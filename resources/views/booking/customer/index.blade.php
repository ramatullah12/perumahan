@extends('layout.customer')

@section('content')

<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.2/font/bootstrap-icons.css">
<link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap" rel="stylesheet">

<style>
    .content-wrapper { padding: 40px; font-family: 'Plus Jakarta Sans', sans-serif; background: #f8fafc; min-height: 100vh; }
    
    /* Header Styles */
    .header-section { display: flex; justify-content: space-between; align-items: center; margin-bottom: 40px; }
    .title-box { font-size: 32px; font-weight: 800; color: #1e293b; letter-spacing: -1px; }
    .subtitle { color: #64748b; font-size: 15px; font-weight: 500; margin-top: 4px; }
    
    .btn-book { 
        background: #1e5eff; color: white; padding: 14px 28px; border-radius: 16px; 
        font-size: 14px; font-weight: 700; text-decoration: none; transition: 0.3s; 
        box-shadow: 0 10px 15px -3px rgba(30, 94, 255, 0.2); display: inline-flex; align-items: center; gap: 8px;
    }
    .btn-book:hover { background: #0046ff; transform: translateY(-2px); color: white; }

    /* Booking Card Styles */
    .booking-wrapper { 
        background: white; border: 1px solid #f1f5f9; border-radius: 28px; 
        overflow: hidden; margin-bottom: 30px; transition: 0.4s cubic-bezier(0.4, 0, 0.2, 1); 
        box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.02);
    }
    .booking-wrapper:hover { transform: translateY(-5px); box-shadow: 0 20px 25px -5px rgba(0, 0, 0, 0.08); border-color: #1e5eff; }
    
    .booking-card { padding: 35px; display: flex; gap: 35px; position: relative; align-items: center; }
    
    /* Image Handling (Cloudinary Optimized) */
    .unit-img-wrapper { 
        width: 240px; height: 160px; border-radius: 20px; overflow: hidden; 
        background: #f1f5f9; flex-shrink: 0; border: 1px solid #edf2f7; 
    }
    .unit-img { width: 100%; height: 100%; object-fit: cover; transition: 0.5s; }
    .booking-wrapper:hover .unit-img { transform: scale(1.08); }

    /* Text Content */
    .project-title { font-size: 24px; font-weight: 800; color: #1e293b; margin-bottom: 8px; }
    .unit-badge-info { 
        display: inline-flex; align-items: center; gap: 8px; 
        background: #f1f5f9; padding: 4px 12px; border-radius: 8px;
        color: #475569; font-size: 14px; font-weight: 700; margin-bottom: 15px;
    }
    .unit-price { font-size: 28px; font-weight: 900; color: #1e5eff; display: flex; align-items: baseline; gap: 4px; }
    .price-symbol { font-size: 16px; color: #64748b; font-weight: 600; }
    
    .date-badge { 
        display: inline-flex; align-items: center; gap: 8px; background: white; 
        padding: 8px 14px; border-radius: 12px; color: #64748b; border: 1px solid #e2e8f0;
        font-size: 13px; font-weight: 600; margin-top: 15px;
    }

    /* Status Badges */
    .badge-status { 
        padding: 10px 20px; border-radius: 50px; font-size: 12px; font-weight: 800; 
        position: absolute; top: 35px; right: 35px; display: flex; align-items: center; gap: 8px;
        text-transform: uppercase; letter-spacing: 0.5px;
    }
    .status-pending { background: #fff7ed; color: #ea580c; border: 1px solid #ffedd5; }
    .status-disetujui { background: #f0fdf4; color: #16a34a; border: 1px solid #dcfce7; }
    .status-ditolak { background: #fef2f2; color: #dc2626; border: 1px solid #fee2e2; }
    .dot { width: 8px; height: 8px; border-radius: 50%; }
    .status-pending .dot { background: #ea580c; box-shadow: 0 0 0 4px rgba(234, 88, 12, 0.1); }
    .status-disetujui .dot { background: #16a34a; box-shadow: 0 0 0 4px rgba(22, 163, 74, 0.1); }
    .status-ditolak .dot { background: #dc2626; box-shadow: 0 0 0 4px rgba(220, 38, 38, 0.1); }

    /* Progress Banner */
    .notif-banner { 
        background: #f0fdf4; padding: 22px 35px; display: flex; align-items: center; 
        justify-content: space-between; border-top: 1px solid #dcfce7; 
    }
    .notif-content { display: flex; align-items: center; gap: 15px; }
    .check-circle { 
        background: #16a34a; color: white; width: 36px; height: 36px; 
        border-radius: 50%; display: flex; align-items: center; justify-content: center; font-size: 20px;
    }
    .btn-progress { 
        background: #16a34a; color: white; padding: 12px 26px; border-radius: 14px; 
        font-size: 13px; font-weight: 800; text-decoration: none; transition: 0.3s;
        display: flex; align-items: center; gap: 8px;
    }
    .btn-progress:hover { background: #15803d; transform: scale(1.03); color: white; box-shadow: 0 4px 12px rgba(22, 163, 74, 0.2); }

    /* Responsive */
    @media (max-width: 768px) {
        .booking-card { flex-direction: column; align-items: flex-start; gap: 20px; }
        .unit-img-wrapper { width: 100%; height: 200px; }
        .badge-status { position: static; margin-bottom: 15px; align-self: flex-start; }
        .notif-banner { flex-direction: column; gap: 20px; text-align: center; }
        .notif-content { flex-direction: column; }
    }
</style>

<div class="content-wrapper">
    {{-- HEADER --}}
    <div class="header-section">
        <div>
            <div class="title-box">Booking Saya</div>
            <div class="subtitle">Kelola dan pantau progres unit rumah impian Anda</div>
        </div>
        <a href="{{ route('customer.booking.create') }}" class="btn-book">
            <i class="bi bi-plus-lg"></i> Booking Unit Baru
        </a>
    </div>

    {{-- FLASH MESSAGES --}}
    @if(session('success'))
        <div class="alert alert-success border-0 shadow-sm rounded-4 p-3 mb-4 d-flex align-items-center" style="background: #f0fdf4; color: #16a34a;">
            <i class="bi bi-check-circle-fill fs-4 me-3"></i>
            <div>
                <strong class="d-block">Berhasil!</strong>
                <small>{{ session('success') }}</small>
            </div>
            <button type="button" class="btn-close ms-auto" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif

    {{-- LIST BOOKING --}}
    @forelse ($bookings as $b)
    <div class="booking-wrapper">
        <div class="booking-card">
            {{-- Image Logic (Cloudinary) --}}
            <div class="unit-img-wrapper">
                @php
                    $imgUrl = $b->unit->project->gambar ?? null;
                    $isCloudinary = Str::startsWith($imgUrl, ['http://', 'https://']);
                    $finalImg = $isCloudinary ? $imgUrl : ($imgUrl ? asset('storage/' . $imgUrl) : null);
                @endphp

                @if($finalImg)
                    <img src="{{ $finalImg }}" class="unit-img" alt="Proyek" 
                         onerror="this.src='https://images.unsplash.com/photo-1560518883-ce09059eeffa?q=80&w=400'">
                @else
                    <img src="https://images.unsplash.com/photo-1580587767303-93663300851c?q=80&w=400" class="unit-img" alt="Default">
                @endif
            </div>

            <div class="card-info">
                <div class="project-title">{{ $b->unit->project->nama_proyek ?? 'Proyek Tidak Diketahui' }}</div>
                
                <div class="unit-badge-info">
                    <i class="bi bi-geo-alt-fill" style="color: #1e5eff;"></i>
                    Blok {{ $b->unit->block ?? '-' }} No. {{ $b->unit->no_unit ?? '-' }} • Tipe {{ $b->unit->tipe->nama_tipe ?? 'N/A' }}
                </div>

                <div class="unit-price">
                    <span class="price-symbol">Rp</span>{{ number_format($b->unit->tipe->harga ?? 0, 0, ',', '.') }}
                </div>

                <div class="date-badge">
                    <i class="bi bi-calendar-check text-primary"></i>
                    Diajukan: <span>{{ \Carbon\Carbon::parse($b->tanggal_booking)->translatedFormat('l, d M Y') }}</span>
                </div>
            </div>

            {{-- Status Badge --}}
            @php $statusClass = strtolower($b->status ?? 'pending'); @endphp
            <div class="badge-status status-{{ $statusClass }}">
                <span class="dot"></span>
                {{ strtoupper($b->status ?? 'pending') }}
            </div>
        </div>

        {{-- Progress Banner (Hanya Jika Disetujui) --}}
        @if($statusClass == 'disetujui')
        <div class="notif-banner">
            <div class="notif-content">
                <div class="check-circle"><i class="bi bi-house-heart"></i></div>
                <div>
                    <div style="color: #065f46; font-size: 15px; font-weight: 800;">Selamat! Unit Anda Sudah Disetujui</div>
                    <div style="color: #047857; font-size: 13px; font-weight: 500;">Pantau tahapan pembangunan rumah Anda secara real-time.</div>
                </div>
            </div>
            <a href="{{ route('customer.progres.index') }}" class="btn-progress">
                <i class="bi bi-bar-chart-steps"></i> LIHAT PROGRES RUMAH
            </a>
        </div>
        @elseif($statusClass == 'pending')
        <div class="notif-banner" style="background: #fffbeb; border-color: #fef3c7;">
            <div class="notif-content">
                <div class="check-circle" style="background: #f59e0b;"><i class="bi bi-hourglass-split"></i></div>
                <div>
                    <div style="color: #92400e; font-size: 15px; font-weight: 800;">Menunggu Verifikasi Admin</div>
                    <div style="color: #b45309; font-size: 13px; font-weight: 500;">Admin kami akan memeriksa dokumen Anda dalam 1x24 jam.</div>
                </div>
            </div>
        </div>
        @endif
    </div>
    @empty
    <div class="empty-state">
        <div style="font-size: 100px; margin-bottom: 20px;">🏡</div>
        <h3 style="font-weight: 800; color: #1e293b; font-size: 24px;">Belum Ada Booking</h3>
        <p style="color: #64748b; max-width: 420px; margin: 0 auto 30px; line-height: 1.6;">
            Temukan berbagai pilihan unit hunian terbaik dengan fasilitas lengkap hanya di Kedaton.
        </p>
        <a href="{{ route('customer.booking.create') }}" class="btn-book">
            Mulai Cari Unit Sekarang
        </a>
    </div>
    @endforelse
</div>

@endsection