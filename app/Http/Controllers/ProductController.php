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
            $query->where('description', 'like', "%{$search}%")
                  ->orWhere('item_code', 'like', "%{$search}%");
        }

        if ($request->wantsJson() || $request->ajax()) {
            return response()->json([
                'products' => $query->take(100)->get(['id', 'description', 'unit', 'default_price']),
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
            'unit' => 'nullable|string|max:50',
            'default_price' => 'nullable|numeric|min:0',
            'item_code' => 'nullable|string|max:100',
        ]);

        $product = Product::create([
            'description' => trim($validated['description']),
            'unit' => $validated['unit'] ?: 'Nos',
            'default_price' => $validated['default_price'] ?? 0,
            'item_code' => $validated['item_code'] ?? null,
            'created_by' => Auth::id(),
        ]);

        \App\Models\ActivityLog::record(
            action: 'created',
            module: 'Products',
            description: "Added product [{$product->description}] to catalog (Unit: {$product->unit}, Price: PKR " . number_format($product->default_price, 2) . ").",
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

                    $price = 0;
                    if (count($parts) >= 3) {
                        // Col 0: Description | Col 1: Qty | Col 2: Price
                        $priceStr = str_replace([',', 'PKR', 'Rs', ' '], '', $parts[2] ?? '');
                        $price = is_numeric($priceStr) ? (float) $priceStr : 0;
                    } elseif (count($parts) === 2) {
                        // Col 0: Description | Col 1: Price or Qty
                        $valStr = str_replace([',', 'PKR', 'Rs', ' '], '', $parts[1] ?? '');
                        $price = is_numeric($valStr) ? (float) $valStr : 0;
                    }

                    $descriptions[] = [
                        'description' => $desc,
                        'unit' => $defaultUnit,
                        'default_price' => $price,
                    ];
                } else {
                    $descriptions[] = [
                        'description' => $line,
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
            fputcsv($handle, ['description', 'unit', 'default_price']);
            fputcsv($handle, ['Cat6 Shielded Ethernet Cable 305m Roll', 'Nos', '18500.00']);
            fputcsv($handle, ['A4 Photocopy Paper 80gsm 500 Sheets', 'Box', '1450.00']);
            fputcsv($handle, ['Heavy Duty Office Executive Chair', 'Nos', '24000.00']);
            fputcsv($handle, ['Ballpoint Pens Blue Box of 10', 'Pack', '450.00']);
            fputcsv($handle, ['Laserjet Toner Cartridge Black 85A', 'Units', '6500.00']);
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
                    $unit = ($unitIndex !== null && isset($row[$unitIndex]) && trim($row[$unitIndex]) !== '') ? trim($row[$unitIndex]) : $defaultUnit;
                    $price = ($priceIndex !== null && isset($row[$priceIndex])) ? (float) preg_replace('/[^0-9.]/', '', $row[$priceIndex]) : 0;
                    $items[] = [
                        'description' => $desc,
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
                            $items[] = [
                                'description' => $firstCell,
                                'unit' => trim($rowCells[1] ?? '') ?: $defaultUnit,
                                'default_price' => isset($rowCells[2]) ? (float) preg_replace('/[^0-9.]/', '', $rowCells[2]) : 0,
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
