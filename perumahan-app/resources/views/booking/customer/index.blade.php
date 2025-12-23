@extends('layout.customer')

@section('content')

<style>
    .content-wrapper { padding: 30px; font-family: 'Inter', sans-serif; background: #f8fafc; }
    .header-section { display: flex; justify-content: space-between; align-items: flex-end; margin-bottom: 30px; }
    .title-box { font-size: 28px; font-weight: 900; color: #1a202c; letter-spacing: -1px; }
    .subtitle { color: #718096; font-size: 14px; font-weight: 500; }
    .btn-book { background: #1e5eff; color: white; padding: 14px 28px; border-radius: 16px; font-size: 14px; font-weight: 800; text-decoration: none; transition: 0.3s; box-shadow: 0 10px 15px -3px rgba(30, 94, 255, 0.2); }
    .btn-book:hover { background: #0046ff; transform: translateY(-2px); }
    
    /* STYLE PESAN SUKSES */
    .alert-success-custom { 
        background: #f0fdf4; color: #15803d; padding: 18px 25px; border-radius: 20px; 
        margin-bottom: 30px; border: 1px solid #bbf7d0; display: flex; align-items: center;
        box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.05);
        animation: slideDown 0.5s ease-out;
    }
    
    @keyframes slideDown {
        from { opacity: 0; transform: translateY(-15px); }
        to { opacity: 1; transform: translateY(0); }
    }

    .booking-wrapper { background: white; border: 1px solid #e2e8f0; border-radius: 24px; overflow: hidden; margin-bottom: 25px; transition: 0.3s; }
    .booking-wrapper:hover { border-color: #1e5eff; box-shadow: 0 20px 25px -5px rgba(0, 0, 0, 0.05); }
    
    .booking-card { padding: 30px; display: flex; gap: 30px; position: relative; align-items: center; }
    
    .unit-img-wrapper { width: 180px; height: 130px; border-radius: 20px; overflow: hidden; background: #f1f5f9; flex-shrink: 0; border: 1px solid #edf2f7; }
    .unit-img { width: 100%; height: 100%; object-fit: cover; transition: 0.5s; }
    
    .unit-price { color: #1e5eff; font-size: 22px; font-weight: 900; margin-top: 8px; }
    
    .badge-status { 
        padding: 8px 18px; border-radius: 12px; font-size: 11px; font-weight: 800; 
        position: absolute; top: 30px; right: 30px; text-transform: uppercase; letter-spacing: 1px;
    }
    .status-pending { background: #fffaf0; color: #dd6b20; border: 1px solid #feebc8; }
    .status-disetujui { background: #f0fff4; color: #38a169; border: 1px solid #c6f6d5; }
    .status-ditolak { background: #fff5f5; color: #e53e3e; border: 1px solid #fed7d7; }

    .notif-banner { background: #f0fff4; padding: 20px 30px; display: flex; align-items: center; justify-content: space-between; border-top: 1px solid #c6f6d5; }
    .btn-progress { background: #10b981; color: white; padding: 12px 24px; border-radius: 14px; font-size: 12px; font-weight: 800; text-decoration: none; transition: 0.3s; box-shadow: 0 4px 6px rgba(16,185,129,0.2); }
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

    {{-- LOGIKA PESAN BERHASIL --}}
    @if(session('success'))
        <div class="alert-success-custom">
            <div style="background: #22c55e; color: white; width: 32px; height: 32px; border-radius: 50%; display: flex; align-items: center; justify-content: center; margin-right: 15px; flex-shrink: 0;">
                <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="3">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7" />
                </svg>
            </div>
            <div>
                <div style="font-weight: 800; font-size: 15px;">Berhasil Memesan!</div>
                <div style="font-size: 13px; opacity: 0.9;">{{ session('success') }}</div>
            </div>
        </div>
    @endif

    {{-- LIST BOOKING --}}
    @forelse ($bookings as $b)
    <div class="booking-wrapper">
        <div class="booking-card">
            <div class="unit-img-wrapper">
                @php
                    $pathFoto = $b->unit->project->gambar ?? null;
                @endphp

                @if($pathFoto && Storage::disk('public')->exists($pathFoto))
                    <img src="{{ asset('storage/' . $pathFoto) }}" class="unit-img" alt="Foto Proyek">
                @else
                    <img src="https://images.unsplash.com/photo-1560518883-ce09059eeffa?q=80&w=400" class="unit-img" alt="Default">
                @endif
            </div>

            <div class="card-info">
                <div style="font-size: 22px; font-weight: 900; color: #1a202c; letter-spacing: -0.5px;">
                    {{ $b->unit->project->nama_proyek ?? 'Proyek Tidak Diketahui' }}
                </div>
                <div style="color: #718096; font-size: 15px; font-weight: 600; margin-top: 2px;">
                    Blok {{ $b->unit->block ?? '-' }} No. {{ $b->unit->no_unit ?? '-' }} – 
                    <span class="text-blue-600">Tipe {{ $b->unit->tipe->nama_tipe ?? 'N/A' }}</span>
                </div>

                <div class="unit-price">
                    Rp {{ number_format($b->unit->tipe->harga ?? 0, 0, ',', '.') }}
                </div>

                <div style="color: #a0aec0; font-size: 13px; margin-top: 15px; font-weight: 500; display: flex; align-items: center; gap: 8px;">
                    <i class="far fa-calendar-alt"></i>
                    Diajukan pada: 
                    <span style="color: #4a5568; font-weight: 700;">
                        {{ \Carbon\Carbon::parse($b->tanggal_booking)->translatedFormat('l, d F Y') }}
                    </span>
                </div>
            </div>

            <div class="badge-status status-{{ strtolower($b->status ?? 'pending') }}">
                ● {{ strtoupper($b->status ?? 'pending') }}
            </div>
        </div>

        @if($b->status == 'disetujui')
        <div class="notif-banner">
            <div style="display: flex; align-items: center; gap: 12px;">
                <div style="background: #10b981; color: white; width: 32px; height: 32px; border-radius: 50%; display: flex; align-items: center; justify-content: center; font-size: 16px; font-weight: bold; box-shadow: 0 4px 6px rgba(16,185,129,0.2);">✓</div>
                <div>
                    <div style="color: #065f46; font-size: 14px; font-weight: 800;">Booking Disetujui!</div>
                    <div style="color: #047857; font-size: 12px; font-weight: 500;">Pantau tahap pembangunan rumah Anda secara real-time melalui tombol di samping.</div>
                </div>
            </div>
            <a href="{{ route('customer.progres.index') }}" 
   class="btn-progress {{ request()->routeIs('customer.progres.index') ? 'active' : '' }}">
   LIHAT PROGRES RUMAH
</a>
        </div>
        @endif
    </div>
    @empty
    <div style="text-align: center; padding: 100px 20px; background: white; border-radius: 30px; border: 2px dashed #e2e8f0;">
        <div style="font-size: 60px; margin-bottom: 20px;">🏘️</div>
        <h3 style="font-weight: 800; color: #4a5568; font-size: 20px;">Belum Ada Booking</h3>
        <p style="color: #a0aec0; max-width: 400px; margin: 0 auto;">Mulai ajukan pemesanan unit rumah impian Anda sekarang!</p>
        <br>
        <a href="{{ route('customer.booking.create') }}" class="btn-book">Cari Unit Rumah</a>
    </div>
    @endforelse
</div>
@endsection