@extends('layout.customer')

@section('content')

<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.2/font/bootstrap-icons.css">

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
    
    /* Image Handling */
    .unit-img-wrapper { 
        width: 220px; height: 150px; border-radius: 20px; overflow: hidden; 
        background: #f1f5f9; flex-shrink: 0; border: 1px solid #edf2f7; 
    }
    .unit-img { width: 100%; height: 100%; object-fit: cover; transition: 0.5s; }
    .booking-wrapper:hover .unit-img { transform: scale(1.05); }

    /* Text Content */
    .project-title { font-size: 24px; font-weight: 800; color: #1e293b; margin-bottom: 6px; }
    .unit-info { color: #64748b; font-size: 16px; font-weight: 600; margin-bottom: 12px; }
    .type-highlight { color: #1e5eff; font-weight: 700; }
    .unit-price { font-size: 26px; font-weight: 900; color: #1e5eff; }
    
    .date-badge { 
        display: inline-flex; align-items: center; gap: 8px; background: #f8fafc; 
        padding: 6px 12px; border-radius: 10px; color: #64748b; font-size: 13px; font-weight: 500; margin-top: 15px;
    }

    /* Status Badges */
    .badge-status { 
        padding: 10px 20px; border-radius: 50px; font-size: 12px; font-weight: 800; 
        position: absolute; top: 35px; right: 35px; display: flex; align-items: center; gap: 8px;
    }
    .status-pending { background: #fff7ed; color: #ea580c; border: 1px solid #ffedd5; }
    .status-disetujui { background: #f0fdf4; color: #16a34a; border: 1px solid #dcfce7; }
    .status-ditolak { background: #fef2f2; color: #dc2626; border: 1px solid #fee2e2; }
    .dot { width: 8px; height: 8px; border-radius: 50%; }
    .status-pending .dot { background: #ea580c; }
    .status-disetujui .dot { background: #16a34a; }
    .status-ditolak .dot { background: #dc2626; }

    /* Success Banner */
    .notif-banner { 
        background: #f0fdf4; padding: 20px 35px; display: flex; align-items: center; 
        justify-content: space-between; border-top: 1px solid #dcfce7; 
    }
    .notif-content { display: flex; align-items: center; gap: 15px; }
    .check-circle { 
        background: #16a34a; color: white; width: 32px; height: 32px; 
        border-radius: 50%; display: flex; align-items: center; justify-content: center; font-size: 18px;
    }
    .btn-progress { 
        background: #16a34a; color: white; padding: 12px 24px; border-radius: 14px; 
        font-size: 12px; font-weight: 800; text-decoration: none; transition: 0.3s;
    }
    .btn-progress:hover { background: #15803d; transform: scale(1.02); color: white; }

    /* Empty State */
    .empty-state { 
        text-align: center; padding: 100px 20px; background: white; 
        border-radius: 30px; border: 2px dashed #e2e8f0; 
    }
</style>

<div class="content-wrapper">
    {{-- HEADER --}}
    <div class="header-section">
        <div>
            <div class="title-box">Booking Saya</div>
            <div class="subtitle">Pantau status pengajuan unit rumah impian Anda</div>
        </div>
        <a href="{{ route('customer.booking.create') }}" class="btn-book">
            <i class="bi bi-plus-lg"></i> Booking Unit Baru
        </a>
    </div>

    {{-- ALERT SUCCESS --}}
    @if(session('success'))
        <div class="alert alert-success border-0 shadow-sm rounded-4 p-3 mb-4 d-flex align-items-center">
            <i class="bi bi-check-circle-fill fs-4 me-3"></i>
            <div>
                <strong class="d-block">Berhasil!</strong>
                <small>{{ session('success') }}</small>
            </div>
        </div>
    @endif

    {{-- LIST BOOKING --}}
    @forelse ($bookings as $b)
    <div class="booking-wrapper">
        <div class="booking-card">
            <div class="unit-img-wrapper">
                @php
                    $imgUrl = $b->unit->project->gambar ?? null;
                @endphp

                @if($imgUrl)
                    @if(Str::startsWith($imgUrl, ['http://', 'https://']))
                        <img src="{{ $imgUrl }}" class="unit-img" alt="Proyek">
                    @else
                        <img src="{{ asset('storage/' . $imgUrl) }}" class="unit-img" alt="Proyek" 
                             onerror="this.src='https://images.unsplash.com/photo-1560518883-ce09059eeffa?q=80&w=400'">
                    @endif
                @else
                    <img src="https://images.unsplash.com/photo-1580587767303-93663300851c?q=80&w=400" class="unit-img" alt="Default">
                @endif
            </div>

            <div class="card-info">
                <div class="project-title">{{ $b->unit->project->nama_proyek ?? 'Proyek Kedaton' }}</div>
                <div class="unit-info">
                    Blok {{ $b->unit->block ?? '-' }} No. {{ $b->unit->no_unit ?? '-' }} • 
                    <span class="type-highlight">Tipe {{ $b->unit->tipe->nama_tipe ?? 'N/A' }}</span>
                </div>

                <div class="unit-price">
                    Rp {{ number_format($b->unit->tipe->harga ?? 0, 0, ',', '.') }}
                </div>

                <div class="date-badge">
                    <i class="bi bi-calendar3"></i>
                    Diajukan pada: <span>{{ \Carbon\Carbon::parse($b->tanggal_booking)->translatedFormat('l, d F Y') }}</span>
                </div>
            </div>

            <div class="badge-status status-{{ strtolower($b->status ?? 'pending') }}">
                <span class="dot"></span>
                {{ strtoupper($b->status ?? 'pending') }}
            </div>
        </div>

        {{-- Progress Banner (Hanya muncul jika disetujui) --}}
        @if($b->status == 'disetujui')
        <div class="notif-banner">
            <div class="notif-content">
                <div class="check-circle"><i class="bi bi-check-lg"></i></div>
                <div>
                    <div style="color: #065f46; font-size: 14px; font-weight: 800;">Booking Disetujui!</div>
                    <div style="color: #047857; font-size: 12px; font-weight: 500;">Rumah Anda sedang dalam tahap persiapan. Klik tombol untuk pantau progres.</div>
                </div>
            </div>
            <a href="{{ route('customer.progres.index') }}" class="btn-progress">
                LIHAT PROGRES RUMAH
            </a>
        </div>
        @endif
    </div>
    @empty
    <div class="empty-state">
        <div style="font-size: 80px; margin-bottom: 20px;">🏡</div>
        <h3 style="font-weight: 800; color: #1e293b;">Belum Ada Riwayat Booking</h3>
        <p style="color: #64748b; max-width: 400px; margin: 0 auto 25px;">Sepertinya Anda belum memesan unit rumah. Temukan hunian impian Anda sekarang!</p>
        <a href="{{ route('customer.booking.create') }}" class="btn-book">Cari Unit Rumah</a>
    </div>
    @endforelse
</div>
@endsection