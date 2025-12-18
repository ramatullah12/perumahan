@extends('dashboard.customer')

@section('content')

<style>
    .content-wrapper { padding: 30px; font-family: 'Inter', sans-serif; background: #f8fafc; }
    .header-section { display: flex; justify-content: space-between; align-items: flex-end; margin-bottom: 30px; }
    .title-box { font-size: 28px; font-weight: 900; color: #1a202c; letter-spacing: -1px; }
    .subtitle { color: #718096; font-size: 14px; font-weight: 500; }
    .btn-book { background: #1e5eff; color: white; padding: 14px 28px; border-radius: 16px; font-size: 14px; font-weight: 800; text-decoration: none; transition: 0.3s; box-shadow: 0 10px 15px -3px rgba(30, 94, 255, 0.2); }
    .btn-book:hover { background: #0046ff; transform: translateY(-2px); }
    
    .booking-wrapper { background: white; border: 1px solid #e2e8f0; border-radius: 24px; overflow: hidden; margin-bottom: 25px; transition: 0.3s; }
    .booking-wrapper:hover { border-color: #1e5eff; box-shadow: 0 20px 25px -5px rgba(0, 0, 0, 0.05); }
    
    .booking-card { padding: 30px; display: flex; gap: 30px; position: relative; }
    .unit-img { width: 150px; height: 150px; border-radius: 20px; object-fit: cover; background: #edf2f7; }
    
    .unit-price { color: #1e5eff; font-size: 20px; font-weight: 800; margin-top: 8px; }
    
    .badge-status { 
        padding: 8px 18px; border-radius: 12px; font-size: 12px; font-weight: 800; 
        position: absolute; top: 30px; right: 30px; text-transform: uppercase; letter-spacing: 1px;
    }
    .status-pending { background: #fffaf0; color: #dd6b20; border: 1px solid #feebc8; }
    .status-disetujui { background: #f0fff4; color: #38a169; border: 1px solid #c6f6d5; }
    .status-ditolak { background: #fff5f5; color: #e53e3e; border: 1px solid #fed7d7; }

    .notif-banner { background: #f0fff4; padding: 20px 30px; display: flex; align-items: center; justify-content: space-between; border-top: 1px solid #c6f6d5; }
    .btn-progress { background: #00ab4e; color: white; padding: 12px 24px; border-radius: 14px; font-size: 13px; font-weight: 800; text-decoration: none; box-shadow: 0 4px 12px rgba(0,171,78,0.2); }
</style>

<div class="content-wrapper">
    {{-- HEADER --}}
    <div class="header-section">
        <div>
            <div class="title-box">Booking Saya</div>
            <div class="subtitle">Pantau status pengajuan unit rumah impian Anda</div>
        </div>
        <a href="{{ route('customer.booking.create') }}" class="btn-book">+ Booking Unit Baru</a>
    </div>

    {{-- LIST BOOKING --}}
    @forelse ($bookings as $b)
    <div class="booking-wrapper">
        <div class="booking-card">
            {{-- Menggunakan gambar unit jika ada --}}
            <img src="{{ $b->unit->foto ? asset('storage/' . $b->unit->foto) : 'https://images.unsplash.com/photo-1570129477492-45c003edd2be?auto=format&fit=crop&q=80&w=300' }}" class="unit-img">

            <div class="card-info">
                <div style="font-size: 20px; font-weight: 900; color: #1a202c;">{{ $b->unit->project->nama_proyek }}</div>
                <div style="color: #718096; font-size: 15px; font-weight: 600; margin-top: 2px;">
                    Blok {{ $b->unit->block }} No. {{ $b->unit->no_unit }} – Tipe {{ $b->unit->tipe->nama_tipe }}
                </div>

                <div class="unit-price">
                    Rp {{ number_format($b->unit->tipe->harga, 0, ',', '.') }}
                </div>

                <div style="color: #a0aec0; font-size: 13px; margin-top: 15px; font-weight: 500;">
                    <i class="far fa-calendar-alt mr-1"></i> Diajukan pada: 
                    <span class="text-gray-700 font-bold">
                        {{ \Carbon\Carbon::parse($b->tanggal_booking)->translatedFormat('l, d F Y') }}
                    </span>
                </div>
            </div>

            {{-- Badge Status Dinamis --}}
            <div class="badge-status status-{{ strtolower($b->status) }}">
                ● {{ $b->status }}
            </div>
        </div>

        {{-- BANNER NOTIFIKASI --}}
        @if($b->status == 'disetujui')
        <div class="notif-banner">
            <div style="display: flex; align-items: center; gap: 12px;">
                <div style="background: #38a169; color: white; width: 24px; height: 24px; border-radius: 50%; display: flex; align-items: center; justify-content: center; font-size: 12px;">✔</div>
                <div style="color: #2f855a; font-size: 14px; font-weight: 600;">
                    Selamat! Booking Anda telah disetujui. Silakan pantau progres pembangunan unit Anda.
                </div>
            </div>

            <a href="{{ route('customer.progres.index') }}" class="btn-progress">
                LIHAT PROGRES PEMBANGUNAN
            </a>
        </div>
        @endif
    </div>
    @empty
    <div style="text-align: center; padding: 100px 20px; background: white; border-radius: 30px; border: 2px dashed #e2e8f0;">
        <div style="font-size: 50px; margin-bottom: 20px;">🏠</div>
        <h3 style="font-weight: 800; color: #4a5568;">Belum Ada Booking</h3>
        <p style="color: #a0aec0;">Anda belum memilih unit manapun. Mulai cari rumah impian Anda sekarang!</p>
        <br>
        <a href="{{ route('customer.booking.create') }}" class="btn-book">Cari Unit Rumah</a>
    </div>
    @endforelse
</div>

@endsection