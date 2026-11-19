<?php

namespace App\Http\Controllers;

use App\Models\ActivityLog;
use App\Models\BankAccount;
use App\Models\Company;
use App\Models\Department;
use App\Models\Tender;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;

class TenderController extends Controller
{
    public function index(Request $request)
    {
        $query = Tender::with(['creator', 'bids', 'department', 'company', 'tenderFeeBankAccount', 'bidSecurityBankAccount']);

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        if ($request->filled('bidding_procedure')) {
            $query->where('bidding_procedure', $request->bidding_procedure);
        }

        if ($request->filled('tender_type')) {
            $query->where('tender_type', $request->tender_type);
        }

        if ($request->filled('tender_outcome')) {
            $query->where('tender_outcome', $request->tender_outcome);
        }

        if ($request->filled('department_id')) {
            $query->where('department_id', $request->department_id);
        }

        if ($request->filled('company_id')) {
            $query->where('company_id', $request->company_id);
        }

        if ($request->filled('tender_type')) {
            $query->where('tender_type', $request->tender_type);
        }

        if ($request->filled('category')) {
            $query->where('category', $request->category);
        }

        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('title', 'like', "%{$search}%")
                  ->orWhere('tender_no', 'like', "%{$search}%")
                  ->orWhere('reference_no', 'like', "%{$search}%")
                  ->orWhere('description', 'like', "%{$search}%")
                  ->orWhere('bid_security_instrument_no', 'like', "%{$search}%");
            });
        }

        $tenders = $query->latest('submission_deadline')->paginate(15)->withQueryString();
        
        $categories = Tender::whereNotNull('category')->distinct()->pluck('category');
        $departments = Department::where('status', 'active')->orderBy('name')->get();
        $companies = Company::where('status', 'active')->orderBy('name')->get();

        // Metric Counters
        $totalCount = Tender::count();
        $inProgressCount = Tender::where('tender_outcome', 'in_progress')->count();
        $wonCount = Tender::where('tender_outcome', 'won')->orWhere('status', 'awarded')->count();
        $lostCount = Tender::where('tender_outcome', 'lost')->orWhere('stage_1_status', 'disqualified')->count();
        $twoEnvelopeCount = Tender::where('bidding_procedure', 'single_stage_two_envelope')->count();
        $epadCount = Tender::where('tender_type', 'epad')->count();
        $tenderTypeCount = Tender::where('tender_type', 'tender')->orWhereNull('tender_type')->count();

        return view('tenders.index', compact(
            'tenders',
            'categories',
            'departments',
            'companies',
            'totalCount',
            'inProgressCount',
            'wonCount',
            'lostCount',
            'twoEnvelopeCount',
            'epadCount',
            'tenderTypeCount'
        ));
    }

    /**
     * Comprehensive Tenders & Work Orders Reporting System
     */
    public function reports(Request $request)
    {
        $query = Tender::with(['creator', 'department', 'company', 'tenderFeeBankAccount', 'bidSecurityBankAccount']);

        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('title', 'like', "%{$search}%")
                  ->orWhere('tender_no', 'like', "%{$search}%")
                  ->orWhere('reference_no', 'like', "%{$search}%")
                  ->orWhere('work_order_no', 'like', "%{$search}%")
                  ->orWhere('award_letter_ref', 'like', "%{$search}%")
                  ->orWhere('bid_security_instrument_no', 'like', "%{$search}%");
            });
        }

        if ($request->filled('outcome')) {
            $query->where('tender_outcome', $request->outcome);
        }

        if ($request->filled('department_id')) {
            $query->where('department_id', $request->department_id);
        }

        if ($request->filled('company_id')) {
            $query->where('company_id', $request->company_id);
        }

        if ($request->filled('bidding_procedure')) {
            $query->where('bidding_procedure', $request->bidding_procedure);
        }

        if ($request->filled('tender_type')) {
            $query->where('tender_type', $request->tender_type);
        }

        if ($request->filled('tender_fee_status')) {
            if ($request->tender_fee_status === 'paid') {
                $query->where('is_tender_fee_paid', true);
            } elseif ($request->tender_fee_status === 'unpaid') {
                $query->where(function ($q) {
                    $q->where('is_tender_fee_paid', false)->orWhereNull('is_tender_fee_paid');
                });
            }
        }

        if ($request->filled('bid_security_status')) {
            $query->where('bid_security_status', $request->bid_security_status);
        }

        if ($request->filled('date_from')) {
            $query->whereDate('submission_deadline', '>=', $request->date_from);
        }

        if ($request->filled('date_to')) {
            $query->whereDate('submission_deadline', '<=', $request->date_to);
        }

        $allTenders = (clone $query)->get();
        $tenders = $query->latest('submission_deadline')->paginate(25)->withQueryString();

        // Metrics for summary cards
        $totalCount = $allTenders->count();
        $totalBudget = $allTenders->sum('budget');
        $wonTenders = $allTenders->filter(fn($t) => $t->isWon());
        $totalWonValue = $wonTenders->sum(function ($t) {
            return $t->awarded_amount ?: $t->budget;
        });
        $totalTenderFeesPaid = $allTenders->where('is_tender_fee_paid', true)->sum('tender_fee_amount');
        $totalBidSecuritySubmitted = $allTenders->where('bid_security_status', 'submitted')->sum('bid_security_amount');
        $totalBidSecurityReleased = $allTenders->where('bid_security_status', 'released_returned')->sum('bid_security_amount');
        $docsCompletedCount = $wonTenders->filter(fn($t) => $t->isDocumentsCompleted())->count();

        $departments = Department::where('status', 'active')->orderBy('name')->get();
        $companies = Company::where(function ($q) {
            $q->where('status', 'active')->orWhereNull('status');
        })->orderBy('company_name')->get();
        $bankAccounts = BankAccount::where('status', 'active')->orderBy('bank_name')->get();

        return view('tenders.reports', compact(
            'tenders',
            'allTenders',
            'totalCount',
            'totalBudget',
            'totalWonValue',
            'totalTenderFeesPaid',
            'totalBidSecuritySubmitted',
            'totalBidSecurityReleased',
            'wonTenders',
            'docsCompletedCount',
            'departments',
            'companies',
            'bankAccounts'
        ));
    }

    public function create()
    {
        if (!Auth::user()->isAdmin()) {
            abort(403, 'Unauthorized action. Only administrators can publish tenders.');
        }

        $departments = Department::where('status', 'active')->orderBy('name')->get();
        $companies = Company::where(function ($q) {
            $q->where('status', 'active')->orWhereNull('status');
        })->orderBy('company_name')->get();
        $bankAccounts = BankAccount::where('status', 'active')->orderBy('bank_name')->get();

        return view('tenders.create', compact('departments', 'companies', 'bankAccounts'));
    }

    public function store(Request $request)
    {
        if (!Auth::user()->isAdmin()) {
            abort(403, 'Unauthorized action.');
        }

        $validated = $request->validate([
            'tender_type' => 'nullable|in:tender,epad',
            'closing_date' => 'nullable|date',
            'title' => 'required|string|max:500',
            'tender_no' => 'nullable|string|max:255',
            'bidding_procedure' => 'required|in:single_stage_one_envelope,single_stage_two_envelope',
            'department_id' => 'required|exists:departments,id',
            'company_id' => 'nullable|exists:companies,id',
            'category' => 'nullable|string|max:100',
            'budget' => 'nullable|numeric|min:0',
            // Tender Fee
            'tender_fee_amount' => 'nullable|numeric|min:0',
            'is_tender_fee_paid' => 'nullable|boolean',
            'tender_fee_bank_account_id' => 'nullable|exists:bank_accounts,id',
            'tender_fee_payment_mode' => 'nullable|string|max:50',
            'tender_fee_ref_no' => 'nullable|string|max:255',
            'tender_fee_date' => 'nullable|date',
            'tender_fee_receipt' => 'nullable|file|mimes:pdf,docx,jpg,png|max:10240',
            // Bid Security
            'bid_security_amount' => 'nullable|numeric|min:0',
            'bid_security_percentage' => 'nullable|numeric|min:0|max:100',
            'bid_security_type' => 'nullable|string|max:50',
            'bid_security_bank_account_id' => 'nullable|exists:bank_accounts,id',
            'bid_security_instrument_no' => 'nullable|string|max:255',
            'bid_security_date' => 'nullable|date',
            'bid_security_expiry_date' => 'nullable|date',
            'bid_security_status' => 'nullable|string|max:50',
            'bid_security_scan' => 'nullable|file|mimes:pdf,docx,jpg,png|max:10240',
            // Dates
            'collection_start_date' => 'nullable|date',
            'collection_start_notes' => 'nullable|string|max:255',
            'collection_end_date' => 'nullable|date',
            'collection_end_notes' => 'nullable|string|max:255',
            'submission_deadline' => 'nullable|date',
            'opening_date' => 'nullable|date',
            'technical_opening_date' => 'nullable|date',
            'description' => 'nullable|string',
            'requirements' => 'nullable|string',
            'document' => 'nullable|file|mimes:pdf,docx,zip|max:20480',
        ]);

        $tenderType = $validated['tender_type'] ?? 'tender';
        $closingDate = $validated['closing_date'] ?? null;

        if ($tenderType === 'epad') {
            if (empty($closingDate)) {
                $closingDate = $validated['submission_deadline'] ?? null;
            }
            if (empty($closingDate)) {
                return back()->withErrors(['closing_date' => 'Closing Date & Time is required for EPAD 2.0.'])->withInput();
            }
            $submissionDeadline = $closingDate;
            $generalOpening = $closingDate;
            $technicalOpening = $closingDate;
            $financialOpening = $closingDate;
            $collectionStartDate = null;
            $collectionStartNotes = null;
            $collectionEndDate = null;
            $collectionEndNotes = null;
            $category = !empty($validated['category']) ? $validated['category'] : 'EPAD 2.0';
        } else {
            $submissionDeadline = $validated['submission_deadline'] ?? $closingDate;
            if (empty($submissionDeadline)) {
                return back()->withErrors(['submission_deadline' => 'Tender Submission Deadline is required.'])->withInput();
            }
            $isTwoEnvelope = ($validated['bidding_procedure'] === 'single_stage_two_envelope');
            if ($isTwoEnvelope) {
                $technicalOpening = !empty($validated['technical_opening_date']) 
                    ? $validated['technical_opening_date'] 
                    : ($validated['opening_date'] ?? null);
                $generalOpening = $technicalOpening;
                $financialOpening = null;
            } else {
                $generalOpening = !empty($validated['opening_date']) 
                    ? $validated['opening_date'] 
                    : ($validated['technical_opening_date'] ?? null);
                $technicalOpening = $generalOpening;
                $financialOpening = $generalOpening;
            }
            $collectionStartDate = $validated['collection_start_date'] ?? null;
            $collectionStartNotes = $validated['collection_start_notes'] ?: 'During working hours';
            $collectionEndDate = $validated['collection_end_date'] ?? null;
            $collectionEndNotes = $validated['collection_end_notes'] ?: 'During working hours';
            $category = $validated['category'] ?? 'General Procurement';
        }

        $referenceNo = 'TND-' . date('Y') . '-' . strtoupper(Str::random(5));
        $tenderNo = !empty($validated['tender_no']) ? trim($validated['tender_no']) : $referenceNo;

        $docPath = null;
        if ($request->hasFile('document')) {
            $docPath = $request->file('document')->store('tender_documents', 'public');
        }

        $tenderFeeReceipt = null;
        if ($request->hasFile('tender_fee_receipt')) {
            $tenderFeeReceipt = $request->file('tender_fee_receipt')->store('tender_documents', 'public');
        }

        $bidSecurityFile = null;
        if ($request->hasFile('bid_security_scan')) {
            $bidSecurityFile = $request->file('bid_security_scan')->store('tender_documents', 'public');
        }

        $isTenderFeePaid = $request->boolean('is_tender_fee_paid');

        // EPAD 2.0 does not use Tender Form Fees or Bid Security (CDR)
        if ($tenderType === 'epad') {
            $tenderFeeAmount = null;
            $isTenderFeePaid = false;
            $tenderFeeBankId = null;
            $tenderFeeMode = null;
            $tenderFeeRef = null;
            $tenderFeeDate = null;
            $tenderFeeReceipt = null;

            $bidSecurityAmount = null;
            $bidSecurityPct = null;
            $bidSecurityType = null;
            $bidSecurityBankId = null;
            $bidSecurityInstNo = null;
            $bidSecurityDate = null;
            $bidSecurityExpiry = null;
            $bidSecurityStatus = null;
            $bidSecurityFile = null;
        } else {
            $tenderFeeAmount = $validated['tender_fee_amount'] ?? null;
            $tenderFeeBankId = $validated['tender_fee_bank_account_id'] ?? null;
            $tenderFeeMode = $validated['tender_fee_payment_mode'] ?? ($isTenderFeePaid ? 'cash' : null);
            $tenderFeeRef = $validated['tender_fee_ref_no'] ?? null;
            $tenderFeeDate = $validated['tender_fee_date'] ?? null;

            $bidSecurityAmount = $validated['bid_security_amount'] ?? null;
            $bidSecurityPct = $validated['bid_security_percentage'] ?? null;
            $bidSecurityType = $validated['bid_security_type'] ?? 'pay_order';
            $bidSecurityBankId = $validated['bid_security_bank_account_id'] ?? null;
            $bidSecurityInstNo = $validated['bid_security_instrument_no'] ?? null;
            $bidSecurityDate = $validated['bid_security_date'] ?? null;
            $bidSecurityExpiry = $validated['bid_security_expiry_date'] ?? null;
            $bidSecurityStatus = $validated['bid_security_status'] ?? 'submitted';
        }

        $tender = Tender::create([
            'title' => $validated['title'],
            'reference_no' => $referenceNo,
            'tender_no' => $tenderNo,
            'tender_type' => $tenderType,
            'closing_date' => $closingDate,
            'bidding_procedure' => $validated['bidding_procedure'],
            'department_id' => $validated['department_id'],
            'company_id' => $validated['company_id'] ?? null,
            'category' => $category,
            'budget' => $validated['budget'] ?? null,
            // Tender Fee
            'tender_fee_amount' => $tenderFeeAmount,
            'is_tender_fee_paid' => $isTenderFeePaid,
            'tender_fee_bank_account_id' => $tenderFeeBankId,
            'tender_fee_payment_mode' => $tenderFeeMode,
            'tender_fee_ref_no' => $tenderFeeRef,
            'tender_fee_date' => $tenderFeeDate,
            'tender_fee_receipt_file' => $tenderFeeReceipt,
            // Bid Security
            'bid_security_amount' => $bidSecurityAmount,
            'bid_security_percentage' => $bidSecurityPct,
            'bid_security_type' => $bidSecurityType,
            'bid_security_bank_account_id' => $bidSecurityBankId,
            'bid_security_instrument_no' => $bidSecurityInstNo,
            'bid_security_date' => $bidSecurityDate,
            'bid_security_expiry_date' => $bidSecurityExpiry,
            'bid_security_status' => $bidSecurityStatus,
            'bid_security_file' => $bidSecurityFile,
            // Schedule
            'collection_start_date' => $collectionStartDate,
            'collection_start_notes' => $collectionStartNotes,
            'collection_end_date' => $collectionEndDate,
            'collection_end_notes' => $collectionEndNotes,
            'submission_deadline' => $submissionDeadline,
            'opening_date' => $generalOpening,
            'technical_opening_date' => $technicalOpening,
            'financial_opening_date' => $financialOpening,
            'current_stage' => 'stage_1',
            'stage_1_status' => 'pending',
            'tender_outcome' => 'in_progress',
            'description' => $validated['description'] ?? $validated['title'],
            'requirements' => $validated['requirements'] ?? null,
            'document_path' => $docPath,
            'status' => 'published',
            'created_by' => Auth::id(),
        ]);

        ActivityLog::record(
            'create',
            'Tender',
            "Published tender '{$tender->tender_no}' with procedure '{$tender->procedure_label}'",
            $tender,
            [
                'bidding_procedure' => $tender->bidding_procedure,
                'submission_deadline' => $tender->submission_deadline,
                'department_id' => $tender->department_id,
                'is_tender_fee_paid' => $tender->is_tender_fee_paid,
                'bid_security_amount' => $tender->bid_security_amount,
            ]
        );

        \App\Services\NotificationService::tenderCreated($tender);

        return redirect()->route('tenders.index')->with('success', "Tender published successfully! Reference No: {$referenceNo} | Tender No: {$tenderNo}");
    }

    public function show(Tender $tender)
    {
        $tender->load(['creator', 'department', 'company', 'tenderFeeBankAccount', 'bidSecurityBankAccount', 'bids.vendor', 'bids.evaluations.evaluator', 'bills.vendor']);

        $userBid = null;
        if (Auth::check() && Auth::user()->isVendor()) {
            $userBid = $tender->bids->where('user_id', Auth::id())->first();
        }

        $bankAccounts = BankAccount::where('status', 'active')->orderBy('bank_name')->get();

        return view('tenders.show', compact('tender', 'userBid', 'bankAccounts'));
    }

    public function edit(Tender $tender)
    {
        if (!Auth::user()->isAdmin()) {
            abort(403, 'Unauthorized action.');
        }

        $departments = Department::where('status', 'active')->orderBy('name')->get();
        $companies = Company::where(function ($q) {
            $q->where('status', 'active')->orWhereNull('status');
        })->orderBy('company_name')->get();
        $bankAccounts = BankAccount::where('status', 'active')->orderBy('bank_name')->get();

        return view('tenders.edit', compact('tender', 'departments', 'companies', 'bankAccounts'));
    }

    public function update(Request $request, Tender $tender)
    {
        if (!Auth::user()->isAdmin()) {
            abort(403, 'Unauthorized action.');
        }

        $validated = $request->validate([
            'tender_type' => 'nullable|in:tender,epad',
            'closing_date' => 'nullable|date',
            'title' => 'required|string|max:500',
            'tender_no' => 'nullable|string|max:255',
            'bidding_procedure' => 'required|in:single_stage_one_envelope,single_stage_two_envelope',
            'department_id' => 'required|exists:departments,id',
            'company_id' => 'nullable|exists:companies,id',
            'category' => 'nullable|string|max:100',
            'budget' => 'nullable|numeric|min:0',
            // Tender Fee
            'tender_fee_amount' => 'nullable|numeric|min:0',
            'is_tender_fee_paid' => 'nullable|boolean',
            'tender_fee_bank_account_id' => 'nullable|exists:bank_accounts,id',
            'tender_fee_payment_mode' => 'nullable|string|max:50',
            'tender_fee_ref_no' => 'nullable|string|max:255',
            'tender_fee_date' => 'nullable|date',
            // Bid Security
            'bid_security_amount' => 'nullable|numeric|min:0',
            'bid_security_percentage' => 'nullable|numeric|min:0|max:100',
            'bid_security_type' => 'nullable|string|max:50',
            'bid_security_bank_account_id' => 'nullable|exists:bank_accounts,id',
            'bid_security_instrument_no' => 'nullable|string|max:255',
            'bid_security_date' => 'nullable|date',
            'bid_security_expiry_date' => 'nullable|date',
            'bid_security_status' => 'nullable|string|max:50',
            // Award / Work Order
            'awarded_amount' => 'nullable|numeric|min:0',
            'work_order_no' => 'nullable|string|max:255',
            'work_order_date' => 'nullable|date',
            // Schedule
            'collection_start_date' => 'nullable|date',
            'collection_start_notes' => 'nullable|string|max:255',
            'collection_end_date' => 'nullable|date',
            'collection_end_notes' => 'nullable|string|max:255',
            'submission_deadline' => 'nullable|date',
            'opening_date' => 'nullable|date',
            'technical_opening_date' => 'nullable|date',
            'financial_opening_date' => 'nullable|date',
            'status' => 'required|in:draft,published,under_evaluation,awarded,closed,cancelled',
            'tender_outcome' => 'required|in:in_progress,won,lost,cancelled',
            'description' => 'nullable|string',
            'requirements' => 'nullable|string',
            'document' => 'nullable|file|mimes:pdf,docx,zip|max:20480',
        ]);

        if (($validated['tender_type'] ?? 'tender') === 'epad') {
            $closing = $validated['closing_date'] ?? $validated['submission_deadline'] ?? null;
            if ($closing) {
                $validated['closing_date'] = $closing;
                $validated['submission_deadline'] = $closing;
                $validated['opening_date'] = $closing;
                $validated['technical_opening_date'] = $closing;
                $validated['financial_opening_date'] = $closing;
                $validated['collection_start_date'] = null;
                $validated['collection_end_date'] = null;
            }
            // In EPAD 2.0, Tender Fee and Bid Security modules are not used
            $validated['tender_fee_amount'] = null;
            $validated['is_tender_fee_paid'] = false;
            $validated['tender_fee_bank_account_id'] = null;
            $validated['tender_fee_payment_mode'] = null;
            $validated['tender_fee_ref_no'] = null;
            $validated['tender_fee_date'] = null;
            $validated['bid_security_amount'] = null;
            $validated['bid_security_percentage'] = null;
            $validated['bid_security_type'] = null;
            $validated['bid_security_bank_account_id'] = null;
            $validated['bid_security_instrument_no'] = null;
            $validated['bid_security_date'] = null;
            $validated['bid_security_expiry_date'] = null;
        }

        if ($request->hasFile('document')) {
            $validated['document_path'] = $request->file('document')->store('tender_documents', 'public');
        }

        $validated['is_tender_fee_paid'] = $request->boolean('is_tender_fee_paid');

        if (!empty($validated['tender_no'])) {
            $validated['tender_no'] = trim($validated['tender_no']);
        }

        $tender->update($validated);

        ActivityLog::record(
            'update',
            'Tender',
            "Updated tender '{$tender->tender_no}'",
            $tender
        );

        return redirect()->route('tenders.show', $tender)->with('success', 'Tender details updated successfully.');
    }

    /**
     * Dedicated Action to Update Financial Securities & Fees on an existing Tender
     */
    public function updateFinancialSecurities(Request $request, Tender $tender)
    {
        if (!Auth::user()->isAdmin()) {
            abort(403, 'Unauthorized action.');
        }

        $request->validate([
            'tender_fee_amount' => 'nullable|numeric|min:0',
            'tender_fee_bank_account_id' => 'nullable|exists:bank_accounts,id',
            'tender_fee_payment_mode' => 'nullable|string|max:50',
            'tender_fee_ref_no' => 'nullable|string|max:255',
            'tender_fee_date' => 'nullable|date',
            'tender_fee_receipt' => 'nullable|file|mimes:pdf,docx,jpg,png|max:10240',
            'bid_security_amount' => 'nullable|numeric|min:0',
            'bid_security_percentage' => 'nullable|numeric|min:0|max:100',
            'bid_security_type' => 'nullable|string|max:50',
            'bid_security_bank_account_id' => 'nullable|exists:bank_accounts,id',
            'bid_security_instrument_no' => 'nullable|string|max:255',
            'bid_security_date' => 'nullable|date',
            'bid_security_expiry_date' => 'nullable|date',
            'bid_security_status' => 'nullable|string|max:50',
            'bid_security_scan' => 'nullable|file|mimes:pdf,docx,jpg,png|max:10240',
            'awarded_amount' => 'nullable|numeric|min:0',
            'work_order_no' => 'nullable|string|max:255',
            'work_order_date' => 'nullable|date',
        ]);

        $updateData = [
            'tender_fee_amount' => $request->tender_fee_amount,
            'is_tender_fee_paid' => $request->boolean('is_tender_fee_paid'),
            'tender_fee_bank_account_id' => $request->tender_fee_bank_account_id,
            'tender_fee_payment_mode' => $request->tender_fee_payment_mode,
            'tender_fee_ref_no' => $request->tender_fee_ref_no,
            'tender_fee_date' => $request->tender_fee_date,
            'bid_security_amount' => $request->bid_security_amount,
            'bid_security_percentage' => $request->bid_security_percentage,
            'bid_security_type' => $request->bid_security_type ?: 'pay_order',
            'bid_security_bank_account_id' => $request->bid_security_bank_account_id,
            'bid_security_instrument_no' => $request->bid_security_instrument_no,
            'bid_security_date' => $request->bid_security_date,
            'bid_security_expiry_date' => $request->bid_security_expiry_date,
            'bid_security_status' => $request->bid_security_status ?: 'submitted',
            'awarded_amount' => $request->awarded_amount,
            'work_order_no' => $request->work_order_no,
            'work_order_date' => $request->work_order_date,
        ];

        if ($request->hasFile('tender_fee_receipt')) {
            $updateData['tender_fee_receipt_file'] = $request->file('tender_fee_receipt')->store('tender_documents', 'public');
        }

        if ($request->hasFile('bid_security_scan')) {
            $updateData['bid_security_file'] = $request->file('bid_security_scan')->store('tender_documents', 'public');
        }

        $tender->update($updateData);

        ActivityLog::record(
            'update_financials',
            'Tender',
            "Updated Tender Fee & Bid Security financials for tender '{$tender->tender_no}'",
            $tender
        );

        \App\Services\NotificationService::bidSecurityUpdated($tender);

        return redirect()->route('tenders.show', $tender)->with('success', 'Tender Fee & Bid Security financials updated successfully!');
    }

    /**
     * Mark Bid Security (CDR / Pay Order) as Released / Returned
     */
    public function releaseBidSecurity(Request $request, Tender $tender)
    {
        if (!Auth::user()->isAdmin()) {
            abort(403, 'Unauthorized action.');
        }

        $tender->update([
            'bid_security_status' => 'released_returned',
            'bid_security_released_at' => now(),
        ]);

        ActivityLog::record(
            'release_bid_security',
            'Tender',
            "Bid security (PKR {$tender->bid_security_amount}) marked as Released/Returned for tender '{$tender->tender_no}'",
            $tender
        );

        \App\Services\NotificationService::bidSecurityUpdated($tender);

        return redirect()->route('tenders.show', $tender)->with('success', 'Bid Security / CDR marked as Released / Returned to bank account! ✅');
    }

    /**
     * Interactive Stage & Status Progression Workflow
     */
    public function updateStageStatus(Request $request, Tender $tender)
    {
        if (!Auth::user()->isAdmin()) {
            abort(403, 'Unauthorized action.');
        }

        $isTwoEnvelope = $tender->isTwoEnvelope();

        if (!$isTwoEnvelope) {
            // SINGLE STAGE ONE ENVELOPE: Direct Win or Lose
            $request->validate([
                'outcome' => 'required|in:won,lost',
                'notes' => 'nullable|string|max:1000',
                'awarded_amount' => 'nullable|numeric|min:0',
                'work_order_no' => 'nullable|string|max:255',
            ]);

            $outcome = $request->outcome;
            $notes = $request->notes;

            if ($outcome === 'won') {
                $hasLoi = $request->boolean('has_letter_of_intent');
                $hasAward = $request->boolean('has_award_letter');
                $isDocsComplete = ($hasLoi && $hasAward);

                $loiFile = $request->hasFile('letter_of_intent_file') 
                    ? $request->file('letter_of_intent_file')->store('tender_documents', 'public') 
                    : null;
                $awardFile = $request->hasFile('award_letter_file') 
                    ? $request->file('award_letter_file')->store('tender_documents', 'public') 
                    : null;

                $updateData = [
                    'tender_outcome' => 'won',
                    'status' => 'awarded',
                    'stage_1_status' => 'qualified',
                    'stage_1_notes' => $notes ?: 'Single Stage bid accepted and awarded.',
                    'stage_1_completed_at' => now(),
                    'current_stage' => 'completed',
                    'has_letter_of_intent' => $hasLoi,
                    'letter_of_intent_ref' => $request->letter_of_intent_ref,
                    'letter_of_intent_date' => $request->letter_of_intent_date,
                    'has_award_letter' => $hasAward,
                    'award_letter_ref' => $request->award_letter_ref,
                    'award_letter_date' => $request->award_letter_date,
                    'awarded_amount' => $request->awarded_amount ?: $tender->budget,
                    'work_order_no' => $request->work_order_no ?: $request->award_letter_ref,
                    'documents_status' => $isDocsComplete ? 'completed' : 'pending',
                    'documents_completed_at' => $isDocsComplete ? now() : null,
                ];

                if ($loiFile) $updateData['letter_of_intent_file'] = $loiFile;
                if ($awardFile) $updateData['award_letter_file'] = $awardFile;

                $tender->update($updateData);

                $docMsg = $isDocsComplete 
                    ? "Documents Completed! (Both LOI and Award Letter verified) ✅" 
                    : "Documents PENDING: 2 letters (Letter of Intent & Award Letter/Work Order) required.";
                $msg = "Single Stage One Envelope tender marked as WON / Awarded! 🎉 " . $docMsg;
            } else {
                $tender->update([
                    'tender_outcome' => 'lost',
                    'status' => 'closed',
                    'stage_1_status' => 'disqualified',
                    'stage_1_notes' => $notes ?: 'Single Stage bid rejected/disqualified.',
                    'stage_1_completed_at' => now(),
                    'current_stage' => 'completed',
                ]);
                $msg = "Single Stage One Envelope tender marked as LOST / Closed.";
            }

            ActivityLog::record(
                'status_change',
                'Tender',
                "Tender {$tender->tender_no} marked as " . strtoupper($outcome),
                $tender,
                ['outcome' => $outcome, 'notes' => $notes]
            );

            \App\Services\NotificationService::tenderOutcomeChanged($tender, $outcome);

            return redirect()->route('tenders.show', $tender)->with('success', $msg);
        }

        // SINGLE STAGE TWO ENVELOPE WORKFLOW
        if ($tender->current_stage === 'stage_1') {
            // Stage 1 (Technical Bid Evaluation)
            $request->validate([
                'stage_1_action' => 'required|in:win,lose',
                'stage_1_notes' => 'nullable|string|max:1000',
                'financial_opening_date' => 'required_if:stage_1_action,win|nullable|date',
                'financial_opening_notes' => 'nullable|string|max:255',
            ]);

            $action = $request->stage_1_action;
            $notes = $request->stage_1_notes;

            if ($action === 'lose') {
                $tender->update([
                    'stage_1_status' => 'disqualified',
                    'stage_1_notes' => $notes ?: 'Technical bid disqualified / not shortlisted.',
                    'stage_1_completed_at' => now(),
                    'current_stage' => 'completed',
                    'tender_outcome' => 'lost',
                    'status' => 'closed',
                ]);

                ActivityLog::record(
                    'stage_disqualified',
                    'Tender',
                    "Tender {$tender->tender_no} failed Stage 1 (Technical). Outcome: LOST.",
                    $tender,
                    ['stage' => 'stage_1', 'notes' => $notes]
                );

                \App\Services\NotificationService::tenderStageChanged($tender, 'stage_1', 'disqualified');

                return redirect()->route('tenders.show', $tender)->with('info', "Stage 1 (Technical Bid) marked as Disqualified / Lose. Process ended (Tender Lost).");
            } else {
                $financialOpening = $request->financial_opening_date;

                $tender->update([
                    'stage_1_status' => 'qualified',
                    'stage_1_notes' => $notes ?: 'Technical bid qualified successfully.',
                    'stage_1_completed_at' => now(),
                    'current_stage' => 'stage_2',
                    'stage_2_status' => 'pending',
                    'financial_opening_date' => $financialOpening,
                    'status' => 'under_evaluation',
                ]);

                ActivityLog::record(
                    'stage_qualified',
                    'Tender',
                    "Tender {$tender->tender_no} passed Stage 1 (Technical). Financial Stage scheduled for {$financialOpening}.",
                    $tender,
                    ['financial_opening_date' => $financialOpening, 'notes' => $notes]
                );

                \App\Services\NotificationService::tenderStageChanged($tender, 'stage_1', 'qualified');

                return redirect()->route('tenders.show', $tender)->with('success', "Stage 1 (Technical) Qualified! Stage 2 (Financial Opening Date & Time) has been scheduled successfully.");
            }
        } elseif ($tender->current_stage === 'stage_2') {
            // Stage 2 (Financial Bid Evaluation)
            $request->validate([
                'stage_2_action' => 'required|in:win,lose',
                'stage_2_notes' => 'nullable|string|max:1000',
                'awarded_amount' => 'nullable|numeric|min:0',
                'work_order_no' => 'nullable|string|max:255',
            ]);

            $action = $request->stage_2_action;
            $notes = $request->stage_2_notes;

            if ($action === 'win') {
                $hasLoi = $request->boolean('has_letter_of_intent');
                $hasAward = $request->boolean('has_award_letter');
                $isDocsComplete = ($hasLoi && $hasAward);

                $loiFile = $request->hasFile('letter_of_intent_file') 
                    ? $request->file('letter_of_intent_file')->store('tender_documents', 'public') 
                    : null;
                $awardFile = $request->hasFile('award_letter_file') 
                    ? $request->file('award_letter_file')->store('tender_documents', 'public') 
                    : null;

                $updateData = [
                    'stage_2_status' => 'won',
                    'stage_2_notes' => $notes ?: 'Lowest responsive financial bid accepted and awarded.',
                    'stage_2_completed_at' => now(),
                    'current_stage' => 'completed',
                    'tender_outcome' => 'won',
                    'status' => 'awarded',
                    'has_letter_of_intent' => $hasLoi,
                    'letter_of_intent_ref' => $request->letter_of_intent_ref,
                    'letter_of_intent_date' => $request->letter_of_intent_date,
                    'has_award_letter' => $hasAward,
                    'award_letter_ref' => $request->award_letter_ref,
                    'award_letter_date' => $request->award_letter_date,
                    'awarded_amount' => $request->awarded_amount ?: $tender->budget,
                    'work_order_no' => $request->work_order_no ?: $request->award_letter_ref,
                    'documents_status' => $isDocsComplete ? 'completed' : 'pending',
                    'documents_completed_at' => $isDocsComplete ? now() : null,
                ];

                if ($loiFile) $updateData['letter_of_intent_file'] = $loiFile;
                if ($awardFile) $updateData['award_letter_file'] = $awardFile;

                $tender->update($updateData);

                ActivityLog::record(
                    'tender_won',
                    'Tender',
                    "Tender {$tender->tender_no} WON Stage 2 (Financial) and Awarded! 🎉",
                    $tender,
                    ['stage' => 'stage_2', 'notes' => $notes, 'documents_status' => $updateData['documents_status']]
                );

                \App\Services\NotificationService::tenderOutcomeChanged($tender, 'won');

                $docMsg = $isDocsComplete 
                    ? "Documents Completed! (Both LOI and Award Letter verified) ✅" 
                    : "Documents PENDING: 2 letters (Letter of Intent & Award Letter/Work Order) required.";

                return redirect()->route('tenders.show', $tender)->with('success', "Congratulations! Tender Stage 2 (Financial) WON and Awarded! 🎉 " . $docMsg);
            } else {
                $tender->update([
                    'stage_2_status' => 'lost',
                    'stage_2_notes' => $notes ?: 'Financial bid lost to competing vendor.',
                    'stage_2_completed_at' => now(),
                    'current_stage' => 'completed',
                    'tender_outcome' => 'lost',
                    'status' => 'closed',
                ]);

                ActivityLog::record(
                    'tender_lost',
                    'Tender',
                    "Tender {$tender->tender_no} LOST in Stage 2 (Financial).",
                    $tender,
                    ['stage' => 'stage_2', 'notes' => $notes]
                );

                \App\Services\NotificationService::tenderOutcomeChanged($tender, 'lost');

                return redirect()->route('tenders.show', $tender)->with('info', "Tender Stage 2 (Financial) marked as Lost / Closed.");
            }
        }

        return redirect()->route('tenders.show', $tender)->with('info', "Tender is already completed.");
    }

    /**
     * Update Post-Award Documents (Letter of Intent & Award Letter / Work Order)
     */
    public function updatePostAwardDocuments(Request $request, Tender $tender)
    {
        if (!Auth::user()->isAdmin()) {
            abort(403, 'Unauthorized action.');
        }

        $request->validate([
            'letter_of_intent_ref' => 'nullable|string|max:255',
            'letter_of_intent_date' => 'nullable|date',
            'letter_of_intent_file' => 'nullable|file|mimes:pdf,docx,jpg,png|max:10240',
            'award_letter_ref' => 'nullable|string|max:255',
            'award_letter_date' => 'nullable|date',
            'award_letter_file' => 'nullable|file|mimes:pdf,docx,jpg,png|max:10240',
            'work_order_no' => 'nullable|string|max:255',
            'awarded_amount' => 'nullable|numeric|min:0',
        ]);

        $hasLoi = $request->boolean('has_letter_of_intent');
        $hasAward = $request->boolean('has_award_letter');

        $updateData = [
            'has_letter_of_intent' => $hasLoi,
            'letter_of_intent_ref' => $request->letter_of_intent_ref,
            'letter_of_intent_date' => $request->letter_of_intent_date,
            'has_award_letter' => $hasAward,
            'award_letter_ref' => $request->award_letter_ref,
            'award_letter_date' => $request->award_letter_date,
            'work_order_no' => $request->work_order_no ?: ($request->award_letter_ref ?: $tender->work_order_no),
            'awarded_amount' => $request->awarded_amount ?: $tender->awarded_amount,
        ];

        if ($request->hasFile('letter_of_intent_file')) {
            $updateData['letter_of_intent_file'] = $request->file('letter_of_intent_file')->store('tender_documents', 'public');
        }

        if ($request->hasFile('award_letter_file')) {
            $updateData['award_letter_file'] = $request->file('award_letter_file')->store('tender_documents', 'public');
        }

        $isDocsComplete = ($hasLoi && $hasAward);
        $updateData['documents_status'] = $isDocsComplete ? 'completed' : 'pending';
        $updateData['documents_completed_at'] = $isDocsComplete ? ($tender->documents_completed_at ?: now()) : null;

        $tender->update($updateData);

        ActivityLog::record(
            'update_documents',
            'Tender',
            "Updated post-award letters for tender '{$tender->tender_no}'. Status: " . strtoupper($updateData['documents_status']),
            $tender,
            [
                'has_letter_of_intent' => $hasLoi,
                'has_award_letter' => $hasAward,
                'documents_status' => $updateData['documents_status']
            ]
        );

        \App\Services\NotificationService::tenderLettersUpdated($tender, $hasLoi ? 'letter_of_intent' : 'award_letter');

        if ($isDocsComplete) {
            return redirect()->route('tenders.show', $tender)->with('success', 'Documents Completed! Letter of Intent and Award Letter / Work Order have both been verified. ✅');
        } else {
            $missing = [];
            if (!$hasLoi) $missing[] = 'Letter of Intent (LOI)';
            if (!$hasAward) $missing[] = 'Award Letter / Work Order';
            $missingText = implode(' and ', $missing);
            return redirect()->route('tenders.show', $tender)->with('info', "Post-award documentation updated. Status remains PENDING: {$missingText} must be submitted.");
        }
    }

    public function destroy(Tender $tender)
    {
        if (!Auth::user()->isAdmin()) {
            abort(403, 'Unauthorized action.');
        }

        $tenderNo = $tender->tender_no;
        $tender->delete();

        ActivityLog::record(
            'delete',
            'Tender',
            "Deleted tender '{$tenderNo}'",
            null
        );

        return redirect()->route('tenders.index')->with('success', 'Tender deleted successfully.');
    }
}
