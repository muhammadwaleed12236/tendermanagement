<?php

namespace App\Http\Controllers;

use App\Models\Department;
use App\Models\Epad;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rule;

class EpadController extends Controller
{
    /**
     * Page 1: Existing Records / ePads Listing
     */
    public function index(Request $request)
    {
        $baseQuery = Epad::with('department');

        if ($request->filled('search')) {
            $search = trim($request->search);
            $baseQuery->where(function ($q) use ($search) {
                $q->where('title', 'like', "%{$search}%")
                  ->orWhere('description', 'like', "%{$search}%")
                  ->orWhere('reference_no', 'like', "%{$search}%")
                  ->orWhere('tender_no', 'like', "%{$search}%")
                  ->orWhereHas('department', function ($dq) use ($search) {
                      $dq->where('name', 'like', "%{$search}%")
                         ->orWhere('code', 'like', "%{$search}%");
                  });
            });
        }

        if ($request->filled('department_id')) {
            $baseQuery->where('department_id', $request->department_id);
        }

        if ($request->filled('procurement_type')) {
            $baseQuery->where('category', $request->procurement_type);
        }

        if ($request->filled('status')) {
            $st = trim($request->status);
            $baseQuery->where('status', $st);
        }

        if ($request->filled('date_from')) {
            $baseQuery->whereDate('closing_date', '>=', $request->date_from);
        }

        if ($request->filled('date_to')) {
            $baseQuery->whereDate('closing_date', '<=', $request->date_to);
        }

        // Summary Statistics (calculated dynamically from actual database records)
        $stats = [
            'total_count' => (clone $baseQuery)->count(),
            'accepted_count' => (clone $baseQuery)->where('status', 'Accepted')->count(),
            'rejected_count' => (clone $baseQuery)->where('status', 'Rejected')->count(),
            'total_bid_amount' => (float) (clone $baseQuery)->sum('budget'),
        ];

        $epads = (clone $baseQuery)->latest('id')->paginate(15)->withQueryString();
        $departments = Department::where('status', 'active')->orderBy('name')->get();

        return view('epads.index', compact('epads', 'departments', 'stats'));
    }

    /**
     * Page 2: + Create ePads Form
     */
    public function create()
    {
        $departments = Department::where('status', 'active')->orderBy('name')->get();
        $procurementTypes = ['Petty Purchase', 'Request for Quotation'];

        return view('epads.create', compact('departments', 'procurementTypes'));
    }

    /**
     * Store new ePads record
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'items_description' => 'required|string|max:500',
            'department_id' => 'required|exists:departments,id',
            'procurement_type' => 'required|in:Petty Purchase,Request for Quotation',
            'closing_date' => 'required|date',
            'bid_amount' => 'required|numeric|min:0',
            'ref_no' => 'nullable|string|max:100|unique:tenders,reference_no',
            'status' => 'nullable|in:Accepted,Rejected',
        ], [
            'items_description.required' => 'Items / Work Description is required.',
            'department_id.required' => 'Please select a Department.',
            'procurement_type.required' => 'Procurement Type must be Petty Purchase or Request for Quotation.',
            'closing_date.required' => 'Closing Date & Time is required.',
            'bid_amount.required' => 'Bid Amount is required.',
            'ref_no.unique' => 'This Ref # is already associated with another record.',
        ]);

        $epad = new Epad();
        $epad->title = trim($validated['items_description']);
        $epad->description = trim($validated['items_description']);
        $epad->department_id = $validated['department_id'];
        $epad->category = $validated['procurement_type'];
        $epad->closing_date = $validated['closing_date'];
        $epad->submission_deadline = $validated['closing_date'];
        $epad->opening_date = $validated['closing_date'];
        $epad->budget = $validated['bid_amount'];
        // Ref # is manually entered after bid submission; do not auto-generate
        $epad->reference_no = !empty($validated['ref_no']) ? trim($validated['ref_no']) : null;
        $epad->tender_no = $epad->reference_no;
        $epad->status = $validated['status'] ?? 'Accepted';
        $epad->tender_outcome = ($epad->status === 'Accepted') ? 'won' : 'lost';
        $epad->tender_type = 'epad';
        $epad->bidding_procedure = 'single_stage_one_envelope';
        $epad->created_by = Auth::id() ?? 1;
        $epad->save();

        return redirect()->route('epads.index')->with('success', 'ePads record created successfully.');
    }

    /**
     * Edit ePads record
     */
    public function edit(Epad $epad)
    {
        $departments = Department::where('status', 'active')->orderBy('name')->get();
        $procurementTypes = ['Petty Purchase', 'Request for Quotation'];

        return view('epads.edit', compact('epad', 'departments', 'procurementTypes'));
    }

    /**
     * Update existing ePads record
     */
    public function update(Request $request, Epad $epad)
    {
        $validated = $request->validate([
            'items_description' => 'required|string|max:500',
            'department_id' => 'required|exists:departments,id',
            'procurement_type' => 'required|in:Petty Purchase,Request for Quotation',
            'closing_date' => 'required|date',
            'bid_amount' => 'required|numeric|min:0',
            'ref_no' => ['nullable', 'string', 'max:100', Rule::unique('tenders', 'reference_no')->ignore($epad->id)],
            'status' => 'required|in:Accepted,Rejected',
        ], [
            'ref_no.unique' => 'This Ref # is already associated with another record.',
        ]);

        $epad->title = trim($validated['items_description']);
        $epad->description = trim($validated['items_description']);
        $epad->department_id = $validated['department_id'];
        $epad->category = $validated['procurement_type'];
        $epad->closing_date = $validated['closing_date'];
        $epad->submission_deadline = $validated['closing_date'];
        $epad->opening_date = $validated['closing_date'];
        $epad->budget = $validated['bid_amount'];
        $epad->reference_no = !empty($validated['ref_no']) ? trim($validated['ref_no']) : null;
        $epad->tender_no = $epad->reference_no;
        $epad->status = $validated['status'];
        $epad->tender_outcome = ($validated['status'] === 'Accepted') ? 'won' : 'lost';
        $epad->save();

        return redirect()->route('epads.index')->with('success', 'ePads record updated successfully.');
    }

    /**
     * Quick Update Ref # after bid submission
     */
    public function updateRef(Request $request, Epad $epad)
    {
        $validated = $request->validate([
            'ref_no' => ['required', 'string', 'max:100', Rule::unique('tenders', 'reference_no')->ignore($epad->id)],
        ], [
            'ref_no.unique' => 'This Ref # is already associated with another record.',
        ]);

        $epad->reference_no = trim($validated['ref_no']);
        $epad->tender_no = $epad->reference_no;
        $epad->save();

        if ($request->wantsJson()) {
            return response()->json([
                'success' => true,
                'ref_no' => $epad->reference_no,
                'message' => 'Ref # updated successfully.',
            ]);
        }

        return back()->with('success', 'Ref # updated successfully for this ePads record.');
    }

    /**
     * Quick Update Status (Accepted / Rejected)
     */
    public function updateStatus(Request $request, Epad $epad)
    {
        $validated = $request->validate([
            'status' => 'required|in:Accepted,Rejected',
        ]);

        $epad->status = $validated['status'];
        $epad->tender_outcome = ($validated['status'] === 'Accepted') ? 'won' : 'lost';
        $epad->save();

        if ($request->wantsJson()) {
            return response()->json([
                'success' => true,
                'status' => $epad->status,
                'message' => 'Status updated successfully.',
            ]);
        }

        return back()->with('success', "Status updated to {$epad->status}.");
    }

    /**
     * Delete ePads record
     */
    public function destroy(Epad $epad)
    {
        $epad->delete();
        return redirect()->route('epads.index')->with('success', 'ePads record deleted successfully.');
    }

    /**
     * Page 3: ePads Reports
     */
    public function reports(Request $request)
    {
        $query = Epad::with('department');

        if ($request->filled('search')) {
            $search = trim($request->search);
            $query->where(function ($q) use ($search) {
                $q->where('title', 'like', "%{$search}%")
                  ->orWhere('description', 'like', "%{$search}%")
                  ->orWhere('reference_no', 'like', "%{$search}%")
                  ->orWhere('tender_no', 'like', "%{$search}%")
                  ->orWhereHas('department', function ($dq) use ($search) {
                      $dq->where('name', 'like', "%{$search}%");
                  });
            });
        }

        if ($request->filled('procurement_type')) {
            $query->where('category', $request->procurement_type);
        }

        if ($request->filled('department_id')) {
            $query->where('department_id', $request->department_id);
        }

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        if ($request->filled('date_from')) {
            $query->whereDate('closing_date', '>=', $request->date_from);
        }

        if ($request->filled('date_to')) {
            $query->whereDate('closing_date', '<=', $request->date_to);
        }

        // Real dynamic database metrics
        $stats = [
            'total_count' => (clone $query)->count(),
            'accepted_count' => (clone $query)->where('status', 'Accepted')->count(),
            'rejected_count' => (clone $query)->where('status', 'Rejected')->count(),
            'total_bid_amount' => (float) (clone $query)->sum('budget'),
        ];

        $epads = $query->latest('closing_date')->paginate(20)->withQueryString();
        $departments = Department::where('status', 'active')->orderBy('name')->get();
        $procurementTypes = ['Petty Purchase', 'Request for Quotation'];

        return view('epads.reports', compact('epads', 'departments', 'stats', 'procurementTypes'));
    }

    /**
     * Export ePads Reports as CSV
     */
    public function exportReports(Request $request)
    {
        $query = Epad::with('department');

        if ($request->filled('search')) {
            $search = trim($request->search);
            $query->where(function ($q) use ($search) {
                $q->where('title', 'like', "%{$search}%")
                  ->orWhere('reference_no', 'like', "%{$search}%")
                  ->orWhereHas('department', function ($dq) use ($search) {
                      $dq->where('name', 'like', "%{$search}%");
                  });
            });
        }

        if ($request->filled('procurement_type')) {
            $query->where('category', $request->procurement_type);
        }

        if ($request->filled('department_id')) {
            $query->where('department_id', $request->department_id);
        }

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        if ($request->filled('date_from')) {
            $query->whereDate('closing_date', '>=', $request->date_from);
        }

        if ($request->filled('date_to')) {
            $query->whereDate('closing_date', '<=', $request->date_to);
        }

        $records = $query->latest('closing_date')->get();
        $fileName = 'epads_report_' . date('Y_m_d_His') . '.csv';

        $headers = [
            'Content-Type' => 'text/csv; charset=utf-8',
            'Content-Disposition' => "attachment; filename=\"{$fileName}\"",
            'Pragma' => 'no-cache',
            'Cache-Control' => 'must-revalidate, post-check=0, pre-check=0',
            'Expires' => '0',
        ];

        $callback = function () use ($records) {
            $file = fopen('php://output', 'w');
            // Add UTF-8 BOM for Excel compatibility
            fputs($file, "\xEF\xBB\xBF");

            // CSV Header
            fputcsv($file, [
                '#',
                'Ref #',
                'Items / Work Description',
                'Department Name',
                'Procurement Type',
                'Closing Date & Time',
                'Status',
                'Bid Amount (PKR)',
            ]);

            $index = 1;
            $totalAmount = 0;

            foreach ($records as $record) {
                $bidAmount = (float) $record->budget;
                $totalAmount += $bidAmount;

                fputcsv($file, [
                    $index++,
                    $record->reference_no ?: 'N/A',
                    $record->title,
                    $record->department?->name ?: 'N/A',
                    $record->category ?: 'Petty Purchase',
                    $record->closing_date ? $record->closing_date->format('d/m/Y h:i A') : 'N/A',
                    $record->status ?: 'Accepted',
                    number_format($bidAmount, 2),
                ]);
            }

            // Summary Row
            fputcsv($file, []);
            fputcsv($file, ['Total Records:', count($records), '', '', '', '', 'Total Amount (PKR):', number_format($totalAmount, 2)]);

            fclose($file);
        };

        return response()->stream($callback, 200, $headers);
    }
}
