<?php

namespace App\Http\Controllers;

use App\Models\Bid;
use App\Models\Bill;
use App\Models\Cheque;
use App\Models\Department;
use App\Models\Evaluation;
use App\Models\Tender;
use App\Models\Todo;
use App\Models\ActivityLog;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class DashboardController extends Controller
{
    public function index()
    {
        $user = Auth::user();

        $totalBudget = (float) Department::sum('budget_allocated');
        $totalReleased = (float) \App\Models\BudgetRelease::sum('amount');
        $totalBilled = (float) Bill::sum('net_amount');
        $totalPaid = (float) Bill::where('status', 'paid')->sum('net_amount');
        $pendingBillsAmount = (float) Bill::whereIn('status', ['pending', 'verified_by_dept'])->sum('net_amount');
        $availableBalance = max(0, $totalReleased - $totalBilled);
        $complianceRate = $totalReleased > 0 ? round(($availableBalance / $totalReleased) * 100, 1) : 90.0;

        // Cheques, Commission & Vendor Profit
        $totalCheques = (float) Cheque::sum('cheque_amount');
        $totalCommission = (float) Cheque::sum('xyz_commission');
        if ($totalCommission <= 0) {
            $totalCommission = (float) Cheque::sum('our_commission') + (float) Cheque::sum('other_commission');
        }
        $totalVendorProfit = (float) Cheque::sum('vendor_profit');

        // Dynamic Percentages for Donut Indicators
        $billedPct = $totalBudget > 0 ? min(100, max(5, round(($totalBilled / $totalBudget) * 100))) : 68;
        $chequePct = $totalBilled > 0 ? min(100, max(5, round(($totalCheques / $totalBilled) * 100))) : 82;
        $commissionPct = $totalCheques > 0 ? min(100, max(1, round(($totalCommission / $totalCheques) * 100))) : 35;
        $profitPct = $totalCheques > 0 ? min(100, max(1, round(($totalVendorProfit / $totalCheques) * 100))) : 74;

        // Analytics Donut Flow
        $analyticsFlow = $totalBilled + $totalCheques;
        $salesPct = $analyticsFlow > 0 ? round(($totalBilled / $analyticsFlow) * 100) : 68;
        $purchasesPct = 100 - $salesPct;

        // System-wide Global Financial & Operational Stats
        $stats = [
            'total_departments' => Department::count(),
            'total_budget' => $totalBudget,
            'total_released' => $totalReleased,
            'total_cheques' => $totalCheques,
            'total_commission' => $totalCommission,
            'total_vendor_profit' => $totalVendorProfit,
            'total_billed' => $totalBilled,
            'total_paid' => $totalPaid,
            'total_pending_amount' => $pendingBillsAmount,
            'available_balance' => $availableBalance,
            'compliance_rate' => $complianceRate,
            'billed_pct' => $billedPct,
            'cheque_pct' => $chequePct,
            'commission_pct' => $commissionPct,
            'profit_pct' => $profitPct,
            'sales_pct' => $salesPct,
            'purchases_pct' => $purchasesPct,
            'total_tenders' => Tender::count(),
            'active_tenders' => Tender::where('status', 'published')
                ->where('submission_deadline', '>=', now())
                ->count(),
            'awarded_tenders' => Tender::where('status', 'awarded')->count(),
            'total_bids' => Bid::count(),
            'pending_bills_count' => Bill::whereIn('status', ['pending', 'verified_by_dept'])->count(),
        ];

        // Department Breakdown with spent and tenders count
        $departments = Department::withCount('tenders')
            ->with(['bills' => function ($q) {
                $q->where('status', 'paid');
            }])
            ->get();

        // Recent items
        $recentTenders = Tender::with(['creator', 'department'])->latest()->take(5)->get();
        $recentBills = Bill::with(['tender', 'vendor', 'department', 'items'])->latest()->take(6)->get();
        $recentActivities = ActivityLog::with('user')->latest()->take(8)->get();

        // Upcoming To-Do & Procurement Tasks
        $upcomingTodos = Todo::pending()
            ->with(['tender', 'bill', 'company'])
            ->orderByRaw("CASE 
                WHEN priority = 'urgent' THEN 1 
                WHEN priority = 'high' THEN 2 
                WHEN priority = 'medium' THEN 3 
                ELSE 4 END")
            ->orderBy('due_date', 'asc')
            ->take(5)
            ->get();

        $todosStats = [
            'pending' => Todo::pending()->count(),
            'overdue' => Todo::overdue()->count(),
            'due_today' => Todo::dueToday()->count(),
        ];

        // Top Products from Bill Items
        $topProducts = \App\Models\BillItem::selectRaw('item_name, sum(quantity) as total_qty, sum(total_price) as total_amount')
            ->groupBy('item_name')
            ->orderByDesc('total_qty')
            ->take(3)
            ->get();

        if ($user->isAdmin()) {
            $recentBids = Bid::with(['tender', 'vendor'])->latest()->take(5)->get();
            return view('dashboard', compact('stats', 'departments', 'recentTenders', 'recentBids', 'recentBills', 'recentActivities', 'upcomingTodos', 'todosStats', 'topProducts'));
        } elseif ($user->isEvaluator()) {
            $evaluationTenders = Tender::where('status', 'under_evaluation')
                ->with(['bids.vendor', 'bids.evaluations', 'department'])
                ->latest()
                ->take(10)
                ->get();
            return view('dashboard', compact('stats', 'departments', 'evaluationTenders', 'recentBills', 'recentActivities', 'upcomingTodos', 'todosStats', 'topProducts'));
        } else {
            // Vendor
            $myBids = Bid::where('user_id', $user->id)->with('tender.department')->latest()->get();
            $myBills = Bill::where('vendor_id', $user->id)->with(['tender', 'department'])->latest()->get();
            $openTenders = Tender::where('status', 'published')
                ->where('submission_deadline', '>=', now())
                ->with('department')
                ->latest()
                ->take(6)
                ->get();
            return view('dashboard', compact('stats', 'departments', 'myBids', 'myBills', 'openTenders', 'recentActivities', 'upcomingTodos', 'todosStats', 'topProducts'));
        }
    }
}
