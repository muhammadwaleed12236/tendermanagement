<?php

namespace App\Http\Controllers;

use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ProductController extends Controller
{
    /**
     * Display listing of products and creation/import hub
     */
    public function index(Request $request)
    {
        $search = $request->query('search');

        $query = Product::query()->latest();

        if ($search) {
            $query->where(function ($q) use ($search) {
                $q->where('description', 'like', "%{$search}%")
                  ->orWhere('item_code', 'like', "%{$search}%")
                  ->orWhere('head_of_account', 'like', "%{$search}%");
            });
        }

        if ($request->wantsJson() || $request->ajax()) {
            return response()->json([
                'products' => $query->take(100)->get(['id', 'description', 'unit', 'default_price', 'head_of_account', 'item_code']),
            ]);
        }

        $products = $query->paginate(20);
        $totalCount = Product::count();

        return view('products.index', compact('products', 'search', 'totalCount'));
    }

    /**
     * Store a single newly created product
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'description' => 'required|string|max:1000',
            'head_of_account' => 'nullable|string|max:255',
            'unit' => 'nullable|string|max:50',
            'default_price' => 'nullable|numeric|min:0',
            'item_code' => 'nullable|string|max:100',
        ]);

        $product = Product::create([
            'description' => trim($validated['description']),
            'head_of_account' => !empty($validated['head_of_account']) ? trim($validated['head_of_account']) : null,
            'unit' => $validated['unit'] ?: 'Nos',
            'default_price' => $validated['default_price'] ?? 0,
            'item_code' => $validated['item_code'] ?? null,
            'created_by' => Auth::id(),
        ]);

        \App\Models\ActivityLog::record(
            action: 'created',
            module: 'Products',
            description: "Added product [{$product->description}] to catalog (Head: {$product->head_of_account}, Unit: {$product->unit}, Price: PKR " . number_format($product->default_price, 2) . ").",
            subject: $product
        );

        if ($request->wantsJson() || $request->ajax()) {
            return response()->json([
                'success' => true,
                'message' => 'Product created successfully!',
                'product' => $product,
            ]);
        }

        return redirect()->route('products.index')->with('success', "Product '{$product->description}' created successfully!");
    }

    /**
     * Update an existing product
     */
    public function update(Request $request, Product $product)
    {
        $validated = $request->validate([
            'description' => 'required|string|max:1000',
            'head_of_account' => 'nullable|string|max:255',
            'unit' => 'nullable|string|max:50',
            'default_price' => 'nullable|numeric|min:0',
            'item_code' => 'nullable|string|max:100',
        ]);

        $product->update([
            'description' => trim($validated['description']),
            'head_of_account' => !empty($validated['head_of_account']) ? trim($validated['head_of_account']) : null,
            'unit' => $validated['unit'] ?: 'Nos',
            'default_price' => $validated['default_price'] ?? 0,
            'item_code' => $validated['item_code'] ?? null,
        ]);

        \App\Models\ActivityLog::record(
            action: 'updated',
            module: 'Products',
            description: "Updated product [{$product->description}] (Head: {$product->head_of_account}, Unit: {$product->unit}, Price: PKR " . number_format($product->default_price, 2) . ").",
            subject: $product
        );

        if ($request->wantsJson() || $request->ajax()) {
            return response()->json([
                'success' => true,
                'message' => 'Product updated successfully!',
                'product' => $product,
            ]);
        }

        return redirect()->route('products.index')->with('success', "Product '{$product->description}' updated successfully!");
    }

    /**
     * Import products via CSV/Excel file or Bulk Text Paste
     */
    public function import(Request $request)
    {
        $request->validate([
            'file' => 'nullable|file|mimes:csv,txt,xlsx,xls|max:10240',
            'paste_text' => 'nullable|string',
            'default_unit' => 'nullable|string|max:50',
        ]);

        $defaultUnit = $request->input('default_unit', 'Nos');
        $importedCount = 0;
        $descriptions = [];

        // 1. Process bulk pasted text if provided (supports Excel copy-paste: description \t qty \t default_price)
        if ($request->filled('paste_text')) {
            $lines = preg_split('/\r\n|\r|\n/', $request->input('paste_text'));
            foreach ($lines as $lineIndex => $line) {
                $line = trim($line);
                if (empty($line)) continue;

                // Skip header row if user copied headers (e.g. description \t qty \t default_price)
                $lower = strtolower($line);
                if ($lineIndex === 0 && str_contains($lower, 'description') && (str_contains($lower, 'qty') || str_contains($lower, 'price'))) {
                    continue;
                }

                // Detect tab-separated (standard Excel paste) or comma/pipe separated
                $delimiter = str_contains($line, "\t") ? "\t" : (str_contains($line, ",") ? "," : (str_contains($line, "|") ? "|" : null));

                if ($delimiter) {
                    $parts = array_map('trim', explode($delimiter, $line));
                    $desc = $parts[0] ?? '';
                    if (empty($desc)) continue;

                    $headOfAccount = null;
                    $unit = $defaultUnit;
                    $price = 0;

                    if (count($parts) >= 4) {
                        // Col 0: Description | Col 1: Head of Account | Col 2: Unit/Qty | Col 3: Price
                        $headOfAccount = $parts[1] ?: null;
                        $unit = $parts[2] ?: $defaultUnit;
                        $priceStr = str_replace([',', 'PKR', 'Rs', ' '], '', $parts[3] ?? '');
                        $price = is_numeric($priceStr) ? (float) $priceStr : 0;
                    } elseif (count($parts) === 3) {
                        // Check if middle column is text (Head of Account) or numeric (Qty)
                        $middle = $parts[1] ?? '';
                        $third = str_replace([',', 'PKR', 'Rs', ' '], '', $parts[2] ?? '');
                        if (!is_numeric($middle) && is_numeric($third)) {
                            // Col 0: Description | Col 1: Head of Account | Col 2: Price
                            $headOfAccount = $middle ?: null;
                            $price = (float) $third;
                        } else {
                            // Col 0: Description | Col 1: Qty | Col 2: Price
                            $price = is_numeric($third) ? (float) $third : 0;
                        }
                    } elseif (count($parts) === 2) {
                        // Col 0: Description | Col 1: Price or Head of Account
                        $valStr = str_replace([',', 'PKR', 'Rs', ' '], '', $parts[1] ?? '');
                        if (is_numeric($valStr)) {
                            $price = (float) $valStr;
                        } else {
                            $headOfAccount = $parts[1] ?: null;
                        }
                    }

                    $descriptions[] = [
                        'description' => $desc,
                        'head_of_account' => $headOfAccount,
                        'unit' => $unit,
                        'default_price' => $price,
                    ];
                } else {
                    $descriptions[] = [
                        'description' => $line,
                        'head_of_account' => null,
                        'unit' => $defaultUnit,
                        'default_price' => 0,
                    ];
                }
            }
        }

        // 2. Process uploaded file if provided
        if ($request->hasFile('file') && $request->file('file')->isValid()) {
            $file = $request->file('file');
            $extension = strtolower($file->getClientOriginalExtension());
            $path = $file->getRealPath();

            if (in_array($extension, ['csv', 'txt'])) {
                $descriptions = array_merge($descriptions, $this->parseCsvFile($path, $defaultUnit));
            } elseif ($extension === 'xlsx') {
                $descriptions = array_merge($descriptions, $this->parseXlsxFile($path, $defaultUnit));
            }
        }

        if (empty($descriptions)) {
            return back()->with('error', 'No valid product descriptions were found to import. Please check your file or pasted text.');
        }

        // Batch insert or individual creation
        foreach ($descriptions as $item) {
            $desc = trim($item['description']);
            if (empty($desc)) continue;

            Product::create([
                'description' => $desc,
                'head_of_account' => !empty($item['head_of_account']) ? trim($item['head_of_account']) : null,
                'unit' => $item['unit'] ?: $defaultUnit,
                'default_price' => $item['default_price'] ?? 0,
                'created_by' => Auth::id(),
            ]);
            $importedCount++;
        }

        if ($importedCount > 0) {
            \App\Models\ActivityLog::record(
                action: 'imported',
                module: 'Products',
                description: "Imported {$importedCount} products into catalog.",
                properties: ['count' => $importedCount]
            );
        }

        if ($request->wantsJson() || $request->ajax()) {
            return response()->json([
                'success' => true,
                'message' => "Successfully imported {$importedCount} products!",
                'count' => $importedCount,
            ]);
        }

        return redirect()->route('products.index')->with('success', "Successfully imported {$importedCount} products into catalog!");
    }

    /**
     * Download sample CSV template
     */
    public function sampleCsv()
    {
        $headers = [
            'Content-Type' => 'text/csv',
            'Content-Disposition' => 'attachment; filename="products_import_sample.csv"',
        ];

        $callback = function () {
            $handle = fopen('php://output', 'w');
            fputcsv($handle, ['description', 'head_of_account', 'unit', 'default_price']);
            fputcsv($handle, ['Cat6 Shielded Ethernet Cable 305m Roll', 'A03901 - Stationary & General', 'Nos', '18500.00']);
            fputcsv($handle, ['A4 Photocopy Paper 80gsm 500 Sheets', 'A03901 - Office Supplies', 'Box', '1450.00']);
            fputcsv($handle, ['Fresh Apples & Fruit Basket', 'FRUIT HEADS OF ACCOUNTS 00343', 'Kg', '250.00']);
            fputcsv($handle, ['Heavy Duty Office Executive Chair', 'A09601 - Purchase of Furniture', 'Nos', '24000.00']);
            fputcsv($handle, ['Laserjet Toner Cartridge Black 85A', 'A03901 - Computer Consumables', 'Units', '6500.00']);
            fclose($handle);
        };

        return response()->stream($callback, 200, $headers);
    }

    /**
     * Delete a product
     */
    public function destroy(Request $request, Product $product)
    {
        $desc = $product->description;
        $product->delete();

        \App\Models\ActivityLog::record(
            action: 'deleted',
            module: 'Products',
            description: "Deleted product [{$desc}] from catalog.",
            properties: ['description' => $desc]
        );

        if ($request->wantsJson() || $request->ajax()) {
            return response()->json([
                'success' => true,
                'message' => 'Product deleted successfully!',
            ]);
        }

        return back()->with('success', "Product '{$desc}' removed from catalog.");
    }

    /**
     * Parse CSV / TXT file
     */
    private function parseCsvFile(string $path, string $defaultUnit): array
    {
        $items = [];
        if (($handle = fopen($path, 'r')) !== false) {
            $header = null;
            $descIndex = 0;
            $headIndex = null;
            $unitIndex = null;
            $priceIndex = null;
            $isFirstRow = true;

            while (($row = fgetcsv($handle, 2048, ',')) !== false) {
                // If single column or string separated by other delimiters
                if (count($row) === 1 && str_contains($row[0], "\t")) {
                    $row = explode("\t", $row[0]);
                }

                if ($isFirstRow) {
                    $isFirstRow = false;
                    // Detect if this is a header
                    $lowered = array_map(fn($v) => strtolower(trim($v)), $row);
                    if (in_array('description', $lowered) || in_array('item', $lowered) || in_array('product', $lowered)) {
                        foreach ($lowered as $idx => $colName) {
                            if (str_contains($colName, 'desc') || str_contains($colName, 'item') || str_contains($colName, 'product') || str_contains($colName, 'title') || str_contains($colName, 'name')) {
                                $descIndex = $idx;
                            } elseif (str_contains($colName, 'head') || str_contains($colName, 'account')) {
                                $headIndex = $idx;
                            } elseif (str_contains($colName, 'unit') || str_contains($colName, 'uom')) {
                                $unitIndex = $idx;
                            } elseif (str_contains($colName, 'price') || str_contains($colName, 'rate')) {
                                $priceIndex = $idx;
                            }
                        }
                        continue; // Skip header row
                    }
                }

                $desc = isset($row[$descIndex]) ? trim($row[$descIndex]) : '';
                if (!empty($desc)) {
                    $headOfAccount = ($headIndex !== null && isset($row[$headIndex]) && trim($row[$headIndex]) !== '') ? trim($row[$headIndex]) : null;
                    $unit = ($unitIndex !== null && isset($row[$unitIndex]) && trim($row[$unitIndex]) !== '') ? trim($row[$unitIndex]) : $defaultUnit;
                    $price = ($priceIndex !== null && isset($row[$priceIndex])) ? (float) preg_replace('/[^0-9.]/', '', $row[$priceIndex]) : 0;
                    $items[] = [
                        'description' => $desc,
                        'head_of_account' => $headOfAccount,
                        'unit' => $unit,
                        'default_price' => $price,
                    ];
                }
            }
            fclose($handle);
        }
        return $items;
    }

    /**
     * Parse XLSX file using ZipArchive without requiring external heavy libraries
     */
    private function parseXlsxFile(string $path, string $defaultUnit): array
    {
        $items = [];
        $zip = new \ZipArchive();
        if ($zip->open($path) === true) {
            $sharedStrings = [];
            // 1. Read shared strings if present
            if (($xmlString = $zip->getFromName('xl/sharedStrings.xml')) !== false) {
                $xml = simplexml_load_string($xmlString);
                if ($xml) {
                    foreach ($xml->si as $si) {
                        $sharedStrings[] = (string) ($si->t ?? $si->r->t ?? '');
                    }
                }
            }

            // 2. Read first sheet
            if (($xmlSheet = $zip->getFromName('xl/worksheets/sheet1.xml')) !== false) {
                $sheet = simplexml_load_string($xmlSheet);
                if ($sheet && isset($sheet->sheetData->row)) {
                    $isFirstRow = true;
                    foreach ($sheet->sheetData->row as $row) {
                        $rowCells = [];
                        foreach ($row->c as $c) {
                            $val = (string) $c->v;
                            if (isset($c['t']) && (string) $c['t'] === 's') {
                                $val = $sharedStrings[(int) $val] ?? $val;
                            }
                            $rowCells[] = $val;
                        }

                        if (empty($rowCells)) continue;

                        $firstCell = trim($rowCells[0] ?? '');
                        if ($isFirstRow) {
                            $isFirstRow = false;
                            if (stripos($firstCell, 'desc') !== false || stripos($firstCell, 'item') !== false || stripos($firstCell, 'product') !== false) {
                                continue; // Skip header
                            }
                        }

                        if (!empty($firstCell)) {
                            $headOfAccount = null;
                            $unit = $defaultUnit;
                            $price = 0;

                            if (count($rowCells) >= 4) {
                                $headOfAccount = trim($rowCells[1] ?? '') ?: null;
                                $unit = trim($rowCells[2] ?? '') ?: $defaultUnit;
                                $price = isset($rowCells[3]) ? (float) preg_replace('/[^0-9.]/', '', $rowCells[3]) : 0;
                            } elseif (count($rowCells) === 3) {
                                $c1 = trim($rowCells[1] ?? '');
                                $c2 = isset($rowCells[2]) ? (float) preg_replace('/[^0-9.]/', '', $rowCells[2]) : 0;
                                $unit = $c1 ?: $defaultUnit;
                                $price = $c2;
                            } elseif (count($rowCells) === 2) {
                                $price = isset($rowCells[1]) ? (float) preg_replace('/[^0-9.]/', '', $rowCells[1]) : 0;
                            }

                            $items[] = [
                                'description' => $firstCell,
                                'head_of_account' => $headOfAccount,
                                'unit' => $unit,
                                'default_price' => $price,
                            ];
                        }
                    }
                }
            }
            $zip->close();
        }
        return $items;
    }
}
