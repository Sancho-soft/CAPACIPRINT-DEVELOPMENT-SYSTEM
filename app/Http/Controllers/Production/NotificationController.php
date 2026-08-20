<?php

namespace App\Http\Controllers\Production;

use App\Http\Controllers\Controller;
use App\Models\InternalNotification;

class NotificationController extends Controller
{
    public function index()
    {
        $notifications = InternalNotification::where('user_id', auth()->id())
            ->latest()->paginate(20);
        $unreadCount = InternalNotification::where('user_id', auth()->id())->where('is_read', false)->count();
        return view('production.notifications.index', compact('notifications', 'unreadCount'));
    }

    public function markRead($id)
    {
        InternalNotification::where('user_id', auth()->id())->findOrFail($id)->update(['is_read' => true]);
        return back();
    }
}
