<?php

namespace App\Http\Controllers;

use App\Models\Notification;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class NotificationController extends Controller
{
    /**
     * Menampilkan daftar notifikasi untuk Customer.
     */
    public function index()
    {
        // 1. Mengambil notifikasi milik user yang sedang login
        $notifications = Notification::where('user_id', Auth::id())
            ->latest()
            ->get();

        // PERBAIKAN: Sesuaikan titik (.) dengan urutan folder di VS Code Anda
        // Di gambar folder Anda adalah notifications -> customer -> index
        return view('notifications.customer.index', compact('notifications'));
    }

    /**
     * Menandai notifikasi sebagai dibaca.
     */
    public function markAsRead($id)
    {
        $notification = Notification::where('user_id', Auth::id())->findOrFail($id);
        $notification->update(['is_read' => true]);

        return back()->with('success', 'Notifikasi ditandai sebagai dibaca');
    }
}