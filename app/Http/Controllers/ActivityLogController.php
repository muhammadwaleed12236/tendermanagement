<?php

namespace App\Http\Controllers;

use App\Models\ActivityLog;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Auth;

class ActivityLogController extends Controller
{
    /**
     * Display a listing of system activity logs / audit trail
     */
    public function index(Request $request)
    {
        $query = ActivityLog::with('user')->latest();

        // 1. Filter by User
        if ($userId = $request->query('user_id')) {
            $query->where('user_id', $userId);
        }

        // 2. Filter by Module
        if ($module = $request->query('module')) {
            $query->where('module', $module);
        }

        // 3. Filter by Action
        if ($action = $request->query('action')) {
            $query->where('action', $action);
        }

        // 4. Filter by Date range
        if ($dateFrom = $request->query('date_from')) {
            $query->whereDate('created_at', '>=', Carbon::parse($dateFrom)->startOfDay());
        }
        if ($dateTo = $request->query('date_to')) {
            $query->whereDate('created_at', '<=', Carbon::parse($dateTo)->endOfDay());
        }

        // 5. Keyword search
        if ($search = $request->query('search')) {
            $query->where(function ($q) use ($search) {
                $q->where('description', 'like', "%{$search}%")
                  ->orWhere('user_name', 'like', "%{$search}%")
                  ->orWhere('user_role', 'like', "%{$search}%")
                  ->orWhere('ip_address', 'like', "%{$search}%");
            });
        }

        $logs = $query->paginate(25)->withQueryString();

        // High-level KPIs
        $totalCount = ActivityLog::count();
        $todayCount = ActivityLog::whereDate('created_at', today())->count();
        $uniqueUsersCount = ActivityLog::distinct('user_id')->whereNotNull('user_id')->count('user_id');
        $topModule = ActivityLog::selectRaw('module, count(*) as count')
            ->groupBy('module')
            ->orderByDesc('count')
            ->first()?->module ?: 'Bills';

        // Filter Options
        $usersList = User::orderBy('name')->get(['id', 'name', 'email', 'role']);
        $modulesList = ActivityLog::distinct()->pluck('module')->filter()->values();
        $actionsList = ActivityLog::distinct()->pluck('action')->filter()->values();

        // Default list if empty
        if ($modulesList->isEmpty()) {
            $modulesList = collect(['Bills', 'Cheques', 'Departments', 'Companies', 'Products', 'Users', 'Auth']);
        }
        if ($actionsList->isEmpty()) {
            $actionsList = collect(['created', 'updated', 'deleted', 'status_changed', 'login', 'logout', 'imported']);
        }

        return view('activity_logs.index', compact(
            'logs',
            'totalCount',
            'todayCount',
            'uniqueUsersCount',
            'topModule',
            'usersList',
            'modulesList',
            'actionsList'
        ));
    }

    /**
     * Delete old activity logs (Admin only)
     */
    public function purge(Request $request)
    {
        if (!Auth::user()->isAdmin()) {
            abort(403, 'Unauthorized.');
        }

        $days = (int) $request->input('days', 90);
        $beforeDate = now()->subDays($days);

        $deletedCount = ActivityLog::where('created_at', '<', $beforeDate)->delete();

        ActivityLog::record(
            action: 'deleted',
            module: 'Users',
            description: "Purged {$deletedCount} activity log records older than {$days} days.",
            properties: ['days' => $days, 'deleted_count' => $deletedCount]
        );

        return back()->with('success', "Successfully purged {$deletedCount} old audit log records.");
    }
}
