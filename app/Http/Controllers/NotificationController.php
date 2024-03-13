<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Auth;

class NotificationController extends Controller
{
    public function getUnreadNotifications()
    {
        $user = Auth::user();
        $unreadNotifications = $user->unreadNotifications;

        return response()->json($unreadNotifications);
    }

    public function markAsRead($id)
    {
        $user = Auth::user();
        $notification = $user->unreadNotifications->findOrFail($id);
        $notification->markAsRead();

        return response()->json(['message' => 'Notification marked as read.']);
    }

    public function deleteNotification($id)
    {
        $user = Auth::user();
        $notification = $user->notifications->find($id);
        if ($notification) {
            $notification->delete();
            return response()->json(['message' => 'Notification deleted successfully.']);
        } else {
            return response()->json(['message' => 'Notification not found.'], 404);
        }
    }
}
