<?php

namespace App\Http\Controllers\Api\V1\User;

use App\Http\Controllers\Controller;
use App\Models\Notification;
use Illuminate\Http\Request;

class NotificationController extends Controller
{
    public function index(Request $request)
    {
        /** @var \App\Models\User $user */
        $user = $request->user();

        $notifications = Notification::where('user_id', $user->id)
            ->orderByDesc('created_at')
            ->limit(100)
            ->get();

        return response()->json($notifications);
    }

    public function markAsRead(Notification $notification, Request $request)
    {
        /** @var \App\Models\User $user */
        $user = $request->user();

        abort_unless($notification->user_id === $user->id, 403);

        $notification->read_at = now();
        $notification->save();

        return response()->json(['message' => 'Notification marked as read.']);
    }
}

