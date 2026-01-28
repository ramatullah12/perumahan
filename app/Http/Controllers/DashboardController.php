<?php

namespace App\Http\Controllers;

use App\Models\Project;
use App\Models\Unit;
use App\Models\Booking;
use App\Models\User;
use App\Models\Notification;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class DashboardController extends Controller
{
    /**
     * Mengarahkan pengguna ke dashboard yang sesuai berdasarkan role.
     */
    public function index()
    {
        // Pastikan user sudah login sebelum memproses role
        if (!Auth::check()) {
            return redirect()->route('login');
        }

        return match (Auth::user()->role) {
            'admin'    => $this->admin(),
            'owner'    => $this->owner(),
            'customer' => $this->customer(),
            default    => abort(403, 'Unauthorized Action'),
        };
    }

    /**
     * Dashboard untuk Admin
     */
    public function admin()
    {
        $totalProyek = Project::count();
        $totalUnit = Unit::count();
        $unitTersedia = Unit::where('status', 'Tersedia')->count();
        $bookingPending = Booking::where('status', 'pending')->count();
        $totalBooking = Booking::count();
        $totalCustomer = User::where('role', 'customer')->count();

        // Mengambil data proyek dengan hitungan status booking tertentu
        $projects = Project::withCount(['bookings as sold_count' => function($query) {
            $query->where('status', 'disetujui');
        }, 'bookings as booked_count' => function($query) {
            $query->where('status', 'pending');
        }])->get();

        // 5 Approval terbaru untuk ditampilkan di widget dashboard
        $pendingApprovals = Booking::with(['user', 'unit.project'])
            ->where('status', 'pending')
            ->latest()
            ->take(5)
            ->get();

        return view('dashboard.admin.index', compact(
            'totalProyek', 'totalUnit', 'unitTersedia', 'bookingPending', 
            'totalBooking', 'totalCustomer', 'pendingApprovals', 'projects'
        ));
    }

    /**
     * Dashboard untuk Owner
     */
    public function owner()
    {
        $totalProyek = Project::count();
        $totalUnit = Unit::count();
        $totalTerjual = Booking::where('status', 'disetujui')->count();
        
        // Pendapatan disetel 0 sementara agar tidak error jika kolom harga belum ada
        $totalPendapatan = 0; 

        $projects = Project::withCount(['bookings as sold_count' => function($query) {
            $query->where('status', 'disetujui');
        }])->get();

        return view('dashboard.owner.index', compact(
            'totalProyek', 'totalUnit', 'totalTerjual', 'totalPendapatan', 'projects'
        ));
    }

    /**
     * Dashboard untuk Customer
     */
    public function customer()
    {
        // Hitung notifikasi yang belum dibaca
        $unreadNotificationsCount = Notification::where('user_id', Auth::id())
            ->where('is_read', false)
            ->count();

        // Ambil data booking milik customer yang sedang login
        $myBookings = Booking::with(['unit.project', 'unit.tipe'])
            ->where('user_id', Auth::id())
            ->latest()
            ->get();

        return view('dashboard.customer.index', compact(
            'unreadNotificationsCount', 
            'myBookings'
        ));
    }
}