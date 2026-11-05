<?php

namespace App\Http\Controllers;

use App\Models\UserNotification;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Auth;

class NotificationController extends Controller
{
    public function index(): JsonResponse
    {
        $userId = Auth::id();

        $notifications = UserNotification::query()
            ->where('user_id', $userId)
            ->latest()
            ->limit(15)
            ->get();

        $unreadCount = UserNotification::query()
            ->where('user_id', $userId)
            ->whereNull('read_at')
            ->count();

        return response()->json([
            'unread_count' => $unreadCount,
            'notifications' => $notifications->map(function (UserNotification $item): array {
                return [
                    'id' => $item->id,
                    'title' => $item->title,
                    'message' => $item->message,
                    'type' => $item->type,
                    'url' => $item->url,
                    'is_read' => $item->read_at !== null,
                    'created_at' => $item->created_at?->diffForHumans(),
                ];
            }),
        ]);
    }

    public function read(int $id): JsonResponse
    {
        $notification = UserNotification::query()
            ->where('user_id', Auth::id())
            ->findOrFail($id);

        $notification->update([
            'read_at' => $notification->read_at ?? now(),
        ]);

        return response()->json([
            'success' => true,
        ]);
    }

    public function readAll(): JsonResponse
    {
        UserNotification::query()
            ->where('user_id', Auth::id())
            ->whereNull('read_at')
            ->update([
                'read_at' => now(),
            ]);

        return response()->json([
            'success' => true,
        ]);
    }
}
