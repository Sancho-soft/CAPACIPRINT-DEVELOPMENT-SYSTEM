<?php

namespace App\Http\Controllers\Staff;

use App\Http\Controllers\Controller;
use App\Models\InternalNotification;
use Illuminate\Http\Request;

class NotificationController extends Controller
{
    public function index()
    {
        $notifications = InternalNotification::where('user_id', auth()->id())
            ->latest()->paginate(20);

        $unreadCount = InternalNotification::where('user_id', auth()->id())
            ->where('is_read', false)->count();

        return view('staff.notifications.index', compact('notifications', 'unreadCount'));
    }

    public function markRead(Request $request, $id)
    {
        InternalNotification::where('user_id', auth()->id())
            ->findOrFail($id)
            ->update(['is_read' => true]);

        return back();
    }

    public function markAllRead(Request $request)
    {
        InternalNotification::where('user_id', auth()->id())
            ->where('is_read', false)
            ->update(['is_read' => true]);

        return back()->with('success', 'All notifications marked as read.');
    }
}
