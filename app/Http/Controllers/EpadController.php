<?php

namespace App\Http\Controllers;

use App\Models\ActivityLog;
use App\Models\Company;
use App\Models\Department;
use App\Models\Epad;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\Rule;

class EpadController extends Controller
{
    /**
     * EPADS 2.0 Management Dashboard & Listing (Combined Directory & Reports)
     */
    public function index(Request $request)
    {
        $baseQuery = Epad::with(['department', 'company']);

        // Search Keywords Filter (Items / Work Description, Ref #, Tender No, Department, Vendor)
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
                  })
                  ->orWhereHas('company', function ($cq) use ($search) {
                      $cq->where('company_name', 'like', "%{$search}%")
                         ->orWhere('name', 'like', "%{$search}%");
                  });
            });
        }

        // Vendor Filter
        if ($request->filled('vendor_id')) {
            $baseQuery->where('company_id', $request->vendor_id);
        } elseif ($request->filled('company_id')) {
            $baseQuery->where('company_id', $request->company_id);
        }

        // Department Filter
        if ($request->filled('department_id')) {
            $baseQuery->where('department_id', $request->department_id);
        }

        // Procurement Type Filter
        if ($request->filled('procurement_type') && $request->procurement_type !== 'All' && $request->procurement_type !== 'All Types') {
            $baseQuery->where('category', $request->procurement_type);
        }

        // Status Filter
        if ($request->filled('status') && $request->status !== 'All' && $request->status !== 'All Statuses') {
            $st = trim($request->status);
            if (str_contains($st, 'Accept') || str_contains($st, 'Award')) {
                $baseQuery->where(function ($q) {
                    $q->where('status', 'like', '%Accepted%')
                      ->orWhere('status', 'like', '%Awarded%')
                      ->orWhere('tender_outcome', 'won');
                });
            } elseif (str_contains($st, 'Evaluation') || str_contains($st, 'Pending')) {
                $baseQuery->where(function ($q) {
                    $q->where('status', 'like', '%Evaluation%')
                      ->orWhere('status', 'like', '%Pending%')
                      ->orWhere('status', 'draft')
                      ->orWhere('tender_outcome', 'in_progress');
                });
            } elseif (str_contains($st, 'Reject') || str_contains($st, 'Disqualif')) {
                $baseQuery->where(function ($q) {
                    $q->where('status', 'like', '%Rejected%')
                      ->orWhere('status', 'like', '%Disqualified%')
                      ->orWhere('tender_outcome', 'lost');
                });
            } else {
                $baseQuery->where('status', $st);
            }
        }

        // Date Wise Filter
        if ($request->filled('date_from')) {
            $baseQuery->whereDate('closing_date', '>=', $request->date_from);
        }
        if ($request->filled('date_to')) {
            $baseQuery->whereDate('closing_date', '<=', $request->date_to);
        }
        if ($request->filled('date_range')) {
            $parts = explode(' - ', $request->date_range);
            if (count($parts) === 2) {
                $baseQuery->whereDate('closing_date', '>=', trim($parts[0]))
                          ->whereDate('closing_date', '<=', trim($parts[1]));
            }
        }

        // Summary Cards Metric Calculations (from all EPAD records dynamically)
        $allEpads = Epad::with(['department', 'company'])->get();
        $totalRecords = $allEpads->count();
        $acceptedRecords = $allEpads->filter(fn($e) => $e->isAccepted())->count();
        $underEvalRecords = $allEpads->filter(fn($e) => $e->isUnderEvaluation())->count();
        $rejectedRecords = $allEpads->filter(fn($e) => $e->isRejected())->count();
        $pendingDocsRecords = $allEpads->filter(fn($e) => !$e->hasLoiAttachment())->count();
        $totalBidAmount = (float) $allEpads->sum('budget');

        $stats = [
            'total_count' => $totalRecords,
            'total_pct' => 100,
            'accepted_count' => $acceptedRecords,
            'accepted_pct' => $totalRecords > 0 ? min(100, round(($acceptedRecords / $totalRecords) * 100)) : 0,
            'under_eval_count' => $underEvalRecords,
            'under_eval_pct' => $totalRecords > 0 ? min(100, round(($underEvalRecords / $totalRecords) * 100)) : 0,
            'rejected_count' => $rejectedRecords,
            'rejected_pct' => $totalRecords > 0 ? min(100, round(($rejectedRecords / $totalRecords) * 100)) : 0,
            'pending_docs_count' => $pendingDocsRecords,
            'pending_docs_pct' => $totalRecords > 0 ? min(100, round(($pendingDocsRecords / $totalRecords) * 100)) : 0,
            'total_bid_amount' => $totalBidAmount,
            'bid_amount_pct' => 100,
        ];

        // Card-click Filters
        $cardFilter = $request->input('card_filter');
        if ($cardFilter) {
            switch ($cardFilter) {
                case 'accepted':
                    $baseQuery->where(function ($q) {
                        $q->where('status', 'like', '%Accepted%')
                          ->orWhere('status', 'like', '%Awarded%')
                          ->orWhere('tender_outcome', 'won');
                    });
                    break;
                case 'under_evaluation':
                case 'under_eval':
                    $baseQuery->where(function ($q) {
                        $q->where('status', 'like', '%Evaluation%')
                          ->orWhere('status', 'like', '%Pending%')
                          ->orWhere('status', 'draft')
                          ->orWhere('tender_outcome', 'in_progress');
                    });
                    break;
                case 'rejected':
                    $baseQuery->where(function ($q) {
                        $q->where('status', 'like', '%Rejected%')
                          ->orWhere('status', 'like', '%Disqualified%')
                          ->orWhere('tender_outcome', 'lost');
                    });
                    break;
                case 'pending_docs':
                    $baseQuery->where(function ($q) {
                        $q->whereNull('letter_of_intent_file')
                          ->orWhere('letter_of_intent_file', '');
                    });
                    break;
                case 'total':
                case 'bid_amount':
                default:
                    break;
            }
        }

        $epads = (clone $baseQuery)->latest('id')->paginate(15)->withQueryString();

        $departments = Department::where('status', 'active')->orderBy('name')->get();
        $companies = Company::where(function ($q) {
            $q->where('status', 'active')->orWhereNull('status');
        })->orderBy('company_name')->get();
        $procurementTypes = ['Open Competitive Bid.', 'Petty Purchase', 'Request for Quotation'];

        return view('epads.index', compact('epads', 'departments', 'companies', 'procurementTypes', 'stats', 'cardFilter'));
    }

    /**
     * Page: + Create EPADS Form (Matching Client Reference Screenshot 02)
     */
    public function create()
    {
        $departments = Department::where('status', 'active')->orderBy('name')->get();
        $companies = Company::where(function ($q) {
            $q->where('status', 'active')->orWhereNull('status');
        })->orderBy('company_name')->get();
        $procurementTypes = ['Open Competitive Bid.', 'Petty Purchase', 'Request for Quotation'];

        return view('epads.create', compact('departments', 'companies', 'procurementTypes'));
    }

    /**
     * Store new ePads record
     */
    public function store(Request $request)
    {
        $isDraft = $request->input('action') === 'draft' || $request->has('save_draft');

        $validated = $request->validate([
            'company_id' => 'required|exists:companies,id',
            'department_id' => 'required|exists:departments,id',
            'procurement_type' => 'required|string|max:100',
            'ref_no' => 'required|string|max:100',
            'items_description' => 'required|string',
            'closing_date' => 'required',
            'bid_amount' => 'required|numeric|min:0',
            'status' => 'nullable|string',
            'letter_of_intent_file' => 'nullable|file|mimes:pdf,doc,docx|max:5120',
        ], [
            'company_id.required' => 'Please select a registered Vendor.',
            'department_id.required' => 'Please select a Department.',
            'procurement_type.required' => 'Please select a Procurement Type.',
            'ref_no.required' => 'Reference Number / Bid ID is required.',
            'items_description.required' => 'Items / Work Description is required.',
            'closing_date.required' => 'Closing Date & Time is required.',
            'bid_amount.required' => 'Bid Amount (PKR) is required.',
        ]);

        $epad = new Epad();
        $epad->company_id = $validated['company_id'];
        $epad->department_id = $validated['department_id'];
        $epad->category = $validated['procurement_type'];
        $epad->reference_no = trim($validated['ref_no']);
        $epad->tender_no = $epad->reference_no;
        $epad->title = trim($validated['items_description']);
        $epad->description = trim($validated['items_description']);
        $epad->closing_date = $validated['closing_date'];
        $epad->submission_deadline = $validated['closing_date'];
        $epad->opening_date = $validated['closing_date'];
        $epad->budget = $validated['bid_amount'];

        // Status Workflow
        $rawStatus = $request->input('status', 'Accepted / Awarded');
        if ($isDraft) {
            $epad->status = 'Under Evaluation / Pending';
            $epad->tender_outcome = 'in_progress';
        } elseif (str_contains($rawStatus, 'Accept') || str_contains($rawStatus, 'Award')) {
            $epad->status = 'Accepted / Awarded';
            $epad->tender_outcome = 'won';
            $epad->awarded_amount = $validated['bid_amount'];
        } elseif (str_contains($rawStatus, 'Reject') || str_contains($rawStatus, 'Disqualif')) {
            $epad->status = 'Rejected / Disqualified';
            $epad->tender_outcome = 'lost';
        } else {
            $epad->status = 'Under Evaluation / Pending';
            $epad->tender_outcome = 'in_progress';
        }

        // LOI Attachment
        if ($request->hasFile('letter_of_intent_file')) {
            $loiPath = $request->file('letter_of_intent_file')->store('tender_documents', 'public');
            $epad->letter_of_intent_file = $loiPath;
            $epad->has_letter_of_intent = true;
            $epad->letter_of_intent_ref = $epad->reference_no;
            $epad->letter_of_intent_date = now()->toDateString();
        }

        $epad->tender_type = 'epad';
        $epad->bidding_procedure = 'single_stage_one_envelope';
        $epad->created_by = Auth::id() ?? 1;
        $epad->is_fully_processed = true;
        $epad->save();

        ActivityLog::record(
            'create',
            'Epad',
            "Created e-Pads procurement entry '{$epad->reference_no}'",
            $epad
        );

        $msg = $isDraft 
            ? "e-Pads draft saved successfully for Ref # {$epad->reference_no}."
            : "e-Pads procurement entry '{$epad->reference_no}' created successfully.";

        return redirect()->route('epads.index')->with('success', $msg);
    }

    /**
     * Edit ePads record
     */
    public function edit(Epad $epad)
    {
        $departments = Department::where('status', 'active')->orderBy('name')->get();
        $companies = Company::where(function ($q) {
            $q->where('status', 'active')->orWhereNull('status');
        })->orderBy('company_name')->get();
        $procurementTypes = ['Open Competitive Bid.', 'Petty Purchase', 'Request for Quotation'];

        return view('epads.edit', compact('epad', 'departments', 'companies', 'procurementTypes'));
    }

    /**
     * Update existing ePads record
     */
    public function update(Request $request, Epad $epad)
    {
        $isDraft = $request->input('action') === 'draft' || $request->has('save_draft');

        $validated = $request->validate([
            'company_id' => 'required|exists:companies,id',
            'department_id' => 'required|exists:departments,id',
            'procurement_type' => 'required|string|max:100',
            'ref_no' => 'required|string|max:100',
            'items_description' => 'required|string',
            'closing_date' => 'required',
            'bid_amount' => 'required|numeric|min:0',
            'status' => 'nullable|string',
            'letter_of_intent_file' => 'nullable|file|mimes:pdf,doc,docx|max:5120',
        ], [
            'company_id.required' => 'Please select a registered Vendor.',
            'department_id.required' => 'Please select a Department.',
            'procurement_type.required' => 'Please select a Procurement Type.',
            'ref_no.required' => 'Reference Number / Bid ID is required.',
            'items_description.required' => 'Items / Work Description is required.',
            'closing_date.required' => 'Closing Date & Time is required.',
            'bid_amount.required' => 'Bid Amount (PKR) is required.',
        ]);

        $epad->company_id = $validated['company_id'];
        $epad->department_id = $validated['department_id'];
        $epad->category = $validated['procurement_type'];
        $epad->reference_no = trim($validated['ref_no']);
        $epad->tender_no = $epad->reference_no;
        $epad->title = trim($validated['items_description']);
        $epad->description = trim($validated['items_description']);
        $epad->closing_date = $validated['closing_date'];
        $epad->submission_deadline = $validated['closing_date'];
        $epad->opening_date = $validated['closing_date'];
        $epad->budget = $validated['bid_amount'];

        // Status Workflow
        $rawStatus = $request->input('status', 'Accepted / Awarded');
        if ($isDraft) {
            $epad->status = 'Under Evaluation / Pending';
            $epad->tender_outcome = 'in_progress';
        } elseif (str_contains($rawStatus, 'Accept') || str_contains($rawStatus, 'Award')) {
            $epad->status = 'Accepted / Awarded';
            $epad->tender_outcome = 'won';
            $epad->awarded_amount = $validated['bid_amount'];
        } elseif (str_contains($rawStatus, 'Reject') || str_contains($rawStatus, 'Disqualif')) {
            $epad->status = 'Rejected / Disqualified';
            $epad->tender_outcome = 'lost';
        } else {
            $epad->status = 'Under Evaluation / Pending';
            $epad->tender_outcome = 'in_progress';
        }

        // LOI Attachment
        if ($request->boolean('remove_loi')) {
            if ($epad->letter_of_intent_file && Storage::disk('public')->exists($epad->letter_of_intent_file)) {
                Storage::disk('public')->delete($epad->letter_of_intent_file);
            }
            $epad->letter_of_intent_file = null;
            $epad->has_letter_of_intent = false;
        } elseif ($request->hasFile('letter_of_intent_file')) {
            if ($epad->letter_of_intent_file && Storage::disk('public')->exists($epad->letter_of_intent_file)) {
                Storage::disk('public')->delete($epad->letter_of_intent_file);
            }
            $loiPath = $request->file('letter_of_intent_file')->store('tender_documents', 'public');
            $epad->letter_of_intent_file = $loiPath;
            $epad->has_letter_of_intent = true;
            $epad->letter_of_intent_ref = $epad->reference_no;
            $epad->letter_of_intent_date = now()->toDateString();
        }

        $epad->save();

        ActivityLog::record(
            'update',
            'Epad',
            "Updated e-Pads procurement entry '{$epad->reference_no}'",
            $epad
        );

        return redirect()->route('epads.index')->with('success', "e-Pads procurement entry '{$epad->reference_no}' updated successfully.");
    }

    /**
     * Download attached Letter of Intent (LOI)
     */
    public function downloadLoi(Epad $epad)
    {
        if ($epad->hasLoiAttachment()) {
            return Storage::disk('public')->download(
                $epad->letter_of_intent_file,
                'Letter_of_Intent_' . ($epad->reference_no ?: $epad->id) . '.' . pathinfo($epad->letter_of_intent_file, PATHINFO_EXTENSION)
            );
        }

        return back()->with('error', 'Letter of Intent (LOI) attachment is not available for this record.');
    }

    /**
     * Delete ePads record
     */
    public function destroy(Epad $epad)
    {
        $ref = $epad->reference_no ?: $epad->tender_no;
        if ($epad->letter_of_intent_file && Storage::disk('public')->exists($epad->letter_of_intent_file)) {
            Storage::disk('public')->delete($epad->letter_of_intent_file);
        }
        $epad->delete();

        return redirect()->route('epads.index')->with('success', "e-Pads record '{$ref}' deleted successfully.");
    }

    /**
     * Legacy reports route redirect to index (merged view)
     */
    public function reports(Request $request)
    {
        return $this->index($request);
    }

    /**
     * Export ePads Reports as CSV (matching table structure)
     */
    public function exportReports(Request $request)
    {
        $query = Epad::with(['department', 'company']);

        if ($request->filled('search')) {
            $search = trim($request->search);
            $query->where(function ($q) use ($search) {
                $q->where('title', 'like', "%{$search}%")
                  ->orWhere('description', 'like', "%{$search}%")
                  ->orWhere('reference_no', 'like', "%{$search}%")
                  ->orWhere('tender_no', 'like', "%{$search}%");
            });
        }

        if ($request->filled('procurement_type') && $request->procurement_type !== 'All Types') {
            $query->where('category', $request->procurement_type);
        }

        if ($request->filled('department_id')) {
            $query->where('department_id', $request->department_id);
        }

        if ($request->filled('status') && $request->status !== 'All Statuses') {
            $query->where('status', $request->status);
        }

        $records = $query->latest('id')->get();
        $fileName = 'epads_records_' . date('Y_m_d_His') . '.csv';

        $headers = [
            'Content-Type' => 'text/csv; charset=utf-8',
            'Content-Disposition' => "attachment; filename=\"{$fileName}\"",
            'Pragma' => 'no-cache',
            'Cache-Control' => 'must-revalidate, post-check=0, pre-check=0',
            'Expires' => '0',
        ];

        $callback = function () use ($records) {
            $file = fopen('php://output', 'w');
            fputs($file, "\xEF\xBB\xBF");

            fputcsv($file, [
                '#',
                'Vendor Name',
                'Department Name',
                'Procurement Type',
                'Ref #',
                'Items / Work Description',
                'Closing Date & Time',
                'Bid Amount (PKR)',
                'Status',
                'Attachment (LOI)',
            ]);

            $index = 1;
            $totalAmount = 0;

            foreach ($records as $record) {
                $bidAmount = (float) $record->budget;
                $totalAmount += $bidAmount;

                fputcsv($file, [
                    $index++,
                    $record->vendor_name,
                    $record->department?->name ?: 'N/A',
                    $record->category ?: 'Petty Purchase',
                    $record->reference_no ?: $record->tender_no ?: 'N/A',
                    $record->title ?: $record->description,
                    $record->closing_date ? $record->closing_date->format('d/m/Y h:i A') : 'N/A',
                    number_format($bidAmount, 2),
                    $record->status ?: 'Accepted / Awarded',
                    $record->hasLoiAttachment() ? 'Attached' : 'Pending',
                ]);
            }

            fputcsv($file, []);
            fputcsv($file, ['Total Records:', count($records), '', '', '', '', 'Total Amount (PKR):', number_format($totalAmount, 2)]);

            fclose($file);
        };

        return response()->stream($callback, 200, $headers);
    }
}
