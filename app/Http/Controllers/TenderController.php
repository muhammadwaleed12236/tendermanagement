<?php

namespace App\Http\Controllers;

use App\Models\ActivityLog;
use App\Models\BankAccount;
use App\Models\Company;
use App\Models\Department;
use App\Models\Tender;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
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
        } else {
            $query->where(function ($q) {
                $q->where('tender_type', 'tender')->orWhereNull('tender_type');
            });
        }

        if ($request->filled('tender_outcome')) {
            $query->where('tender_outcome', $request->tender_outcome);
        }

        if ($request->filled('department_id')) {
            $query->where('department_id', $request->department_id);
        }

        if ($request->filled('opening_date')) {
            $openingDate = $request->opening_date;
            $query->where(function ($q) use ($openingDate) {
                $q->whereDate('opening_date', $openingDate)
                  ->orWhereDate('technical_opening_date', $openingDate)
                  ->orWhereDate('financial_opening_date', $openingDate);
            });
        }

        if ($request->filled('company_id')) {
            $query->where('company_id', $request->company_id);
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
        $companies = Company::where(function ($q) {
            $q->where('status', 'active')->orWhereNull('status');
        })->orderBy('company_name')->get();

        // Metric Counters (Relocated from Create Tender to Tender Details)
        $paperTenders = Tender::where(function ($q) {
            $q->where('tender_type', 'tender')->orWhereNull('tender_type');
        });
        $totalTenderCreated = (clone $paperTenders)->count();
        $totalActiveTender = (clone $paperTenders)->where('status', '!=', 'cancelled')->where(function ($q) {
            $q->whereNull('submission_deadline')->orWhere('submission_deadline', '>=', now());
        })->count();
        $totalInterested = (clone $paperTenders)->where('interest_status', 'interested')->count();
        $totalNotInterested = (clone $paperTenders)->where('interest_status', 'not_interested')->count();

        $activePct = $totalTenderCreated > 0 ? min(100, round(($totalActiveTender / $totalTenderCreated) * 100)) : 0;
        $interestedPct = $totalTenderCreated > 0 ? min(100, round(($totalInterested / $totalTenderCreated) * 100)) : 0;
        $notInterestedPct = $totalTenderCreated > 0 ? min(100, round(($totalNotInterested / $totalTenderCreated) * 100)) : 0;

        $totalCount = $totalTenderCreated;
        $inProgressCount = (clone $paperTenders)->where('tender_outcome', 'in_progress')->count();
        $wonCount = (clone $paperTenders)->where(function ($q) {
            $q->where('tender_outcome', 'won')->orWhere('status', 'awarded');
        })->count();
        $lostCount = (clone $paperTenders)->where(function ($q) {
            $q->where('tender_outcome', 'lost')->orWhere('stage_1_status', 'disqualified');
        })->count();
        $cancelledCount = (clone $paperTenders)->where(function ($q) {
            $q->where('status', 'cancelled')->orWhere('tender_outcome', 'cancelled');
        })->count();
        $twoEnvelopeCount = (clone $paperTenders)->where('bidding_procedure', 'single_stage_two_envelope')->count();
        $epadCount = Tender::where('tender_type', 'epad')->count();
        $tenderTypeCount = $totalCount;

        return view('tenders.index', compact(
            'tenders',
            'categories',
            'departments',
            'companies',
            'totalTenderCreated',
            'totalActiveTender',
            'totalInterested',
            'totalNotInterested',
            'activePct',
            'interestedPct',
            'notInterestedPct',
            'totalCount',
            'inProgressCount',
            'wonCount',
            'lostCount',
            'cancelledCount',
            'twoEnvelopeCount',
            'epadCount',
            'tenderTypeCount'
        ));
    }

    /**
     * Active Bidding Pipeline
     * Dedicated pipeline focused on upcoming opening dates, bidding stages & evaluation status.
     */
    public function pipeline(Request $request)
    {
        $query = Tender::with(['creator', 'department', 'company', 'tenderFeeBankAccount', 'bidSecurityBankAccount']);

        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('title', 'like', "%{$search}%")
                  ->orWhere('tender_no', 'like', "%{$search}%")
                  ->orWhere('reference_no', 'like', "%{$search}%")
                  ->orWhere('description', 'like', "%{$search}%");
            });
        }

        if ($request->filled('department_id')) {
            $query->where('department_id', $request->department_id);
        }

        if ($request->filled('opening_date')) {
            $openingDate = $request->opening_date;
            $query->where(function ($q) use ($openingDate) {
                $q->whereDate('opening_date', $openingDate)
                  ->orWhereDate('technical_opening_date', $openingDate)
                  ->orWhereDate('financial_opening_date', $openingDate);
            });
        }

        if ($request->filled('bidding_procedure')) {
            $query->where('bidding_procedure', $request->bidding_procedure);
        }

        if ($request->filled('stage')) {
            if ($request->stage === 'stage_1') {
                $query->where('bidding_procedure', 'single_stage_two_envelope')->where('current_stage', 'stage_1');
            } elseif ($request->stage === 'stage_2') {
                $query->where('bidding_procedure', 'single_stage_two_envelope')->where('current_stage', 'stage_2');
            } elseif ($request->stage === '1e') {
                $query->where('bidding_procedure', 'single_stage_one_envelope');
            }
        }

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        $statusFilter = $request->input('status') ?: $request->input('outcome');
        if (!empty($statusFilter)) {
            if ($statusFilter === 'cancelled') {
                $query->where(function ($q) {
                    $q->where('status', 'cancelled')->orWhere('tender_outcome', 'cancelled');
                });
            } elseif ($statusFilter === 'won' || $statusFilter === 'awarded') {
                $query->where(function ($q) {
                    $q->where('tender_outcome', 'won')->orWhere('status', 'awarded');
                });
            } elseif ($statusFilter === 'lost' || $statusFilter === 'not_awarded') {
                $query->where(function ($q) {
                    $q->where('tender_outcome', 'lost')->orWhere('status', 'lost')->orWhere('status', 'closed');
                });
            } elseif ($statusFilter === 'in_progress') {
                $query->where(function ($q) {
                    $q->where('tender_outcome', 'in_progress')
                      ->orWhereNull('tender_outcome')
                      ->orWhere('status', 'published')
                      ->orWhere('status', 'draft');
                })->where('status', '!=', 'cancelled')
                  ->where('tender_outcome', '!=', 'cancelled')
                  ->where('status', '!=', 'awarded')
                  ->where('tender_outcome', '!=', 'won')
                  ->where('tender_outcome', '!=', 'lost');
            } else {
                $query->where(function ($q) use ($statusFilter) {
                    $q->where('tender_outcome', $statusFilter)->orWhere('status', $statusFilter);
                });
            }
        } else {
            // Default: focus on active/in-progress pipeline tenders
            $query->where(function ($q) {
                $q->where('tender_outcome', 'in_progress')
                  ->orWhereIn('status', ['published', 'open', 'bidding']);
            });
        }

        // Sort by upcoming opening dates and deadlines
        $tenders = $query->orderByRaw('COALESCE(opening_date, technical_opening_date, submission_deadline) ASC')
                         ->paginate(15)
                         ->withQueryString();

        $departments = Department::where('status', 'active')->orderBy('name')->get();

        // Pipeline Metrics
        $totalActiveCount = Tender::where('tender_outcome', 'in_progress')->count();
        $stage1PendingCount = Tender::where('bidding_procedure', 'single_stage_two_envelope')
                                    ->where('current_stage', 'stage_1')
                                    ->where('tender_outcome', 'in_progress')
                                    ->count();
        $stage2PendingCount = Tender::where('bidding_procedure', 'single_stage_two_envelope')
                                    ->where('current_stage', 'stage_2')
                                    ->where('tender_outcome', 'in_progress')
                                    ->count();
        $upcomingOpeningsCount = Tender::where('tender_outcome', 'in_progress')
                                       ->where(function ($q) {
                                           $q->whereDate('opening_date', '>=', now())
                                             ->orWhereDate('technical_opening_date', '>=', now())
                                             ->orWhereDate('financial_opening_date', '>=', now())
                                             ->orWhereDate('submission_deadline', '>=', now());
                                       })->count();

        return view('tenders.pipeline', compact(
            'tenders',
            'departments',
            'totalActiveCount',
            'stage1PendingCount',
            'stage2PendingCount',
            'upcomingOpeningsCount'
        ));
    }

    /**
     * Comprehensive Tenders & Work Orders Reporting System
     */
    private function buildReportsQuery(Request $request)
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
                  ->orWhere('bid_security_instrument_no', 'like', "%{$search}%")
                  ->orWhereHas('company', function ($cq) use ($search) {
                      $cq->where('company_name', 'like', "%{$search}%")
                         ->orWhere('name', 'like', "%{$search}%");
                  })
                  ->orWhereHas('department', function ($dq) use ($search) {
                      $dq->where('name', 'like', "%{$search}%");
                  });
            });
        }

        $statusFilter = $request->input('status') ?: $request->input('outcome');
        if (!empty($statusFilter)) {
            if ($statusFilter === 'cancelled') {
                $query->where(function ($q) {
                    $q->where('status', 'cancelled')->orWhere('tender_outcome', 'cancelled');
                });
            } elseif ($statusFilter === 'won' || $statusFilter === 'awarded') {
                $query->where(function ($q) {
                    $q->where('tender_outcome', 'won')->orWhere('status', 'awarded')->orWhere('stage_2_status', 'won');
                });
            } elseif ($statusFilter === 'lost' || $statusFilter === 'not_awarded') {
                $query->where(function ($q) {
                    $q->where('tender_outcome', 'lost')->orWhere('status', 'lost')->orWhere('status', 'closed')->orWhere('stage_1_status', 'disqualified');
                });
            } elseif ($statusFilter === 'pending' || $statusFilter === 'in_progress') {
                $query->where(function ($q) {
                    $q->where('tender_outcome', 'in_progress')
                      ->orWhereNull('tender_outcome')
                      ->orWhere('status', 'published')
                      ->orWhere('status', 'draft');
                })->where('status', '!=', 'cancelled')
                  ->where('tender_outcome', '!=', 'cancelled')
                  ->where('status', '!=', 'awarded')
                  ->where('tender_outcome', '!=', 'won')
                  ->where('tender_outcome', '!=', 'lost');
            } elseif ($statusFilter === 'pending_docs') {
                $query->where(function ($q) {
                    $q->where('tender_outcome', 'won')->orWhere('status', 'awarded');
                })->where(function ($q) {
                    $q->where('has_letter_of_intent', false)
                      ->orWhere('has_award_letter', false)
                      ->orWhereNull('has_letter_of_intent')
                      ->orWhereNull('has_award_letter');
                });
            } elseif ($statusFilter !== 'total') {
                $query->where(function ($q) use ($statusFilter) {
                    $q->where('tender_outcome', $statusFilter)->orWhere('status', $statusFilter);
                });
            }
        }

        if ($request->filled('department_id')) {
            $query->where('department_id', $request->department_id);
        }

        if ($request->filled('opening_date')) {
            $openingDate = $request->opening_date;
            $query->where(function ($q) use ($openingDate) {
                $q->whereDate('opening_date', $openingDate)
                  ->orWhereDate('technical_opening_date', $openingDate)
                  ->orWhereDate('financial_opening_date', $openingDate);
            });
        }

        if ($request->filled('company_id')) {
            $query->where('company_id', $request->company_id);
        }

        if ($request->filled('bidding_procedure')) {
            $query->where('bidding_procedure', $request->bidding_procedure);
        }

        if ($request->filled('tender_type')) {
            $query->where('tender_type', $request->tender_type);
        } else {
            $query->where(function ($q) {
                $q->where('tender_type', 'tender')->orWhereNull('tender_type');
            });
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

        // Only interested/completed tenders enter the Tender Management workflow
        $query->where(function ($q) {
            $q->where('interest_status', '!=', 'not_interested')
              ->orWhereNull('interest_status');
        })->where(function ($q) {
            $q->where('is_fully_processed', true)
              ->orWhere('tender_outcome', 'won')
              ->orWhere('status', 'awarded')
              ->orWhere('status', 'cancelled')
              ->orWhere('tender_outcome', 'cancelled')
              ->orWhere(function ($sub) {
                  $sub->where('initial_entry_only', false)->orWhereNull('initial_entry_only');
              });
        });

        return $query;
    }

    private function applyReportsCardFilter($query, ?string $cardFilter): void
    {
        if (!$cardFilter) {
            return;
        }

        switch ($cardFilter) {
            case 'cancelled':
            case 'cancelled_tenders':
                $query->where(function ($q) {
                    $q->where('status', 'cancelled')->orWhere('tender_outcome', 'cancelled');
                });
                break;
            case 'pending':
            case 'pending_tenders':
                $query->where(function ($q) {
                    $q->where('tender_outcome', 'in_progress')
                      ->orWhere('status', 'published')
                      ->orWhere('status', 'draft');
                })->where('status', '!=', 'cancelled')
                  ->where('status', '!=', 'awarded')
                  ->where('tender_outcome', '!=', 'won')
                  ->where('tender_outcome', '!=', 'lost');
                break;
            case 'awarded':
            case 'awarded_tenders':
            case 'awarded_value':
                $query->where(function ($q) {
                    $q->where('tender_outcome', 'won')
                      ->orWhere('status', 'awarded')
                      ->orWhere('stage_2_status', 'won');
                });
                break;
            case 'not_awarded':
            case 'not_awarded_tenders':
                $query->where(function ($q) {
                    $q->where('tender_outcome', 'lost')
                      ->orWhere('status', 'lost')
                      ->orWhere('stage_1_status', 'disqualified');
                });
                break;
            case 'pending_docs':
            case 'pending_award_docs':
                $query->where(function ($q) {
                    $q->where('tender_outcome', 'won')
                      ->orWhere('status', 'awarded');
                })->where(function ($q) {
                    $q->where('has_letter_of_intent', false)
                      ->orWhere('has_award_letter', false)
                      ->orWhereNull('has_letter_of_intent')
                      ->orWhereNull('has_award_letter');
                });
                break;
            case 'tender_fees':
                $query->where('is_tender_fee_paid', true);
                break;
            case 'bid_security_paid':
            case 'bid_sec_paid':
                $query->where('bid_security_status', 'submitted');
                break;
            case 'bid_security_refunded':
            case 'bid_sec_refunded':
                $query->where('bid_security_status', 'released_returned');
                break;
            case 'perf_security_paid':
            case 'perf_sec_paid':
                $query->where('performance_security_status', 'paid')
                      ->where('performance_security_amount', '>', 0);
                break;
            case 'perf_security_released':
            case 'perf_sec_released':
                $query->where(function ($q) {
                    $q->where('tender_outcome', 'won')
                      ->orWhere('status', 'awarded');
                })->where('has_letter_of_intent', true)->where('has_award_letter', true);
                break;
            case 'total':
            case 'total_tenders':
            default:
                break;
        }
    }

    public function reports(Request $request)
    {
        $query = $this->buildReportsQuery($request);
        $allTenders = (clone $query)->get();

        // 12 Summary Cards for Tender Management
        // Row 1: Tender & Workflow Status (6 Cards)
        $totalCount = $allTenders->count();
        $cancelledCount = $allTenders->filter(fn($t) => $t->status === 'cancelled' || $t->tender_outcome === 'cancelled')->count();
        $pendingCount = $allTenders->filter(fn($t) => ($t->tender_outcome === 'in_progress' || $t->status === 'published' || $t->status === 'draft') && !$t->isWon() && !$t->isLost() && $t->status !== 'cancelled')->count();
        $wonTenders = $allTenders->filter(fn($t) => $t->isWon());
        $wonCount = $wonTenders->count();
        $lostCount = $allTenders->filter(fn($t) => $t->isLost())->count();
        $pendingDocsCount = $wonTenders->filter(fn($t) => !$t->isDocumentsCompleted())->count();

        // Row 2: Bidding & Security Financials (6 Cards)
        $totalWonValue = $wonTenders->sum(function ($t) {
            return $t->awarded_amount ?: ($t->budget ?: 0);
        });
        $totalTenderFeesPaid = $allTenders->where('is_tender_fee_paid', true)->sum('tender_fee_amount');
        $totalBidSecuritySubmitted = $allTenders->where('bid_security_status', 'submitted')->sum('bid_security_amount');
        $totalBidSecurityReleased = $allTenders->where('bid_security_status', 'released_returned')->sum('bid_security_amount');
        $totalPerfSecurityPaid = $allTenders->filter(fn($t) => $t->isPerformanceSecurityPaid())->sum('performance_security_amount');
        $totalPerfSecurityReleased = $wonTenders->filter(fn($t) => $t->isDocumentsCompleted())->sum(function ($t) {
            return $t->performance_security_amount ?: 0;
        });

        // Apply Card-click Filters to Paginated Table
        $cardFilter = $request->input('card_filter');
        $this->applyReportsCardFilter($query, $cardFilter);

        $tenders = $query->latest('submission_deadline')->paginate(25)->withQueryString();

        $departments = Department::where('status', 'active')->orderBy('name')->get();
        $companies = Company::where(function ($q) {
            $q->where('status', 'active')->orWhereNull('status');
        })->orderBy('company_name')->get();
        if (BankAccount::count() === 0) {
            (new \Database\Seeders\FinanceSeeder())->run();
        }
        $bankAccounts = BankAccount::where(function ($q) {
            $q->where('status', 'active')->orWhereNull('status');
        })->orderBy('bank_name')->get();

        return view('tenders.reports', compact(
            'tenders',
            'allTenders',
            'cardFilter',
            'totalCount',
            'cancelledCount',
            'pendingCount',
            'wonCount',
            'lostCount',
            'pendingDocsCount',
            'totalWonValue',
            'totalTenderFeesPaid',
            'totalBidSecuritySubmitted',
            'totalBidSecurityReleased',
            'totalPerfSecurityPaid',
            'totalPerfSecurityReleased',
            'wonTenders',
            'departments',
            'companies',
            'bankAccounts'
        ));
    }

    public function exportReports(Request $request)
    {
        $query = $this->buildReportsQuery($request);
        $this->applyReportsCardFilter($query, $request->input('card_filter'));
        $tenders = $query->latest('submission_deadline')->get();

        $headers = [
            'S. No.',
            'Vendor Name',
            'Department Name',
            'Tender Description',
            'Tender No.',
            'Bidding Procedure',
            'Work Order / Award Value (PKR)',
            'Tender Fee (PKR)',
            'Tender Fee Status',
            'Budget (PKR)',
            'Bid Security (CDR / PO) (PKR)',
            'Bid Security Status',
            'Performance Security (PKR)',
            'Performance Security Status',
            'Status',
            'Received Award Documents',
        ];

        $rows = [];
        foreach ($tenders as $idx => $t) {
            $statusLabel = 'Pending';
            if ($t->isWon()) {
                $statusLabel = 'Awarded';
            } elseif ($t->isLost()) {
                $statusLabel = 'Not Awarded';
            } elseif ($t->status === 'cancelled' || $t->tender_outcome === 'cancelled') {
                $statusLabel = 'Cancelled';
            }

            $awardDocsLabel = '-';
            if ($t->isWon()) {
                $awardDocsLabel = $t->isDocumentsCompleted()
                    ? 'Complete'
                    : 'Pending (' . $t->completedDocumentsCount() . '/2)';
            }

            $feeStatus = $t->is_tender_fee_paid ? 'Paid' : ($t->tender_fee_amount ? 'Unpaid' : 'No Fee');
            $bidSecStatus = $t->bid_security_amount
                ? ($t->bid_security_status === 'released_returned' ? 'Refunded / Released' : 'Active')
                : '-';
            $perfStatus = $t->isPerformanceSecurityPaid() ? 'Paid' : 'No Fees';

            $rows[] = [
                $idx + 1,
                $t->company ? ($t->company->company_name ?: $t->company->name) : '-',
                $t->department ? $t->department->name : '-',
                $t->title ?: ($t->description ?: '-'),
                $t->effective_tender_no,
                $t->procedure_label,
                $t->isWon() ? ('PKR ' . number_format($t->awarded_amount ?: ($t->budget ?: 0), 2)) : '-',
                $t->tender_fee_amount ? ('PKR ' . number_format($t->tender_fee_amount, 2)) : '-',
                $feeStatus,
                $t->budget ? ('PKR ' . number_format($t->budget, 2)) : '-',
                $t->bid_security_amount ? ('PKR ' . number_format($t->bid_security_amount, 2)) : '-',
                $bidSecStatus,
                $t->isPerformanceSecurityPaid() ? ('PKR ' . number_format($t->performance_security_amount, 2)) : 'No Fees',
                $perfStatus,
                $statusLabel,
                $awardDocsLabel,
            ];
        }

        $tmpFile = \App\Services\XlsxExporter::generate($headers, $rows, 'Tender Official Report');
        $filename = 'tender-official-report-' . now()->format('Y-m-d-His') . '.xlsx';

        return response()->download($tmpFile, $filename, [
            'Content-Type' => 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet',
        ])->deleteFileAfterSend(true);
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

        return view('tenders.create', compact(
            'departments',
            'companies'
        ));
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
            'interest_status' => 'nullable|string|in:interested,not_interested',
            'contact_person' => 'nullable|string|max:255',
            'initial_entry_only' => 'nullable|boolean',
            // Tender Fee
            'tender_fee_amount' => 'nullable|numeric|min:0',
            'is_tender_fee_paid' => 'nullable|boolean',
            'tender_fee_bank_account_id' => 'nullable|exists:bank_accounts,id',
            'tender_fee_payment_mode' => 'nullable|string|max:50',
            'tender_fee_ref_no' => 'nullable|string|max:255',
            'tender_fee_date' => 'nullable|date',
            'tender_fee_receipt' => 'nullable|file|mimes:pdf,doc,docx,zip,jpg,jpeg,png|max:10240',
            // Bid Security
            'bid_security_amount' => 'nullable|numeric|min:0',
            'bid_security_percentage' => 'nullable|numeric|min:0|max:100',
            'bid_security_type' => 'nullable|string|max:50',
            'bid_security_bank_account_id' => 'nullable|exists:bank_accounts,id',
            'bid_security_instrument_no' => 'nullable|string|max:255',
            'bid_security_date' => 'nullable|date',
            'bid_security_expiry_date' => 'nullable|date',
            'bid_security_status' => 'nullable|string|max:50',
            'bid_security_scan' => 'nullable|file|mimes:pdf,doc,docx,zip,jpg,jpeg,png|max:10240',
            // Dates
            'upload_date' => 'nullable|date',
            'collection_start_date' => 'nullable|date',
            'collection_start_notes' => 'nullable|string|max:255',
            'collection_end_date' => 'nullable|date',
            'collection_end_notes' => 'nullable|string|max:255',
            'submission_deadline' => 'nullable|date',
            'opening_date' => 'nullable|date',
            'technical_opening_date' => 'nullable|date',
            'financial_opening_date' => 'nullable|date',
            'is_draft' => 'nullable|boolean',
            'action' => 'nullable|string',
            'description' => 'nullable|string',
            'requirements' => 'nullable|string',
            'document' => 'nullable|file|mimes:pdf,doc,docx,zip|max:51200',
        ]);

        $tenderType = $validated['tender_type'] ?? 'tender';
        $closingDate = $validated['closing_date'] ?? null;

        $isDraft = $request->boolean('is_draft') || $request->input('is_draft') == '1' || $request->input('action') === 'draft';

        if ($tenderType === 'epad') {
            if (empty($closingDate)) {
                $closingDate = $validated['submission_deadline'] ?? null;
            }
            if (empty($closingDate)) {
                if ($isDraft) {
                    $closingDate = now()->addDays(30);
                } else {
                    return back()->withErrors(['closing_date' => 'Closing Date & Time is required for EPAD 2.0.'])->withInput();
                }
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
                if ($isDraft) {
                    $submissionDeadline = now()->addDays(30);
                } else {
                    return back()->withErrors(['submission_deadline' => 'Tender Submission Deadline is required.'])->withInput();
                }
            }
            $isTwoEnvelope = ($validated['bidding_procedure'] === 'single_stage_two_envelope');
            if ($isTwoEnvelope) {
                $technicalOpening = !empty($validated['technical_opening_date']) 
                    ? $validated['technical_opening_date'] 
                    : ($validated['opening_date'] ?? null);
                $generalOpening = $technicalOpening;
                $financialOpening = !empty($validated['financial_opening_date']) 
                    ? $validated['financial_opening_date'] 
                    : null;
            } else {
                $generalOpening = !empty($validated['opening_date']) 
                    ? $validated['opening_date'] 
                    : ($validated['technical_opening_date'] ?? null);
                $technicalOpening = $generalOpening;
                $financialOpening = $generalOpening;
            }
            $uploadDate = $validated['upload_date'] ?? $validated['collection_start_date'] ?? null;
            $collectionStartDate = $uploadDate ?: ($validated['collection_start_date'] ?? null);
            $collectionStartNotes = !empty($validated['collection_start_notes']) ? $validated['collection_start_notes'] : 'During working hours';
            $collectionEndDate = $uploadDate ?: ($validated['collection_end_date'] ?? null);
            $collectionEndNotes = !empty($validated['collection_end_notes']) ? $validated['collection_end_notes'] : 'During working hours';
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

        $isDraft = $request->boolean('is_draft') || $request->input('action') === 'draft';

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
            'upload_date' => $uploadDate,
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
            'status' => $isDraft ? 'draft' : 'published',
            'interest_status' => 'pending',
            'contact_person' => $validated['contact_person'] ?? null,
            'is_fully_processed' => false,
            'initial_entry_only' => true,
            'created_by' => Auth::id(),
        ]);

        ActivityLog::record(
            'create',
            'Tender',
            "Created tender '{$tender->tender_no}' with procedure '{$tender->procedure_label}'" . ($isDraft ? ' (Draft)' : ''),
            $tender,
            [
                'bidding_procedure' => $tender->bidding_procedure,
                'submission_deadline' => $tender->submission_deadline,
                'department_id' => $tender->department_id,
                'status' => $tender->status,
            ]
        );

        if (!$isDraft) {
            \App\Services\NotificationService::tenderCreated($tender);
        }

        if ($isDraft) {
            return redirect()->route('tenders.index')->with('success', "Tender '{$tenderNo}' saved as draft successfully.");
        }

        return redirect()->route('tenders.index')->with('success', "Tender '{$tenderNo}' created successfully! Please decide your bidding interest below.");
    }

    /**
     * Bidding Preparation Stage: Step 2 Form (Document Fee & Bid Security)
     */
    public function biddingPrep(Tender $tender)
    {
        if (!Auth::user()->isAdmin()) {
            abort(403, 'Unauthorized action.');
        }

        // Auto mark as interested if not already
        if ($tender->interest_status !== 'interested') {
            $tender->update(['interest_status' => 'interested']);
        }

        if (BankAccount::count() === 0) {
            (new \Database\Seeders\FinanceSeeder())->run();
        }
        $bankAccounts = BankAccount::where(function ($q) {
            $q->where('status', 'active')->orWhereNull('status');
        })->orderBy('bank_name')->get();

        return view('tenders.bidding_prep', compact('tender', 'bankAccounts'));
    }

    /**
     * Save Bidding Preparation (Step 2) & Move to Tender Management Workflow
     */
    public function saveBiddingPrep(Request $request, Tender $tender)
    {
        if (!Auth::user()->isAdmin()) {
            abort(403, 'Unauthorized action.');
        }

        $validated = $request->validate([
            'budget' => 'nullable|numeric|min:0',
            'tender_fee_amount' => 'nullable|numeric|min:0',
            'is_tender_fee_paid' => 'nullable|boolean',
            'tender_fee_bank_account_id' => 'nullable|exists:bank_accounts,id',
            'tender_fee_payment_mode' => 'nullable|string|max:50',
            'tender_fee_ref_no' => 'nullable|string|max:255',
            'tender_fee_date' => 'nullable|date',
            'tender_fee_receipt' => 'nullable|file|mimes:pdf,doc,docx,zip,jpg,jpeg,png|max:10240',
            // Bid Security
            'bid_security_amount' => 'nullable|numeric|min:0',
            'bid_security_percentage' => 'nullable|numeric|min:0|max:100',
            'bid_security_type' => 'nullable|string|max:50',
            'bid_security_bank_account_id' => 'nullable|exists:bank_accounts,id',
            'bid_security_instrument_no' => 'nullable|string|max:255',
            'bid_security_date' => 'nullable|date',
            'bid_security_expiry_date' => 'nullable|date',
            'bid_security_status' => 'nullable|string|max:50',
            'bid_security_scan' => 'nullable|file|mimes:pdf,doc,docx,zip,jpg,jpeg,png|max:10240',
        ]);

        $tenderFeeReceipt = $tender->tender_fee_receipt_file;
        if ($request->hasFile('tender_fee_receipt')) {
            $tenderFeeReceipt = $request->file('tender_fee_receipt')->store('tender_documents', 'public');
        }

        $bidSecurityFile = $tender->bid_security_file;
        if ($request->hasFile('bid_security_scan')) {
            $bidSecurityFile = $request->file('bid_security_scan')->store('tender_documents', 'public');
        }

        $isTenderFeePaid = $request->boolean('is_tender_fee_paid');

        $tenderData = [
            'budget' => $validated['budget'] ?? $tender->budget,
            'tender_fee_amount' => $validated['tender_fee_amount'] ?? null,
            'is_tender_fee_paid' => $isTenderFeePaid,
            'tender_fee_bank_account_id' => $validated['tender_fee_bank_account_id'] ?? null,
            'tender_fee_payment_mode' => $validated['tender_fee_payment_mode'] ?? ($isTenderFeePaid ? 'cash' : null),
            'tender_fee_ref_no' => $validated['tender_fee_ref_no'] ?? null,
            'tender_fee_date' => $validated['tender_fee_date'] ?? null,
            'tender_fee_receipt_file' => $tenderFeeReceipt,
            // Bid Security
            'bid_security_amount' => $validated['bid_security_amount'] ?? null,
            'bid_security_percentage' => $validated['bid_security_percentage'] ?? null,
            'bid_security_type' => $validated['bid_security_type'] ?? 'pay_order',
            'bid_security_bank_account_id' => $validated['bid_security_bank_account_id'] ?? null,
            'bid_security_instrument_no' => $validated['bid_security_instrument_no'] ?? null,
            'bid_security_date' => $validated['bid_security_date'] ?? null,
            'bid_security_expiry_date' => $validated['bid_security_expiry_date'] ?? null,
            'bid_security_status' => $validated['bid_security_status'] ?? 'submitted',
            'bid_security_file' => $bidSecurityFile,
            // Move into Tender Management
            'interest_status' => 'interested',
            'is_fully_processed' => true,
            'tender_outcome' => 'in_progress',
        ];

        if ($request->has('budget')) {
            $tenderData['budget'] = $validated['budget'] ?? null;
        }

        $tender->update($tenderData);

        if ((float) ($tenderData['bid_security_amount'] ?? 0) > 0) {
            $this->recordFinancialHistory(
                $tender,
                'bid_security_collection',
                (float) $tenderData['bid_security_amount'],
                $tenderData['bid_security_status'] === 'released_returned' ? 'Refunded / Released' : 'Active / Received',
                $bidSecurityFile,
                'Bid Security recorded during Bidding Preparation'
            );
        }

        ActivityLog::record(
            'update',
            'Tender',
            "Completed bidding preparation for tender '{$tender->effective_tender_no}'",
            $tender
        );

        return redirect()->route('tenders.reports')->with('success', "Bidding preparation completed! Tender '{$tender->effective_tender_no}' has entered Tender Management.");
    }

    /**
     * Update Interested / Not Interested Decision from Tender Details
     */
    public function updateInterestStatus(Request $request, Tender $tender)
    {
        if (!Auth::user()->isAdmin()) {
            abort(403, 'Unauthorized action.');
        }

        $status = $request->input('status');
        if (!in_array($status, ['interested', 'not_interested'])) {
            return back()->withErrors(['status' => 'Invalid interest status.']);
        }

        if ($status === 'not_interested') {
            $tender->update([
                'interest_status' => 'not_interested',
                'is_fully_processed' => false,
            ]);
            return redirect()->route('tenders.index')->with('success', "Tender '{$tender->effective_tender_no}' marked as Not Interested.");
        } else {
            $tender->update([
                'interest_status' => 'interested',
            ]);
            return redirect()->route('tenders.bidding-prep', $tender)->with('info', "Interest recorded! Please complete Document Fee & Bid Security to enter Tender Management.");
        }
    }

    public function show(Tender $tender)
    {
        $tender->load(['creator', 'department', 'company', 'tenderFeeBankAccount', 'bidSecurityBankAccount', 'financialHistories', 'bids.vendor', 'bids.evaluations.evaluator', 'bills.vendor']);

        $userBid = null;
        if (Auth::check() && Auth::user()->isVendor()) {
            $userBid = $tender->bids->where('user_id', Auth::id())->first();
        }

        if (BankAccount::count() === 0) {
            (new \Database\Seeders\FinanceSeeder())->run();
        }
        $bankAccounts = BankAccount::where(function ($q) {
            $q->where('status', 'active')->orWhereNull('status');
        })->orderBy('bank_name')->get();

        return view('tenders.show', compact('tender', 'userBid', 'bankAccounts'));
    }

    public function downloadDocument(Request $request, Tender $tender)
    {
        $tender->load(['creator', 'department', 'company', 'tenderFeeBankAccount', 'bidSecurityBankAccount']);

        $type = $request->get('type', 'document');

        if ($type === 'fee_receipt' && $tender->tender_fee_receipt_file && Storage::disk('public')->exists($tender->tender_fee_receipt_file)) {
            $extension = pathinfo($tender->tender_fee_receipt_file, PATHINFO_EXTENSION) ?: 'pdf';
            $fileName = Str::slug($tender->effective_tender_no ?: $tender->title) . '-fee-receipt.' . $extension;
            return Storage::disk('public')->download($tender->tender_fee_receipt_file, $fileName);
        }

        if ($type === 'bid_security' && $tender->bid_security_file && Storage::disk('public')->exists($tender->bid_security_file)) {
            $extension = pathinfo($tender->bid_security_file, PATHINFO_EXTENSION) ?: 'pdf';
            $fileName = Str::slug($tender->effective_tender_no ?: $tender->title) . '-bid-security-cdr.' . $extension;
            return Storage::disk('public')->download($tender->bid_security_file, $fileName);
        }

        if ($type === 'loi' && $tender->letter_of_intent_file && Storage::disk('public')->exists($tender->letter_of_intent_file)) {
            $extension = pathinfo($tender->letter_of_intent_file, PATHINFO_EXTENSION) ?: 'pdf';
            $fileName = Str::slug($tender->effective_tender_no ?: $tender->title) . '-loi.' . $extension;
            return Storage::disk('public')->download($tender->letter_of_intent_file, $fileName);
        }

        if ($type === 'award' && $tender->award_letter_file && Storage::disk('public')->exists($tender->award_letter_file)) {
            $extension = pathinfo($tender->award_letter_file, PATHINFO_EXTENSION) ?: 'pdf';
            $fileName = Str::slug($tender->effective_tender_no ?: $tender->title) . '-award-letter.' . $extension;
            return Storage::disk('public')->download($tender->award_letter_file, $fileName);
        }

        if ($type === 'performance_security' && $tender->performance_security_file && Storage::disk('public')->exists($tender->performance_security_file)) {
            $extension = pathinfo($tender->performance_security_file, PATHINFO_EXTENSION) ?: 'pdf';
            $fileName = Str::slug($tender->effective_tender_no ?: $tender->title) . '-performance-security.' . $extension;
            return Storage::disk('public')->download($tender->performance_security_file, $fileName);
        }

        if ($tender->document_path && Storage::disk('public')->exists($tender->document_path)) {
            $extension = pathinfo($tender->document_path, PATHINFO_EXTENSION) ?: 'pdf';
            $fileName = Str::slug($tender->effective_tender_no ?: $tender->title) . '-rfp.' . $extension;
            return Storage::disk('public')->download($tender->document_path, $fileName);
        }

        // Download official tender RFP dossier as HTML file when no physical attachment is uploaded
        $dossierFilename = Str::slug($tender->effective_tender_no ?: $tender->title) . '-tender-dossier.html';
        return response()->view('tenders.dossier', compact('tender'), 200, [
            'Content-Type' => 'text/html; charset=UTF-8',
            'Content-Disposition' => 'attachment; filename="' . $dossierFilename . '"',
        ]);
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

        if (BankAccount::count() === 0) {
            (new \Database\Seeders\FinanceSeeder())->run();
        }
        $bankAccounts = BankAccount::where(function ($q) {
            $q->where('status', 'active')->orWhereNull('status');
        })->orderBy('bank_name')->get();

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
            'interest_status' => 'nullable|string|in:interested,not_interested',
            'contact_person' => 'nullable|string|max:255',
            'initial_entry_only' => 'nullable|boolean',
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
            'upload_date' => 'nullable|date',
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
            'document' => 'nullable|file|mimes:pdf,doc,docx,zip|max:51200',
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

        if (!empty($validated['upload_date'])) {
            $validated['collection_start_date'] = $validated['upload_date'];
            $validated['collection_end_date'] = $validated['upload_date'];
        } elseif (!empty($validated['collection_start_date']) && empty($validated['upload_date'])) {
            $validated['upload_date'] = $validated['collection_start_date'];
        }

        if (($validated['bidding_procedure'] ?? '') === 'single_stage_two_envelope') {
            $validated['opening_date'] = $validated['technical_opening_date'] ?? $validated['opening_date'] ?? null;
        } elseif (($validated['bidding_procedure'] ?? '') === 'single_stage_one_envelope') {
            $generalDate = $validated['opening_date'] ?? $validated['technical_opening_date'] ?? null;
            $validated['opening_date'] = $generalDate;
            $validated['technical_opening_date'] = $generalDate;
            $validated['financial_opening_date'] = $generalDate;
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
            'is_tender_fee_paid' => 'nullable|boolean',
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

        $refundAmount = (float) ($tender->bid_security_amount ?? 0);
        $tender->update([
            'bid_security_status' => 'released_returned',
            'bid_security_released_at' => now(),
            'bid_security_refund_amount' => $refundAmount,
        ]);

        $this->recordFinancialHistory(
            $tender,
            'bid_security_refund',
            $refundAmount,
            'Refunded / Released',
            $tender->bid_security_file,
            'Bid Security refunded / released'
        );

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
                $request->validate([
                    'performance_certificate_no' => 'nullable|string|max:255',
                    'performance_security_amount' => 'nullable|numeric|min:0',
                    'performance_security_file' => 'nullable|file|mimes:pdf,doc,docx,zip,jpg,jpeg,png|max:10240',
                    'refund_bid_security' => 'nullable|boolean',
                ]);

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

                $this->applyPerformanceSecurityAndRefund($request, $tender, $updateData);

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
                $request->validate([
                    'performance_certificate_no' => 'nullable|string|max:255',
                    'performance_security_amount' => 'nullable|numeric|min:0',
                    'performance_security_file' => 'nullable|file|mimes:pdf,doc,docx,zip,jpg,jpeg,png|max:10240',
                    'refund_bid_security' => 'nullable|boolean',
                ]);

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

                $this->applyPerformanceSecurityAndRefund($request, $tender, $updateData);

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

    /**
     * Update Performance Security & Optional Bid Security Refund
     */
    public function updatePerformanceSecurity(Request $request, Tender $tender)
    {
        if (!Auth::user()->isAdmin()) {
            abort(403, 'Unauthorized action.');
        }

        $request->validate([
            'performance_certificate_no' => 'nullable|string|max:255',
            'performance_security_amount' => 'nullable|numeric|min:0',
            'performance_security_file' => 'nullable|file|mimes:pdf,doc,docx,zip,jpg,jpeg,png|max:10240',
            'refund_bid_security' => 'nullable|boolean',
        ]);

        $updateData = [];
        $this->applyPerformanceSecurityAndRefund($request, $tender, $updateData);

        if (!empty($updateData)) {
            $tender->update($updateData);
        }

        ActivityLog::record(
            'update_performance_security',
            'Tender',
            "Updated Performance Security for tender '{$tender->effective_tender_no}'",
            $tender
        );

        return redirect()->route('tenders.show', $tender)->with('success', 'Performance Security & Bid Security status updated successfully!');
    }

    private function applyPerformanceSecurityAndRefund(Request $request, Tender $tender, array &$updateData): void
    {
        $perfFile = $tender->performance_security_file;
        if ($request->hasFile('performance_security_file')) {
            $perfFile = $request->file('performance_security_file')->store('tender_documents', 'public');
            $updateData['performance_security_file'] = $perfFile;
        }

        if ($request->filled('performance_certificate_no')) {
            $updateData['performance_certificate_no'] = $request->performance_certificate_no;
        }

        $perfAmount = $request->filled('performance_security_amount')
            ? (float) $request->performance_security_amount
            : null;

        if ($perfAmount !== null && $perfAmount > 0) {
            $updateData['performance_security_amount'] = $perfAmount;
            $updateData['performance_security_status'] = 'paid';
            $updateData['performance_security_date'] = $tender->performance_security_date ?: now()->toDateString();

            $this->recordFinancialHistory(
                $tender,
                'performance_security_collection',
                $perfAmount,
                'Paid',
                $perfFile,
                $request->performance_certificate_no ? ('Certificate: ' . $request->performance_certificate_no) : 'Performance Security collected'
            );
        } elseif ($request->has('performance_security_amount') && ($perfAmount === null || $perfAmount <= 0)) {
            $updateData['performance_security_amount'] = null;
            $updateData['performance_security_status'] = 'no_fees';
        }

        if ($request->boolean('refund_bid_security') && $tender->bid_security_status !== 'released_returned') {
            $refundAmount = (float) ($tender->bid_security_amount ?? 0);
            $updateData['bid_security_status'] = 'released_returned';
            $updateData['bid_security_released_at'] = now();
            $updateData['bid_security_refund_amount'] = $refundAmount;

            $this->recordFinancialHistory(
                $tender,
                'bid_security_refund',
                $refundAmount,
                'Refunded / Released',
                $tender->bid_security_file,
                'Bid Security refunded during Performance Security update'
            );
        }
    }

    private function recordFinancialHistory(
        Tender $tender,
        string $type,
        float $amount,
        string $status,
        ?string $docPath = null,
        ?string $notes = null
    ): void {
        \App\Models\TenderFinancialHistory::create([
            'tender_id' => $tender->id,
            'tender_reference' => $tender->effective_tender_no,
            'vendor_name' => $tender->company ? ($tender->company->company_name ?: $tender->company->name) : 'Al Madina Enterprises',
            'amount' => $amount,
            'action_date' => now(),
            'transaction_type' => $type,
            'current_status' => $status,
            'document_path' => $docPath,
            'notes' => $notes,
            'created_by' => Auth::id(),
        ]);
    }

    /**
     * Update Scope of Work & Work Order Descriptions
     */
    public function updateDescriptions(Request $request, Tender $tender)
    {
        if (!Auth::user()->isAdmin()) {
            abort(403, 'Unauthorized action.');
        }

        $request->validate([
            'description' => 'nullable|string',
            'stage_1_notes' => 'nullable|string',
            'requirements' => 'nullable|string',
        ]);

        $updateData = [];
        if ($request->has('description')) {
            $updateData['description'] = $request->description;
        }
        if ($request->has('stage_1_notes')) {
            $updateData['stage_1_notes'] = $request->stage_1_notes;
        }
        if ($request->has('requirements')) {
            $updateData['requirements'] = $request->requirements;
        }

        if (!empty($updateData)) {
            $tender->update($updateData);
        }

        ActivityLog::record(
            'update_descriptions',
            'Tender',
            "Updated Scope of Work & Work Order descriptions for '{$tender->tender_no}'",
            $tender
        );

        return back()->with('success', 'Scope of Work & Work Order descriptions saved successfully! ✅');
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

        return redirect()->back()->with('success', 'Tender deleted successfully.');
    }
}
