<?php

namespace App\Services;

use App\Models\User;
use App\Models\UserNotification;

class NotificationService
{
    /**
     * Send a notification to every user with the given role.
     */
    public function sendToRole(
        string $role,
        ?int $laporanId,
        string $type,
        string $title,
        string $message,
        ?string $url
    ): void {
        User::query()
            ->where('role', $role)
            ->select('id')
            ->each(function (User $user) use (
                $laporanId,
                $type,
                $title,
                $message,
                $url
            ): void {
                $this->sendToUser(
                    $user->id,
                    $laporanId,
                    $type,
                    $title,
                    $message,
                    $url
                );
            });
    }

    /**
     * Send a notification to one user.
     */
    public function sendToUser(
        int $userId,
        ?int $laporanId,
        string $type,
        string $title,
        string $message,
        ?string $url
    ): void {
        UserNotification::create([
            'user_id' => $userId,
            'laporan_kerusakan_id' => $laporanId,
            'type' => $type,
            'title' => $title,
            'message' => $message,
            'url' => $url,
        ]);
    }
}
