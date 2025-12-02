<?php

namespace App\Http\Controllers;

use App\Models\Booking;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class BookingController extends Controller
{
    // ===========================================================
    // CUSTOMER - LIHAT BOOKING SENDIRI
    // ===========================================================
    public function indexCustomer()
    {
        $bookings = Booking::where('user_id', Auth::id())
                            ->latest()
                            ->get();

        return view('booking.customer.index', compact('bookings'));
    }

    // ===========================================================
    // CUSTOMER - FORM BOOKING
    // ===========================================================
    public function create()
    {
        return view('booking.customer.create');
    }

    // ===========================================================
    // CUSTOMER - SIMPAN BOOKING
    // ===========================================================
    public function store(Request $request)
    {
        $request->validate([
            'project_id' => 'required',
            'unit_id' => 'required',
            'tanggal_booking' => 'required|date',
        ]);

        Booking::create([
            'user_id' => Auth::id(),
            'project_id' => $request->project_id,
            'unit_id' => $request->unit_id,
            'tanggal_booking' => $request->tanggal_booking,
            'status' => 'pending',
        ]);

        return redirect()->route('customer.booking.index')
            ->with('success', 'Booking berhasil dibuat!');
    }

    // ===========================================================
    // ADMIN - LIHAT SEMUA BOOKING
    // ===========================================================
    public function indexAdmin()
    {
        $bookings = Booking::with('user')
                            ->latest()
                            ->get();

        return view('booking.admin.index', compact('bookings'));
    }

    // ===========================================================
    // ADMIN - DETAIL BOOKING
    // ===========================================================
    public function show(Booking $booking)
    {
        return view('booking.admin.show', compact('booking'));
    }

    // ===========================================================
    // ADMIN - UPDATE STATUS BOOKING
    // ===========================================================
    public function updateStatus(Request $request, Booking $booking)
    {
        $request->validate([
            'status' => 'required|in:pending,disetujui,ditolak',
        ]);

        $booking->update([
            'status' => $request->status
        ]);

        return redirect()->route('admin.booking.index')
            ->with('success', 'Status booking berhasil diperbarui!');
    }
}
