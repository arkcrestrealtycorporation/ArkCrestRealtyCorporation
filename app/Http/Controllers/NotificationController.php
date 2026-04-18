<?php

namespace App\Http\Controllers;

use App\Models\SystemNotification;
use Illuminate\Http\Request;

class NotificationController extends Controller
{
    // Mark all as read
    public function markAllRead()
    {
        SystemNotification::where('user_id', auth()->id())->update(['is_read' => true]);
        return back();
    }

    // Clear all notifications
    public function clearAll()
    {
        SystemNotification::where('user_id', auth()->id())->delete();
        return back();
    }

    // Mark single as read
    public function markRead($id)
    {
        SystemNotification::where('id', $id)->where('user_id', auth()->id())->update(['is_read' => true]);
        return back();
    }

    // Poll unread count (for real-time badge update)
    public function count()
    {
        $count = SystemNotification::where('user_id', auth()->id())
            ->where('is_read', false)
            ->count();

        $pendingPerms = 0;
        if (auth()->user()->isAdmin()) {
            $pendingPerms = \App\Models\PermissionRequest::where('status', 'pending')->count();
        }

        return response()->json([
            'unread'        => $count,
            'pending_perms' => $pendingPerms,
        ]);
    }

    // Get latest notifications as JSON for real-time panel update
    public function latest()
    {
        $notifs = SystemNotification::where('user_id', auth()->id())
            ->orderBy('notified_at', 'desc')
            ->limit(50)
            ->get();

        return response()->json($notifs->map(fn($n) => [
            'id'          => $n->id,
            'type'        => $n->type,
            'title'       => $n->title,
            'message'     => $n->message,
            'is_read'     => $n->is_read,
            'note_id'     => $n->note_id,
            'notified_at' => $n->notified_at->diffForHumans(),
        ]));
    }
}
