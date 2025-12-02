@extends('dashboard.admin')

@section('content')

<style>
    .booking-title {
        font-size: 20px;
        font-weight: 600;
        margin-bottom: 4px;
    }

    .booking-sub {
        color: #6c757d;
        font-size: 14px;
        margin-bottom: 20px;
    }

    .table-header {
        background: white;
        padding: 15px 20px;
        border-radius: 10px;
        border: 1px solid #eee;
        font-weight: 600;
        font-size: 14px;
        display: grid;
        grid-template-columns: 1fr 1fr 130px 150px 120px 100px;
        color: #333;
    }

    .table-row {
        background: white;
        padding: 14px 20px;
        border-radius: 10px;
        border: 1px solid #eee;
        margin-top: 10px;
        font-size: 14px;
        display: grid;
        grid-template-columns: 1fr 1fr 130px 150px 120px 100px;
        align-items: center;
    }
</style>

<div class="booking-title">Manajemen Booking</div>
<div class="booking-sub">Kelola semua booking dari customer</div>

{{-- HEADER TABEL --}}
<div class="table-header">
    <div>Customer</div>
    <div>Proyek & Unit</div>
    <div>Tanggal</div>
    <div>Dokumen</div>
    <div>Status</div>
    <div>Aksi</div>
</div>

{{-- DATA BOOKING --}}
@if($bookings->count() > 0)
    @foreach($bookings as $booking)
        <div class="table-row">
            <div>{{ $booking->customer->name }}</div>
            <div>{{ $booking->project->nama_proyek }} - {{ $booking->unit->nama_unit }}</div>
            <div>{{ $booking->tanggal }}</div>
            <div>
                @if($booking->dokumen)
                    <a href="{{ asset('storage/'.$booking->dokumen) }}" target="_blank">Lihat</a>
                @else
                    -
                @endif
            </div>
            <div>{{ ucfirst($booking->status) }}</div>
            <div>
                <a href="/admin/booking/{{ $booking->id }}/detail" class="btn btn-sm btn-primary">Detail</a>
            </div>
        </div>
    @endforeach
@else
    <div class="table-row text-center">
        <div style="grid-column: 1/7;">Tidak ada data booking</div>
    </div>
@endif

@endsection
