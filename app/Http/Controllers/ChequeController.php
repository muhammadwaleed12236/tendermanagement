<?php

namespace App\Http\Controllers;

use App\Models\Bill;
use App\Models\Cheque;
use App\Models\Department;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ChequeController extends Controller
{
    /**
     * Display the Cheque Details Registry and Management Sheet
     */
    public function index(Request $request)
    {
        $query = Cheque::with(['department', 'bill', 'creator']);

        if ($request->filled('cost_center')) {
            $query->where('cost_center', $request->cost_center);
        }

        if ($request->filled('department_id')) {
            $query->where('department_id', $request->department_id);
        }

        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('token_number', 'like', "%{$search}%")
                  ->orWhere('document_number', 'like', "%{$search}%")
                  ->orWhere('company_name', 'like', "%{$search}%")
                  ->orWhere('department_name', 'like', "%{$search}%")
                  ->orWhere('cost_center', 'like', "%{$search}%");
            });
        }

        $cheques = $query->orderBy('cheque_date', 'desc')->orderBy('id', 'desc')->paginate(20)->withQueryString();

        // Consolidated Totals across the active query
        $totals = [
            'cheque_amount' => (clone $query)->sum('cheque_amount'),
            'bill_amount' => (clone $query)->sum('bill_amount'),
            'gst_amount' => (clone $query)->sum('gst_amount'),
            'itax_amount' => (clone $query)->sum('itax_amount'),
            'itax_5_5_amount' => (clone $query)->sum('itax_5_5_amount'),
            'itax_15_amount' => (clone $query)->sum('itax_15_amount'),
            'sst_amount' => (clone $query)->sum('sst_amount'),
            'sst_difference' => (clone $query)->sum('sst_difference'),
            'vendor_gst_difference' => (clone $query)->sum('vendor_gst_difference'),
            'agpr_purchased_gst' => (clone $query)->sum('agpr_purchased_gst'),
            'agpr_purchased_itax' => (clone $query)->sum('agpr_purchased_itax'),
            'agpr_repaired_sst' => (clone $query)->sum('agpr_repaired_sst'),
            'agpr_repaired_itax' => (clone $query)->sum('agpr_repaired_itax'),
            'xyz_commission' => (clone $query)->sum('xyz_commission'),
            'our_commission' => (clone $query)->sum('our_commission'),
            'other_commission' => (clone $query)->sum('other_commission'),
            'total_deduction' => (clone $query)->sum('total_deduction'),
            'amount_to_paid_department' => (clone $query)->sum('amount_to_paid_department'),
            'vendor_profit' => (clone $query)->sum('vendor_profit'),
        ];

        // Cost centers list for filter
        $costCenters = Cheque::select('cost_center')->whereNotNull('cost_center')->distinct()->pluck('cost_center');
        $departments = Department::orderBy('name')->get();
        $pendingBills = Bill::where('status', '!=', 'paid')->latest()->get();
        $allCommissionCheques = (clone $query)->where('xyz_commission', '>', 0)->orderBy('cheque_date', 'desc')->orderBy('id', 'desc')->get();

        return view('cheques.index', compact('cheques', 'totals', 'costCenters', 'departments', 'pendingBills', 'allCommissionCheques'));
    }

    /**
     * Show the form for creating a new Cheque entry on a dedicated page
     */
    public function create(Request $request)
    {
        $departments = Department::orderBy('name')->get();
        $companies = \App\Models\Company::where(function ($q) {
            $q->where('status', 'active')->orWhereNull('status');
        })->orderBy('company_name')->get();
        $pendingBills = Bill::where('status', '!=', 'paid')->latest()->get();
        $selectedBillId = $request->query('bill_id');
        $selectedBill = $selectedBillId ? Bill::find($selectedBillId) : null;

        $chequesCount = Cheque::count();
        $totals = [
            'bill_amount' => (float) Cheque::sum('bill_amount'),
            'cheque_amount' => (float) Cheque::sum('cheque_amount'),
            'xyz_commission' => (float) Cheque::sum('xyz_commission'),
            'vendor_profit' => (float) Cheque::sum('vendor_profit'),
        ];

        return view('cheques.create', compact('departments', 'companies', 'pendingBills', 'selectedBill', 'chequesCount', 'totals'));
    }

    /**
     * Store incoming Cheque with Token and Deductions
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'token_number' => 'required|string|max:50',
            'document_number' => 'required|string|max:100', // Cheque / Doc #
            'cheque_date' => 'required|date',
            'company_name' => 'required|string|max:255',
            'cheque_amount' => 'required|numeric|min:0',
            'bill_amount' => 'required|numeric|min:0',
            'cost_center' => 'nullable|string|max:100',
            'department_id' => 'nullable|exists:departments,id',
            'department_name' => 'nullable|string|max:255',
            'bill_id' => 'nullable|exists:bills,id',
            'gst_amount' => 'nullable|numeric|min:0',
            'itax_amount' => 'nullable|numeric|min:0',
            'itax_5_5_amount' => 'nullable|numeric|min:0',
            'itax_15_amount' => 'nullable|numeric|min:0',
            'sst_amount' => 'nullable|numeric|min:0',
            'sst_difference' => 'nullable|numeric|min:0',
            'vendor_gst_difference' => 'nullable|numeric|min:0',
            'agpr_purchased_gst' => 'nullable|numeric|min:0',
            'agpr_purchased_itax' => 'nullable|numeric|min:0',
            'agpr_repaired_sst' => 'nullable|numeric|min:0',
            'agpr_repaired_itax' => 'nullable|numeric|min:0',
            'xyz_commission' => 'nullable|numeric|min:0',
            'our_commission' => 'nullable|numeric|min:0',
            'other_commission' => 'nullable|numeric|min:0',
            'total_deduction' => 'nullable|numeric|min:0',
            'amount_to_paid_department' => 'nullable|numeric|min:0',
            'vendor_profit' => 'nullable|numeric|min:0',
            'remarks' => 'nullable|string|max:500',
            'commissions' => 'nullable|array',
            'commissions.*.type' => 'nullable|string|in:our,other',
            'commissions.*.title' => 'nullable|string|max:255',
            'commissions.*.percentage' => 'nullable|numeric|min:0',
            'commissions.*.amount' => 'nullable|numeric|min:0',
        ]);

        $chequeAmt = (float) $validated['cheque_amount'];

        // Process dynamic commission breakdown entries (Our Commission vs Other Commission)
        $commissionsList = [];
        $totalCommission = 0;
        $ourCommission = 0;
        $otherCommission = 0;
        if ($request->has('commissions') && is_array($request->input('commissions'))) {
            foreach ($request->input('commissions') as $item) {
                $amount = (float) ($item['amount'] ?? 0);
                $title = trim($item['title'] ?? '');
                $pct = isset($item['percentage']) && $item['percentage'] !== '' ? (float) $item['percentage'] : 0;
                $type = in_array(strtolower($item['type'] ?? ''), ['our', 'other']) ? strtolower($item['type']) : 'our';
                
                if (!empty($title) || $amount > 0 || $pct > 0) {
                    $commissionsList[] = [
                        'type' => $type,
                        'title' => !empty($title) ? $title : ($type === 'our' ? 'Our Commission' : 'Other Commission'),
                        'percentage' => $pct,
                        'amount' => $amount,
                    ];
                    $totalCommission += $amount;
                    if ($type === 'other') {
                        $otherCommission += $amount;
                    } else {
                        $ourCommission += $amount;
                    }
                }
            }
        }

        if ($totalCommission > 0 || !empty($commissionsList)) {
            $validated['xyz_commission'] = $totalCommission;
            $validated['our_commission'] = $ourCommission;
            $validated['other_commission'] = $otherCommission;
            $validated['commission_breakdown'] = $commissionsList;
        } elseif (isset($validated['xyz_commission']) && $validated['xyz_commission'] > 0) {
            $validated['our_commission'] = (float) $validated['xyz_commission'];
            $validated['other_commission'] = 0;
            $validated['commission_breakdown'] = [
                [
                    'type' => 'our',
                    'title' => 'Our Commission',
                    'percentage' => $chequeAmt > 0 ? round(($validated['xyz_commission'] / $chequeAmt) * 100, 2) : 0,
                    'amount' => (float) $validated['xyz_commission'],
                ]
            ];
        } else {
            $validated['our_commission'] = 0;
            $validated['other_commission'] = 0;
        }

        // Auto-resolve department name if department_id provided
        if (!empty($validated['department_id']) && empty($validated['department_name'])) {
            $dept = Department::find($validated['department_id']);
            $validated['department_name'] = $dept ? $dept->name : null;
            if (empty($validated['cost_center']) && $dept) {
                $validated['cost_center'] = $dept->cost_center_description ?: $dept->code;
            }
        }

        // Automatic calculation fallbacks
        $itax55 = (float) ($validated['itax_5_5_amount'] ?? 0);
        $itax15 = (float) ($validated['itax_15_amount'] ?? 0);
        if (empty($validated['itax_amount']) && ($itax55 > 0 || $itax15 > 0)) {
            $validated['itax_amount'] = $itax55 + $itax15;
        }

        $gst = (float) ($validated['gst_amount'] ?? 0);
        $itax = (float) ($validated['itax_amount'] ?? 0);
        $sst = (float) ($validated['sst_amount'] ?? 0);
        $comm = (float) ($validated['xyz_commission'] ?? 0);

        if (empty($validated['total_deduction'])) {
            $validated['total_deduction'] = $gst + $itax + $sst + $comm;
        }

        if (empty($validated['amount_to_paid_department'])) {
            $validated['amount_to_paid_department'] = max(0, $chequeAmt - $comm);
        }

        if (!isset($validated['vendor_profit']) || $validated['vendor_profit'] === null || (float)$validated['vendor_profit'] == 0) {
            $validated['vendor_profit'] = $comm;
        }

        $validated['created_by'] = Auth::id();

        $cheque = Cheque::create($validated);

        // If linked to an invoice bill, mark bill as paid
        if (!empty($validated['bill_id'])) {
            $bill = Bill::find($validated['bill_id']);
            if ($bill) {
                $bill->update([
                    'status' => 'paid',
                    'payment_reference' => 'Cheque #' . $validated['document_number'] . ' (Token: ' . $validated['token_number'] . ')',
                    'paid_at' => $validated['cheque_date'],
                ]);
            }
        }

        \App\Models\ActivityLog::record(
            action: 'created',
            module: 'Cheques',
            description: "Recorded Cheque #{$cheque->document_number} (Token: {$cheque->token_number}) for {$cheque->company_name} of PKR " . number_format($cheque->cheque_amount) . " (Total Deduction: PKR " . number_format($cheque->total_deduction) . ").",
            subject: $cheque,
            properties: [
                'document_number' => $cheque->document_number,
                'token_number' => $cheque->token_number,
                'company_name' => $cheque->company_name,
                'cheque_amount' => $cheque->cheque_amount,
                'total_deduction' => $cheque->total_deduction,
                'commission' => $cheque->xyz_commission,
            ]
        );

        \App\Services\NotificationService::chequeCreated($cheque);

        return redirect()->route('cheques.index')->with('success', 'Cheque #' . $validated['document_number'] . ' (Token ' . $validated['token_number'] . ') recorded successfully.');
    }

    /**
     * Delete a cheque record
     */
    public function destroy(Cheque $cheque)
    {
        if (!Auth::user()->isAdmin() && !Auth::user()->hasPermission('cheques.delete')) {
            abort(403, 'Unauthorized.');
        }

        $doc = $cheque->document_number;
        $tok = $cheque->token_number;
        $comp = $cheque->company_name;
        $amt = (float) $cheque->cheque_amount;

        $cheque->delete();

        \App\Models\ActivityLog::record(
            action: 'deleted',
            module: 'Cheques',
            description: "Deleted Cheque record #{$doc} (Token: {$tok}) for {$comp} (PKR " . number_format($amt) . ").",
            properties: ['document_number' => $doc, 'token_number' => $tok, 'company_name' => $comp, 'amount' => $amt]
        );

        return redirect()->route('cheques.index')->with('success', 'Cheque record removed successfully.');
    }
}
