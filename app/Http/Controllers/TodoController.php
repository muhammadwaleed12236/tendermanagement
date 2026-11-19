<?php

namespace App\Http\Controllers;

use App\Models\Bill;
use App\Models\Company;
use App\Models\Tender;
use App\Models\Todo;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class TodoController extends Controller
{
    /**
     * Display a listing of todos with filtering and counts.
     */
    public function index(Request $request)
    {
        $filter = $request->query('filter', 'pending'); // 'pending', 'all', 'completed', 'overdue', 'due_today'
        $category = $request->query('category');
        $priority = $request->query('priority');
        $search = $request->query('search');

        $query = Todo::with(['tender', 'bill', 'company', 'creator']);

        // Tab filters
        if ($filter === 'pending') {
            $query->pending();
        } elseif ($filter === 'completed') {
            $query->completed();
        } elseif ($filter === 'overdue') {
            $query->overdue();
        } elseif ($filter === 'due_today') {
            $query->dueToday();
        }

        // Additional filters
        if ($category && $category !== 'all') {
            $query->where('category', $category);
        }

        if ($priority && $priority !== 'all') {
            $query->where('priority', $priority);
        }

        if ($search) {
            $query->where(function ($q) use ($search) {
                $q->where('title', 'like', "%{$search}%")
                  ->orWhere('description', 'like', "%{$search}%");
            });
        }

        // Sorting: pending first, then by priority (urgent > high > medium > low), then due date
        $todos = $query->orderBy('is_completed', 'asc')
            ->orderByRaw("CASE 
                WHEN priority = 'urgent' THEN 1 
                WHEN priority = 'high' THEN 2 
                WHEN priority = 'medium' THEN 3 
                ELSE 4 END")
            ->orderBy('due_date', 'asc')
            ->orderBy('created_at', 'desc')
            ->paginate(15)
            ->withQueryString();

        // Summary counts
        $counts = [
            'all' => Todo::count(),
            'pending' => Todo::pending()->count(),
            'completed' => Todo::completed()->count(),
            'overdue' => Todo::overdue()->count(),
            'due_today' => Todo::dueToday()->count(),
        ];

        // Dropdown references for quick linking
        $tenders = Tender::latest()->take(30)->get(['id', 'title', 'reference_no']);
        $bills = Bill::latest()->take(30)->get(['id', 'bill_number', 'milestone_description']);
        $companies = Company::orderBy('company_name')->get(['id', 'company_name']);

        return view('todos.index', compact('todos', 'counts', 'filter', 'category', 'priority', 'search', 'tenders', 'bills', 'companies'));
    }

    /**
     * Store a new todo.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'nullable|string|max:1000',
            'priority' => 'required|in:low,medium,high,urgent',
            'category' => 'required|in:tender,quotation,bill_invoice,cheque_agpr,general',
            'due_date' => 'nullable|date',
            'tender_id' => 'nullable|exists:tenders,id',
            'bill_id' => 'nullable|exists:bills,id',
            'company_id' => 'nullable|exists:companies,id',
        ]);

        $validated['created_by'] = Auth::id();
        $validated['is_completed'] = false;

        $todo = Todo::create($validated);

        if ($request->expectsJson() || $request->ajax()) {
            return response()->json([
                'success' => true,
                'message' => 'Task created successfully!',
                'todo' => $todo->load(['tender', 'bill', 'company']),
            ]);
        }

        return redirect()->back()->with('success', 'Task "' . $todo->title . '" added to your To-Do list.');
    }

    /**
     * Update an existing todo.
     */
    public function update(Request $request, Todo $todo)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'nullable|string|max:1000',
            'priority' => 'required|in:low,medium,high,urgent',
            'category' => 'required|in:tender,quotation,bill_invoice,cheque_agpr,general',
            'due_date' => 'nullable|date',
            'tender_id' => 'nullable|exists:tenders,id',
            'bill_id' => 'nullable|exists:bills,id',
            'company_id' => 'nullable|exists:companies,id',
        ]);

        $todo->update($validated);

        if ($request->expectsJson() || $request->ajax()) {
            return response()->json([
                'success' => true,
                'message' => 'Task updated successfully!',
                'todo' => $todo->load(['tender', 'bill', 'company']),
            ]);
        }

        return redirect()->back()->with('success', 'Task updated successfully.');
    }

    /**
     * Toggle completion status (AJAX friendly).
     */
    public function toggleComplete(Request $request, Todo $todo)
    {
        $todo->is_completed = !$todo->is_completed;
        $todo->completed_at = $todo->is_completed ? Carbon::now() : null;
        $todo->save();

        if ($request->expectsJson() || $request->ajax()) {
            return response()->json([
                'success' => true,
                'is_completed' => $todo->is_completed,
                'completed_at' => $todo->completed_at ? $todo->completed_at->format('M d, Y h:i A') : null,
                'pending_count' => Todo::pending()->count(),
                'completed_count' => Todo::completed()->count(),
            ]);
        }

        $statusText = $todo->is_completed ? 'marked as completed' : 're-opened';
        return redirect()->back()->with('success', "Task {$statusText}.");
    }

    /**
     * Remove the specified todo.
     */
    public function destroy(Request $request, Todo $todo)
    {
        $title = $todo->title;
        $todo->delete();

        if ($request->expectsJson() || $request->ajax()) {
            return response()->json([
                'success' => true,
                'message' => 'Task deleted successfully.',
                'pending_count' => Todo::pending()->count(),
            ]);
        }

        return redirect()->back()->with('success', "Task \"{$title}\" deleted.");
    }
}
