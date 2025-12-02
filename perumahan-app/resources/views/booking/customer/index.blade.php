@extends('dashboard.customer')

@section('content')

<style>
    .title-box {
        font-size: 20px;
        font-weight: 600;
        margin-bottom: 4px;
    }

    .subtitle {
        color: #6c757d;
        font-size: 14px;
        margin-bottom: 20px;
    }

    .btn-book {
        background: #0d6efd;
        color: white;
        padding: 10px 16px;
        border-radius: 8px;
        font-size: 14px;
        text-decoration: none;
        float: right;
    }

    .booking-card {
        background: white;
        border-radius: 12px;
        padding: 20px;
        box-shadow: 0 3px 12px rgba(0,0,0,0.06);
        display: flex;
        position: relative;
    }

    .unit-img {
        width: 180px;
        height: 120px;
        border-radius: 10px;
        object-fit: cover;
        margin-right: 20px;
    }

    .badge-status {
        background: #d4f7df;
        color: #28a745;
        padding: 6px 14px;
        border-radius: 20px;
        font-size: 13px;
        display: inline-block;
        position: absolute;
        top: 16px;
        right: 20px;
    }

    .doc-btn {
        background: #eef1f5;
        padding: 6px 12px;
        border-radius: 6px;
        font-size: 13px;
        text-decoration: none;
        margin-right: 6px;
        color: #333;
    }

    /* Notifikasi status */
    .notif-success {
        background: #e9fbe8;
        border: 1px solid #c9efc9;
        padding: 14px 18px;
        border-radius: 10px;
        margin-top: 14px;
        display: flex;
        align-items: center;
        justify-content: space-between;
    }

    .notif-text {
        color: #2b7a2b;
        font-size: 14px;
        font-weight: 500;
    }

    .btn-progress {
        background: #28a745;
        color: white;
        padding: 8px 16px;
        border-radius: 8px;
        font-size: 14px;
        text-decoration: none;
    }
</style>


{{-- HEADER --}}
<div style="margin-bottom: 20px;">
    <div class="title-box">Booking Saya</div>
    <div class="subtitle">Kelola semua booking unit rumah Anda</div>

    <a href="/customer/booking/new" class="btn-book">+ Booking Unit Baru</a>
</div>


{{-- LIST BOOKING --}}
@foreach ($bookings as $b)
<div class="booking-card">

    {{-- Foto Unit --}}
    <img src="{{ asset('storage/' . $b->unit->gambar) }}" class="unit-img">

    <div>
        <div style="font-size: 18px; font-weight: 600;">{{ $b->project->nama_proyek }}</div>
        <div style="color:#6c757d;">
            Unit {{ $b->unit->kode_unit }} – Tipe {{ $b->unit->tipe }}
        </div>

        <div style="font-weight: 600; margin-top:6px;">
            Rp {{ number_format($b->unit->harga, 0, ',', '.') }}
        </div>

        <div style="margin-top:10px; color:#444;">
            Booking: {{ \Carbon\Carbon::parse($b->tanggal_booking)->format('l, d F Y') }}
        </div>

        <div style="margin-top:10px; font-weight:600;">Dokumen:</div>
        <div style="margin-top:6px;">
            @if($b->dokumen_ktp)
                <a class="doc-btn" target="_blank" href="{{ asset('storage/' . $b->dokumen_ktp) }}">📄 KTP.pdf</a>
            @endif

            @if($b->dokumen_npwp)
                <a class="doc-btn" target="_blank" href="{{ asset('storage/' . $b->dokumen_npwp) }}">📄 NPWP.pdf</a>
            @endif
        </div>

    </div>

    {{-- STATUS --}}
    <div class="badge-status">
        ✔ Disetujui
    </div>

</div>


{{-- NOTIFIKASI --}}
@if($b->status == 'approved')
<div class="notif-success">
    <div class="notif-text">
        ✔ Booking Anda telah disetujui! Anda dapat melihat progres pembangunan.
    </div>

    <a href="/customer/progress/{{ $b->unit_id }}" class="btn-progress">
        📈 Lihat Progres
    </a>
</div>
@endif

@endforeach

@endsection
