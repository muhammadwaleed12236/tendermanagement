<?php

namespace App\Http\Controllers;

use App\Models\BudgetRelease;
use App\Models\Department;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class BudgetReleaseController extends Controller
{
    /**
     * Display a comprehensive department-wise Budget & Release summary sheet,
     * matching the financial accounting view (Year, Department, Original Budget,
     * Expenditure, Released Budget, Released Bud. Balance).
     */
    public function index(Request $request)
    {
        $selectedYear = $request->query('year', '2026');
        $departmentId = $request->query('department_id');

        $departmentsQuery = Department::with(['releases', 'bills' => function ($q) {
            $q->where('status', 'paid');
        }, 'budgetHeads']);

        if ($departmentId) {
            $departmentsQuery->where('id', $departmentId);
        }

        $allDepartments = $departmentsQuery->get();

        // If a specific department is selected, load its detailed object heads
        $selectedDepartment = $departmentId ? Department::with('budgetHeads')->find($departmentId) : null;
        $detailObjectHeads = $selectedDepartment 
            ? $selectedDepartment->budgetHeads()->orderBy('head_code')->get()
            : collect();

        // Build department-wise financial rows
        $summaryRows = $allDepartments->map(function ($dept) use ($selectedYear) {
            $originalBudget = (float) $dept->budget_allocated;
            $expenditure = $dept->totalSpent();
            $releasedBudget = $dept->totalReleasedBudget();
            $releasedBalance = $dept->availableReleasedBudget();
            $unreleased = $dept->unreleasedBudget();

            return [
                'department' => $dept,
                'year' => $selectedYear,
                'code' => $dept->code,
                'name' => $dept->name,
                'cost_center' => $dept->cost_center_description ?: $dept->name,
                'original_budget' => $originalBudget,
                'expenditure' => $expenditure,
                'released_budget' => $releasedBudget,
                'released_balance' => $releasedBalance,
                'unreleased' => $unreleased,
                'release_count' => $dept->releases->count(),
            ];
        });

        // Totals across all departments
        $totalOriginalBudget = $summaryRows->sum('original_budget');
        $totalExpenditure = $summaryRows->sum('expenditure');
        $totalReleasedBudget = $summaryRows->sum('released_budget');
        $totalReleasedBalance = $summaryRows->sum('released_balance');
        $totalUnreleased = $summaryRows->sum('unreleased');

        // All individual releases for the detailed transaction history table
        $releasesQuery = BudgetRelease::with(['department', 'creator'])->latest('release_date');
        if ($departmentId) {
            $releasesQuery->where('department_id', $departmentId);
        }
        $releases = $releasesQuery->paginate(15);

        // List of departments for dropdowns
        $dropdownDepartments = Department::orderBy('name')->get();

        return view('releases.index', compact(
            'summaryRows',
            'totalOriginalBudget',
            'totalExpenditure',
            'totalReleasedBudget',
            'totalReleasedBalance',
            'totalUnreleased',
            'releases',
            'dropdownDepartments',
            'selectedYear',
            'departmentId',
            'selectedDepartment',
            'detailObjectHeads'
        ));
    }

    /**
     * Store a new budget release (from either /budget-releases or /departments/{department}/releases)
     */
    public function store(Request $request, ?Department $department = null)
    {
        if (!Auth::user()->isAdmin()) {
            abort(403, 'Unauthorized. Only admin can release budget funds.');
        }

        $validated = $request->validate([
            'department_id' => $department ? 'nullable|exists:departments,id' : 'required|exists:departments,id',
            'quarter' => 'required|string|max:100',
            'financial_year' => 'required|string|max:20',
            'amount' => 'nullable|numeric|min:0',
            'release_date' => 'required|date',
            'reference_no' => 'nullable|string|max:100',
            'remarks' => 'nullable|string|max:500',
            'heads' => 'nullable|array',
            'heads.*.amount' => 'nullable|numeric|min:0',
        ]);

        $targetDept = $department ?? Department::findOrFail($validated['department_id']);

        $headAllocations = [];
        $totalHeadAmount = 0;

        if (!empty($validated['heads'])) {
            foreach ($validated['heads'] as $headId => $headData) {
                $headAmount = (float) ($headData['amount'] ?? 0);
                if ($headAmount > 0) {
                    $head = \App\Models\DepartmentBudgetHead::where('department_id', $targetDept->id)->find($headId);
                    if ($head) {
                        $headAllocations[] = [
                            'head_id' => $head->id,
                            'head_code' => $head->head_code,
                            'head_name' => $head->head_name,
                            'amount' => $headAmount,
                            'previous_released' => (float) $head->released_budget,
                            'new_released' => (float) $head->released_budget + $headAmount,
                        ];
                        // Increment head's released_budget
                        $head->released_budget = (float) $head->released_budget + $headAmount;
                        $head->save();

                        $totalHeadAmount += $headAmount;
                    }
                }
            }
        }

        // Final total release amount: either from head allocations or direct amount input
        $finalAmount = $totalHeadAmount > 0 ? $totalHeadAmount : (float) ($validated['amount'] ?? 0);

        if ($finalAmount <= 0) {
            return back()->with('error', 'Please enter a valid release amount or allocate funds to at least one budget head.');
        }

        // Check against unreleased ceiling
        $unreleased = $targetDept->unreleasedBudget();
        if ($finalAmount > $unreleased) {
            return back()->with('error', "Total release amount (PKR " . number_format($finalAmount, 2) . ") exceeds unreleased budget ceiling of PKR " . number_format($unreleased, 2) . " for " . $targetDept->name);
        }

        $release = BudgetRelease::create([
            'department_id' => $targetDept->id,
            'quarter' => $validated['quarter'],
            'financial_year' => $validated['financial_year'],
            'amount' => $finalAmount,
            'head_allocations' => count($headAllocations) > 0 ? $headAllocations : null,
            'release_date' => $validated['release_date'],
            'reference_no' => $validated['reference_no'] ?? ('REL-' . date('Y') . '-' . strtoupper(substr(md5(uniqid()), 0, 5))),
            'remarks' => $validated['remarks'] ?? null,
            'created_by' => Auth::id(),
        ]);

        \App\Models\ActivityLog::record(
            action: 'created',
            module: 'Departments',
            description: "Released budget of PKR " . number_format($finalAmount, 2) . " to [{$targetDept->name}] ({$validated['quarter']} - FY {$validated['financial_year']}). Ref: {$release->reference_no}.",
            subject: $release,
            properties: ['department' => $targetDept->name, 'amount' => $finalAmount, 'quarter' => $validated['quarter']]
        );

        \App\Services\NotificationService::budgetReleased($targetDept, $release);

        $msg = 'Quarterly budget of PKR ' . number_format($finalAmount, 2) . ' released to ' . $targetDept->name . ' (' . $targetDept->code . ') successfully.';
        if (count($headAllocations) > 0) {
            $msg .= ' Allocated across ' . count($headAllocations) . ' Detail Object Heads.';
        }

        return back()->with('success', $msg);
    }

    /**
     * Remove a budget release
     */
    public function destroy(BudgetRelease $release)
    {
        if (!Auth::user()->isAdmin()) {
            abort(403, 'Unauthorized.');
        }

        $deptName = $release->department ? $release->department->name : 'Department';

        // Rollback head allocations if any
        if (!empty($release->head_allocations) && is_array($release->head_allocations)) {
            foreach ($release->head_allocations as $alloc) {
                if (isset($alloc['head_id'], $alloc['amount'])) {
                    $head = \App\Models\DepartmentBudgetHead::find($alloc['head_id']);
                    if ($head) {
                        $head->released_budget = max(0, (float) $head->released_budget - (float) $alloc['amount']);
                        $head->save();
                    }
                }
            }
        }

        $amt = (float) $release->amount;
        $ref = $release->reference_no;

        $release->delete();

        \App\Models\ActivityLog::record(
            action: 'deleted',
            module: 'Departments',
            description: "Deleted budget release of PKR " . number_format($amt, 2) . " (Ref: {$ref}) for [{$deptName}].",
            properties: ['department' => $deptName, 'amount' => $amt, 'reference' => $ref]
        );

        return back()->with('success', "Budget release of PKR " . number_format($amt, 2) . " for {$deptName} deleted successfully.");
    }
}
