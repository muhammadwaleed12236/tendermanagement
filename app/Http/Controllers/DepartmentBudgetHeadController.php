<?php

namespace App\Http\Controllers;

use App\Models\Department;
use App\Models\DepartmentBudgetHead;
use Illuminate\Http\Request;

class DepartmentBudgetHeadController extends Controller
{
    /**
     * Get all budget heads for a department (JSON for modal / async)
     */
    public function index(Department $department, Request $request)
    {
        $year = $request->get('year', '2026');
        $heads = $department->budgetHeads()
            ->where(function ($q) use ($year) {
                if ($year) {
                    $q->where('year', $year);
                }
            })
            ->orderBy('head_code')
            ->get();

        $totalOriginal = (float) $heads->sum('original_budget');
        $totalReleased = (float) $heads->sum('released_budget');
        $totalExpenditure = (float) $heads->sum('expenditure');
        $totalBalance = max(0, $totalReleased - $totalExpenditure);

        return response()->json([
            'department' => [
                'id' => $department->id,
                'name' => $department->name,
                'code' => $department->code,
                'budget_allocated' => (float) $department->budget_allocated,
            ],
            'heads' => $heads,
            'totals' => [
                'original_budget' => $totalOriginal,
                'released_budget' => $totalReleased,
                'expenditure' => $totalExpenditure,
                'released_balance' => $totalBalance,
                'unallocated' => max(0, (float) $department->budget_allocated - $totalOriginal),
            ],
        ]);
    }

    /**
     * Get single budget head (JSON for edit modal)
     */
    public function show(DepartmentBudgetHead $budgetHead)
    {
        return response()->json([
            'success' => true,
            'head' => $budgetHead,
        ]);
    }

    /**
     * Store a new budget head for a department
     */
    public function store(Request $request, Department $department)
    {
        $validated = $request->validate([
            'year' => 'nullable|string|max:20',
            'head_code' => 'nullable|string|max:50',
            'head_name' => 'required|string|max:255',
            'original_budget' => 'nullable|numeric|min:0',
            'released_budget' => 'nullable|numeric|min:0',
            'expenditure' => 'nullable|numeric|min:0',
            'notes' => 'nullable|string',
        ]);

        $year = $validated['year'] ?: '2026';
        $headName = trim($validated['head_name']);
        $headCode = trim($validated['head_code'] ?? '');

        // If head_code is empty, try to extract from head_name (e.g. "A03302-WATER" -> "A03302")
        if (empty($headCode) && preg_match('/^([A-Z0-9]+)\s*[-]/i', $headName, $matches)) {
            $headCode = $matches[1];
        }

        $head = DepartmentBudgetHead::create([
            'department_id' => $department->id,
            'year' => $year,
            'head_code' => $headCode,
            'head_name' => $headName,
            'original_budget' => isset($validated['original_budget']) ? (float)$validated['original_budget'] : 0,
            'released_budget' => isset($validated['released_budget']) ? (float)$validated['released_budget'] : 0,
            'expenditure' => isset($validated['expenditure']) ? (float)$validated['expenditure'] : 0,
            'notes' => $validated['notes'] ?? null,
        ]);

        if ($request->wantsJson() || $request->ajax()) {
            return response()->json([
                'success' => true,
                'message' => 'Budget head added successfully!',
                'head' => $head,
            ]);
        }

        return back()->with('success', "Budget head '{$head->head_name}' added successfully!");
    }

    /**
     * Update an existing budget head
     */
    public function update(Request $request, DepartmentBudgetHead $budgetHead)
    {
        $validated = $request->validate([
            'head_name' => 'required|string|max:255',
            'head_code' => 'nullable|string|max:50',
            'original_budget' => 'nullable|numeric|min:0',
            'released_budget' => 'nullable|numeric|min:0',
            'expenditure' => 'nullable|numeric|min:0',
            'notes' => 'nullable|string',
        ]);

        $budgetHead->update([
            'head_name' => trim($validated['head_name']),
            'head_code' => isset($validated['head_code']) ? trim($validated['head_code']) : $budgetHead->head_code,
            'original_budget' => isset($validated['original_budget']) ? (float)$validated['original_budget'] : $budgetHead->original_budget,
            'released_budget' => isset($validated['released_budget']) ? (float)$validated['released_budget'] : $budgetHead->released_budget,
            'expenditure' => isset($validated['expenditure']) ? (float)$validated['expenditure'] : $budgetHead->expenditure,
            'notes' => array_key_exists('notes', $validated) ? $validated['notes'] : $budgetHead->notes,
        ]);

        if ($request->wantsJson() || $request->ajax()) {
            return response()->json([
                'success' => true,
                'message' => 'Budget head updated successfully!',
                'head' => $budgetHead,
            ]);
        }

        return back()->with('success', "Budget head updated successfully!");
    }

    /**
     * Delete a budget head
     */
    public function destroy(Request $request, DepartmentBudgetHead $budgetHead)
    {
        $name = $budgetHead->head_name;
        $budgetHead->delete();

        if ($request->wantsJson() || $request->ajax()) {
            return response()->json([
                'success' => true,
                'message' => "Budget head '{$name}' deleted successfully!",
            ]);
        }

        return back()->with('success', "Budget head '{$name}' removed!");
    }
}
