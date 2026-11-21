<?php

namespace App\Http\Controllers;

use App\Models\Bill;
use App\Models\BillItem;
use App\Models\Department;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ReportController extends Controller
{
    /**
     * Reports & Analytics Hub Overview
     */
    public function index()
    {
        $totalBillsCount = Bill::count();
        $totalBillsAmount = (float) Bill::sum('gross_amount');
        $totalItemsCount = BillItem::count();
        $totalDepartments = Department::count();

        return view('reports.index', compact(
            'totalBillsCount',
            'totalBillsAmount',
            'totalItemsCount',
            'totalDepartments'
        ));
    }

    /**
     * Master Data Management Report
     * Columns: Sr #, Years, Quarter, Head of Account, Head Description, Cost Center, Department Name, Item Description, Qty, Rate, Vendor Name
     */
    public function masterData(Request $request)
    {
        $query = BillItem::with(['bill.department', 'bill.vendor', 'bill.company', 'bill.budgetHead'])
            ->whereHas('bill');

        // Filter by Year (invoice_date or created_at)
        if ($request->filled('year')) {
            $year = $request->year;
            $query->whereHas('bill', function ($q) use ($year) {
                $q->whereYear('invoice_date', $year)
                  ->orWhere(function ($sq) use ($year) {
                      $sq->whereNull('invoice_date')->whereYear('created_at', $year);
                  });
            });
        }

        // Filter by Quarter
        if ($request->filled('quarter')) {
            $quarter = $request->quarter;
            $query->whereHas('bill', function ($q) use ($quarter) {
                $q->where('quarter', $quarter);
            });
        }

        // Filter by Department
        if ($request->filled('department_id')) {
            $deptId = $request->department_id;
            $query->whereHas('bill', function ($q) use ($deptId) {
                $q->where('department_id', $deptId);
            });
        }

        // Filter by Search Query
        if ($request->filled('search')) {
            $search = trim($request->search);
            $query->where(function ($q) use ($search) {
                $q->where('item_name', 'like', "%{$search}%")
                  ->orWhereHas('bill', function ($bq) use ($search) {
                      $bq->where('bill_number', 'like', "%{$search}%")
                         ->orWhere('head_of_account', 'like', "%{$search}%")
                         ->orWhere('head_description', 'like', "%{$search}%")
                         ->orWhere('cost_center', 'like', "%{$search}%")
                         ->orWhereHas('department', function ($dq) use ($search) {
                             $dq->where('name', 'like', "%{$search}%")
                                ->orWhere('code', 'like', "%{$search}%")
                                ->orWhere('cost_center_description', 'like', "%{$search}%");
                         })
                         ->orWhereHas('vendor', function ($vq) use ($search) {
                             $vq->where('name', 'like', "%{$search}%");
                         })
                         ->orWhereHas('company', function ($cq) use ($search) {
                             $cq->where('name', 'like', "%{$search}%");
                         });
                  });
            });
        }

        // Summary Statistics (calculated on the filtered set)
        $summaryQuery = clone $query;
        $totalRecords = $summaryQuery->count();
        $totalQty = (float) $summaryQuery->sum('quantity');
        $totalAmount = (float) $summaryQuery->sum('total_price');

        // Order and Paginate
        $items = $query->latest('id')->paginate(30)->appends($request->query());

        // Dropdown data for filters
        $departments = Department::orderBy('name')->get();
        $availableYears = Bill::selectRaw('YEAR(COALESCE(invoice_date, created_at)) as bill_year')
            ->distinct()
            ->orderBy('bill_year', 'desc')
            ->pluck('bill_year')
            ->filter()
            ->values();

        if ($availableYears->isEmpty()) {
            $availableYears = collect([2026, 2025]);
        }

        return view('reports.master_data', compact(
            'items',
            'departments',
            'availableYears',
            'totalRecords',
            'totalQty',
            'totalAmount'
        ));
    }

    /**
     * Export Master Data Management Report to Excel / CSV
     */
    public function exportMasterData(Request $request)
    {
        $query = BillItem::with(['bill.department', 'bill.vendor', 'bill.company', 'bill.budgetHead'])
            ->whereHas('bill');

        if ($request->filled('year')) {
            $year = $request->year;
            $query->whereHas('bill', function ($q) use ($year) {
                $q->whereYear('invoice_date', $year)
                  ->orWhere(function ($sq) use ($year) {
                      $sq->whereNull('invoice_date')->whereYear('created_at', $year);
                  });
            });
        }

        if ($request->filled('quarter')) {
            $quarter = $request->quarter;
            $query->whereHas('bill', function ($q) use ($quarter) {
                $q->where('quarter', $quarter);
            });
        }

        if ($request->filled('department_id')) {
            $deptId = $request->department_id;
            $query->whereHas('bill', function ($q) use ($deptId) {
                $q->where('department_id', $deptId);
            });
        }

        if ($request->filled('search')) {
            $search = trim($request->search);
            $query->where(function ($q) use ($search) {
                $q->where('item_name', 'like', "%{$search}%")
                  ->orWhereHas('bill', function ($bq) use ($search) {
                      $bq->where('bill_number', 'like', "%{$search}%")
                         ->orWhere('head_of_account', 'like', "%{$search}%")
                         ->orWhere('head_description', 'like', "%{$search}%")
                         ->orWhere('cost_center', 'like', "%{$search}%")
                         ->orWhereHas('department', function ($dq) use ($search) {
                             $dq->where('name', 'like', "%{$search}%")
                                ->orWhere('code', 'like', "%{$search}%");
                         })
                         ->orWhereHas('vendor', function ($vq) use ($search) {
                             $vq->where('name', 'like', "%{$search}%");
                         })
                         ->orWhereHas('company', function ($cq) use ($search) {
                             $cq->where('name', 'like', "%{$search}%");
                         });
                  });
            });
        }

        $items = $query->latest('id')->get();
        $format = strtolower($request->get('format', 'xls'));
        $fileName = 'Master_Data_Management_Report_' . date('d_M_Y_His') . '.' . ($format === 'csv' ? 'csv' : 'xls');

        if ($format === 'csv') {
            $headers = [
                'Content-Type' => 'text/csv; charset=UTF-8',
                'Content-Disposition' => "attachment; filename=\"{$fileName}\"",
            ];

            return response()->stream(function () use ($items) {
                $file = fopen('php://output', 'w');
                // UTF-8 BOM
                fprintf($file, chr(0xEF).chr(0xBB).chr(0xBF));

                fputcsv($file, [
                    'Sr #',
                    'Years',
                    'Quarter',
                    'Head of Account',
                    'Head Description',
                    'Cost Center',
                    'Department Name',
                    'Item Description',
                    'Qty',
                    'Rate (PKR)',
                    'Vendor Name',
                    'Total Amount (PKR)',
                ]);

                foreach ($items as $idx => $item) {
                    $bill = $item->bill;
                    $year = $bill->invoice_date ? $bill->invoice_date->format('Y') : ($bill->created_at ? $bill->created_at->format('Y') : date('Y'));
                    $quarter = $bill->quarter ?: 'Q1';
                    $headOfAccount = $bill->head_of_account ?: ($bill->budgetHead ? $bill->budgetHead->head_code : ($bill->head_code ?: 'A03901'));
                    $headDesc = $bill->head_description ?: ($bill->budgetHead ? $bill->budgetHead->head_name : $bill->head_description_display);
                    $costCenter = $bill->cost_center ?: ($bill->department ? ($bill->department->cost_center_description ?: $bill->department->code) : 'HQ-01');
                    $deptName = $bill->department ? $bill->department->name : '-';
                    $vendorName = $bill->vendor ? $bill->vendor->name : ($bill->company ? $bill->company->name : 'N/A');

                    fputcsv($file, [
                        $idx + 1,
                        $year,
                        $quarter,
                        $headOfAccount,
                        $headDesc,
                        $costCenter,
                        $deptName,
                        $item->item_name,
                        $item->quantity,
                        $item->unit_price,
                        $vendorName,
                        $item->total_price ?: ($item->quantity * $item->unit_price),
                    ]);
                }

                fclose($file);
            }, 200, $headers);
        }

        // HTML XLS format for rich Excel opening
        return response()->stream(function () use ($items) {
            echo '<!DOCTYPE html><html><head><meta charset="utf-8">';
            echo '<style>
                table { border-collapse: collapse; width: 100%; font-family: Arial, sans-serif; font-size: 11px; }
                th { background-color: #fbd5b5; color: #000; font-weight: bold; border: 1px solid #000; padding: 6px; text-align: center; }
                td { border: 1px solid #ccc; padding: 5px; }
                .text-right { text-align: right; }
                .text-center { text-align: center; }
            </style></head><body>';
            echo '<h3 style="text-align:center;">MASTER DATA MANAGEMENT REPORT</h3>';
            echo '<table><thead><tr>';
            echo '<th>Sr #</th><th>Years</th><th>Quarter</th><th>Head of Account</th><th>Head Description</th><th>Cost Center</th><th>Department Name</th><th>Item Description</th><th>Qty</th><th>Rate</th><th>Vendor Name</th><th>Total Amount</th>';
            echo '</tr></thead><tbody>';

            $totalQty = 0;
            $totalVal = 0;

            foreach ($items as $idx => $item) {
                $bill = $item->bill;
                $year = $bill->invoice_date ? $bill->invoice_date->format('Y') : ($bill->created_at ? $bill->created_at->format('Y') : date('Y'));
                $quarter = $bill->quarter ?: 'Q1';
                $headOfAccount = $bill->head_of_account ?: ($bill->budgetHead ? $bill->budgetHead->head_code : ($bill->head_code ?: 'A03901'));
                $headDesc = $bill->head_description ?: ($bill->budgetHead ? $bill->budgetHead->head_name : $bill->head_description_display);
                $costCenter = $bill->cost_center ?: ($bill->department ? ($bill->department->cost_center_description ?: $bill->department->code) : 'HQ-01');
                $deptName = $bill->department ? $bill->department->name : '-';
                $vendorName = $bill->vendor ? $bill->vendor->name : ($bill->company ? $bill->company->name : 'N/A');
                $lineTotal = (float) ($item->total_price ?: ($item->quantity * $item->unit_price));

                $totalQty += (float) $item->quantity;
                $totalVal += $lineTotal;

                echo '<tr>';
                echo '<td class="text-center">' . ($idx + 1) . '</td>';
                echo '<td class="text-center">' . htmlspecialchars($year) . '</td>';
                echo '<td class="text-center">' . htmlspecialchars($quarter) . '</td>';
                echo '<td>' . htmlspecialchars($headOfAccount) . '</td>';
                echo '<td>' . htmlspecialchars($headDesc) . '</td>';
                echo '<td>' . htmlspecialchars($costCenter) . '</td>';
                echo '<td>' . htmlspecialchars($deptName) . '</td>';
                echo '<td>' . htmlspecialchars($item->item_name) . '</td>';
                echo '<td class="text-right">' . number_format($item->quantity, 2) . '</td>';
                echo '<td class="text-right">' . number_format($item->unit_price, 2) . '</td>';
                echo '<td>' . htmlspecialchars($vendorName) . '</td>';
                echo '<td class="text-right">' . number_format($lineTotal, 2) . '</td>';
                echo '</tr>';
            }

            echo '</tbody><tfoot><tr style="background:#f3f4f6;font-weight:bold;">';
            echo '<td colspan="8" class="text-right">TOTAL:</td>';
            echo '<td class="text-right">' . number_format($totalQty, 2) . '</td>';
            echo '<td></td><td></td>';
            echo '<td class="text-right">' . number_format($totalVal, 2) . '</td>';
            echo '</tr></tfoot></table></body></html>';
        }, 200, [
            'Content-Type' => 'application/vnd.ms-excel; charset=UTF-8',
            'Content-Disposition' => "attachment; filename=\"{$fileName}\"",
        ]);
    }
}
