<?php

namespace App\Http\Controllers;

use App\Models\Department;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class DepartmentController extends Controller
{
    public function index()
    {
        $departments = Department::withCount('tenders')
            ->with(['bills' => function ($q) {
                $q->where('status', 'paid');
            }])
            ->get();

        return view('departments.index', compact('departments'));
    }

    public function create()
    {
        if (!Auth::user()->isAdmin()) {
            abort(403, 'Unauthorized. Only admins can create departments.');
        }

        return view('departments.create');
    }

    public function store(Request $request)
    {
        if (!Auth::user()->isAdmin()) {
            abort(403, 'Unauthorized.');
        }

        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'code' => 'required|string|max:20|unique:departments,code',
            'cost_center_description' => 'nullable|string|max:255',
            'city' => 'nullable|string|max:100',
            'head_of_department' => 'nullable|string|max:255',
            'budget_allocated' => 'required|numeric|min:0',
            'description' => 'nullable|string',
            'status' => 'required|in:active,inactive',
        ]);

        $dept = Department::create($validated);

        \App\Models\ActivityLog::record(
            action: 'created',
            module: 'Departments',
            description: "Created department [{$dept->name}] ({$dept->code}) with budget PKR " . number_format($dept->budget_allocated) . ".",
            subject: $dept
        );

        return redirect()->route('departments.index')->with('success', 'Department created successfully.');
    }

    public function show(Department $department)
    {
        $department->load(['releases.creator', 'bills.vendor', 'budgetHeads']);
        $totalAllocated = (float) $department->budget_allocated;
        $totalReleased = $department->totalReleasedBudget();
        $totalSpent = $department->totalSpent();
        $availableReleased = $department->availableReleasedBudget();
        $unreleased = $department->unreleasedBudget();
        $budgetHeads = $department->budgetHeads()->orderBy('head_code')->get();

        return view('departments.show', compact('department', 'totalAllocated', 'totalReleased', 'totalSpent', 'availableReleased', 'unreleased', 'budgetHeads'));
    }

    public function edit(Department $department)
    {
        if (!Auth::user()->isAdmin()) {
            abort(403, 'Unauthorized.');
        }

        return view('departments.edit', compact('department'));
    }

    public function update(Request $request, Department $department)
    {
        if (!Auth::user()->isAdmin()) {
            abort(403, 'Unauthorized.');
        }

        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'code' => 'required|string|max:20|unique:departments,code,' . $department->id,
            'cost_center_description' => 'nullable|string|max:255',
            'city' => 'nullable|string|max:100',
            'head_of_department' => 'nullable|string|max:255',
            'budget_allocated' => 'required|numeric|min:0',
            'description' => 'nullable|string',
            'status' => 'required|in:active,inactive',
        ]);

        $department->update($validated);

        \App\Models\ActivityLog::record(
            action: 'updated',
            module: 'Departments',
            description: "Updated department [{$department->name}] ({$department->code}).",
            subject: $department
        );

        return redirect()->route('departments.show', $department)->with('success', 'Department updated successfully.');
    }

    public function destroy(Department $department)
    {
        if (!Auth::user()->isAdmin() && !Auth::user()->hasPermission('departments.manage')) {
            abort(403, 'Unauthorized.');
        }

        $name = $department->name;
        $code = $department->code;

        $department->delete();

        \App\Models\ActivityLog::record(
            action: 'deleted',
            module: 'Departments',
            description: "Deleted department [{$name}] ({$code}).",
            properties: ['name' => $name, 'code' => $code]
        );

        return redirect()->route('departments.index')->with('success', 'Department deleted successfully.');
    }
}
