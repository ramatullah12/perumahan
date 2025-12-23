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
    public function index()
    {
        return match (Auth::user()->role) {
            'admin'    => $this->admin(),
            'owner'    => $this->owner(),
            'customer' => $this->customer(),
            default    => abort(403, 'Unauthorized'),
        };
    }

    public function admin()
    {
        $totalProyek = Project::count();
        $totalUnit = Unit::count();
        $unitTersedia = Unit::where('status', 'Tersedia')->count();
        $bookingPending = Booking::where('status', 'pending')->count();
        $totalBooking = Booking::count();
        $totalCustomer = User::where('role', 'customer')->count();

        $projects = Project::withCount(['bookings as sold_count' => function($query) {
            $query->where('status', 'disetujui');
        }, 'bookings as booked_count' => function($query) {
            $query->where('status', 'pending');
        }])->get();

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

    public function owner()
    {
        $totalProyek = Project::count();
        $totalUnit = Unit::count();
        $totalTerjual = Booking::where('status', 'disetujui')->count();
        
        // SOLUSI: Set ke 0 sementara untuk menghindari error kolom database
        $totalPendapatan = 0; 

        $projects = Project::withCount(['bookings as sold_count' => function($query) {
            $query->where('status', 'disetujui');
        }])->get();

        // Path: resources/views/dashboard/owner/index.blade.php
        return view('dashboard.owner.index', compact(
            'totalProyek', 'totalUnit', 'totalTerjual', 'totalPendapatan', 'projects'
        ));
    }

    public function customer()
    {
        $unreadNotificationsCount = Notification::where('user_id', Auth::id())
            ->where('is_read', false)
            ->count();

        $myBookings = Booking::with('unit.project')
            ->where('user_id', Auth::id())
            ->latest()
            ->get();

        return view('dashboard.customer.index', compact(
            'unreadNotificationsCount', 
            'myBookings'
        ));
    }
}