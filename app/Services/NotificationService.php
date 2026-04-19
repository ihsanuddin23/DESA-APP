<?php

namespace App\Services;

use App\Models\User;
use Illuminate\Notifications\Notification;
use Illuminate\Support\Facades\Notification as NotificationFacade;

class NotificationService
{
    /**
     * Kirim notifikasi ke semua user dengan role tertentu.
     * Exclude user yang melakukan aksi (tidak perlu dikasih notif ke diri sendiri).
     *
     * @param  array|string  $roles       Role yang akan menerima (misal ['admin', 'staff_desa'])
     * @param  Notification  $notification Notifikasi yang akan dikirim
     * @param  int|null      $excludeUserId User ID yang di-exclude (biasanya aktor yang melakukan aksi)
     */
    public static function notifyRoles(
        array|string $roles,
        Notification $notification,
        ?int $excludeUserId = null
    ): void {
        $roles = is_array($roles) ? $roles : [$roles];

        $users = User::whereIn('role', $roles)
            ->where('is_active', true)
            ->when($excludeUserId, fn($q) => $q->where('id', '!=', $excludeUserId))
            ->get();

        if ($users->isNotEmpty()) {
            NotificationFacade::send($users, $notification);
        }
    }

    /**
     * Shortcut: notif ke admin saja.
     */
    public static function notifyAdmin(Notification $notification, ?int $excludeUserId = null): void
    {
        self::notifyRoles(User::ROLE_ADMIN, $notification, $excludeUserId);
    }

    /**
     * Shortcut: notif ke admin + staff desa.
     */
    public static function notifyAdminAndStaff(Notification $notification, ?int $excludeUserId = null): void
    {
        self::notifyRoles(
            [User::ROLE_ADMIN, User::ROLE_STAFF_DESA],
            $notification,
            $excludeUserId
        );
    }
}