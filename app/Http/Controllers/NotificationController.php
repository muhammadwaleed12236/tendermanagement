<?php

namespace App\Http\Controllers;

use App\Services\NotificationService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class NotificationController extends Controller
{
    /**
     * Display a listing of all notifications for the authenticated user.
     */
    public function index(Request $request)
    {
        $user = Auth::user();

        // Run automated sync for any pending alerts (closing tenders, pending letters, etc.)
        NotificationService::syncAutomatedAlerts($user);

        $query = $user->notifications();

        // Filter: Status (unread, read, all)
        $status = $request->get('status', 'all');
        if ($status === 'unread') {
            $query->whereNull('read_at');
        } elseif ($status === 'read') {
            $query->whereNotNull('read_at');
        }

        // Filter: Category / Type
        $category = $request->get('category', 'all');
        if ($category === 'tenders') {
            $query->where(function ($q) {
                $q->where('data', 'like', '%"type":"tender_%')
                  ->orWhere('data', 'like', '%"type":"bid_security_%');
            });
        } elseif ($category === 'bills') {
            $query->where('data', 'like', '%"type":"bill_%');
        } elseif ($category === 'cheques') {
            $query->where('data', 'like', '%"type":"cheque_%');
        } elseif ($category === 'alerts') {
            $query->where(function ($q) {
                $q->where('data', 'like', '%"level":"danger"%')
                  ->orWhere('data', 'like', '%"level":"warning"%');
            });
        }

        $notifications = $query->latest()->paginate(15)->withQueryString();

        $unreadCount = $user->unreadNotifications()->count();
        $totalCount = $user->notifications()->count();

        return view('notifications.index', compact('notifications', 'unreadCount', 'totalCount', 'status', 'category'));
    }

    /**
     * Mark a specific notification as read.
     */
    public function markAsRead(Request $request, string $id)
    {
        $user = Auth::user();
        $notification = $user->notifications()->where('id', $id)->first();

        if ($notification) {
            $notification->markAsRead();
        }

        if ($request->wantsJson()) {
            return response()->json([
                'success' => true,
                'unread_count' => $user->unreadNotifications()->count(),
            ]);
        }

        $targetUrl = $notification && !empty($notification->data['action_url']) 
            ? $notification->data['action_url'] 
            : route('notifications.index');

        return redirect($targetUrl);
    }

    /**
     * Mark all unread notifications as read.
     */
    public function markAllAsRead(Request $request)
    {
        $user = Auth::user();
        $user->unreadNotifications->markAsRead();

        if ($request->wantsJson()) {
            return response()->json([
                'success' => true,
                'unread_count' => 0,
            ]);
        }

        return redirect()->back()->with('success', 'All notifications marked as read.');
    }

    /**
     * Delete a specific notification.
     */
    public function destroy(Request $request, string $id)
    {
        $user = Auth::user();
        $notification = $user->notifications()->where('id', $id)->first();

        if ($notification) {
            $notification->delete();
        }

        if ($request->wantsJson()) {
            return response()->json(['success' => true]);
        }

        return redirect()->back()->with('success', 'Notification removed.');
    }

    /**
     * Clear all read notifications.
     */
    public function clearRead(Request $request)
    {
        $user = Auth::user();
        $user->readNotifications()->delete();

        return redirect()->back()->with('success', 'Read notifications cleared.');
    }

    /**
     * API endpoint to get real-time unread count and latest 8 notifications for dropdown bell.
     */
    public function apiFeed(Request $request)
    {
        $user = Auth::user();

        // Optionally run sync
        NotificationService::syncAutomatedAlerts($user);

        $unreadCount = $user->unreadNotifications()->count();
        $latest = $user->notifications()->latest()->limit(8)->get()->map(function ($n) {
            return [
                'id' => $n->id,
                'title' => $n->data['title'] ?? 'Notification',
                'message' => $n->data['message'] ?? '',
                'type' => $n->data['type'] ?? 'general',
                'level' => $n->data['level'] ?? 'info',
                'action_url' => $n->data['action_url'] ?? '#',
                'action_text' => $n->data['action_text'] ?? 'View',
                'read' => !is_null($n->read_at),
                'created_at_human' => $n->created_at->diffForHumans(),
            ];
        });

        return response()->json([
            'unread_count' => $unreadCount,
            'notifications' => $latest,
        ]);
    }
}
