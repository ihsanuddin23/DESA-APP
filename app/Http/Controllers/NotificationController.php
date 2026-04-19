<?php

namespace App\Http\Controllers;

use Illuminate\Http\{Request, RedirectResponse, JsonResponse};
use Illuminate\View\View;

class NotificationController extends Controller
{
    /**
     * Tampilkan halaman notifikasi lengkap.
     */
    public function index(Request $request): View
    {
        $user = auth()->user();

        $notifications = $user->notifications()
            ->latest()
            ->paginate(20);

        return view('notifications.index', compact('notifications'));
    }

    /**
     * API: Ambil 10 notifikasi terbaru + jumlah unread (untuk dropdown bell icon).
     */
    public function dropdown(Request $request): JsonResponse
    {
        $user = auth()->user();

        return response()->json([
            'unread_count' => $user->unreadNotifications()->count(),
            'notifications' => $user->notifications()
                ->latest()
                ->limit(10)
                ->get()
                ->map(fn($n) => [
                    'id'         => $n->id,
                    'title'      => $n->data['title']   ?? 'Notifikasi',
                    'message'    => $n->data['message'] ?? '',
                    'icon'       => $n->data['icon']    ?? 'bi-bell',
                    'color'      => $n->data['color']   ?? 'info',
                    'url'        => $n->data['url']     ?? '#',
                    'read'       => !is_null($n->read_at),
                    'created_at' => $n->created_at->diffForHumans(),
                ]),
        ]);
    }

    /**
     * Tandai 1 notifikasi sebagai read lalu redirect ke URL-nya.
     */
    public function show(Request $request, string $id): RedirectResponse
    {
        $user = auth()->user();
        $notification = $user->notifications()->where('id', $id)->first();

        if (!$notification) {
            return redirect()->route('notifications.index')
                ->with('error', 'Notifikasi tidak ditemukan.');
        }

        $notification->markAsRead();

        $url = $notification->data['url'] ?? route('notifications.index');
        return redirect($url);
    }

    /**
     * Tandai semua notifikasi user sebagai read.
     */
    public function markAllAsRead(Request $request): RedirectResponse
    {
        auth()->user()->unreadNotifications->markAsRead();
        return back()->with('success', 'Semua notifikasi ditandai sebagai dibaca.');
    }

    /**
     * Hapus 1 notifikasi.
     */
    public function destroy(Request $request, string $id): RedirectResponse
    {
        $notification = auth()->user()->notifications()->where('id', $id)->first();
        if ($notification) {
            $notification->delete();
        }
        return back()->with('success', 'Notifikasi dihapus.');
    }

    /**
     * Hapus semua notifikasi user.
     */
    public function destroyAll(Request $request): RedirectResponse
    {
        auth()->user()->notifications()->delete();
        return back()->with('success', 'Semua notifikasi dihapus.');
    }
}
