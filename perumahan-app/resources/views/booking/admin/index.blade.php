@extends('dashboard.admin')

@section('content')

<style>
    .booking-title { font-size: 24px; font-weight: 800; color: #1a202c; margin-bottom: 4px; letter-spacing: -0.5px; }
    .booking-sub { color: #718096; font-size: 14px; margin-bottom: 30px; }
    
    .table-header {
        background: #f8fafc;
        padding: 18px 25px;
        border-radius: 15px;
        border: 1px solid #e2e8f0;
        font-weight: 800;
        font-size: 11px;
        display: grid;
        grid-template-columns: 1.5fr 1.5fr 1fr 1fr 1fr 150px;
        color: #64748b;
        text-transform: uppercase;
        letter-spacing: 1px;
    }

    .table-row {
        background: white;
        padding: 20px 25px;
        border-radius: 15px;
        border: 1px solid #e2e8f0;
        margin-top: 12px;
        font-size: 14px;
        display: grid;
        grid-template-columns: 1.5fr 1.5fr 1fr 1fr 1fr 150px;
        align-items: center;
        transition: 0.2s;
    }

    .table-row:hover { border-color: #1e5eff; box-shadow: 0 10px 15px -3px rgba(0, 0, 0, 0.05); }

    .badge-status {
        padding: 6px 12px;
        border-radius: 10px;
        font-size: 11px;
        font-weight: 800;
        display: inline-block;
        text-transform: uppercase;
    }
    .status-pending { background: #fff7ed; color: #c2410c; }
    .status-disetujui { background: #f0fdf4; color: #15803d; }
    .status-ditolak { background: #fef2f2; color: #b91c1c; }

    .btn-action { padding: 8px; border-radius: 10px; font-weight: 700; border: none; cursor: pointer; transition: 0.3s; color: white; display: flex; align-items: center; justify-content: center; }
    .btn-approve { background: #10b981; }
    .btn-approve:hover { background: #059669; transform: scale(1.05); }
    .btn-reject { background: #ef4444; }
    .btn-reject:hover { background: #dc2626; transform: scale(1.05); }
</style>

<div class="booking-title">Manajemen Booking</div>
<div class="booking-sub">Validasi dan kelola permohonan unit dari customer</div>

@if(session('success'))
    <div style="background: #f0fdf4; color: #15803d; padding: 15px; border-radius: 12px; margin-bottom: 20px; font-weight: 700; border: 1px solid #c6f6d5;">
        {{ session('success') }}
    </div>
@endif

<div class="table-header">
    <div>Customer</div>
    <div>Proyek & Unit</div>
    <div>Tanggal Booking</div>
    <div>Dokumen KTP</div>
    <div>Status</div>
    <div>Aksi</div>
</div>

@forelse($bookings as $booking)
    <div class="table-row">
        <div>
            {{-- Menggunakan null coalescing untuk menghindari error property on null --}}
            <div style="font-weight: 800; color: #1a202c;">{{ $booking->user->name ?? $booking->nama ?? 'N/A' }}</div>
            <div style="font-size: 12px; color: #94a3b8;">{{ $booking->user->email ?? '-' }}</div>
        </div>

        <div>
            <div style="font-weight: 700; color: #1e5eff;">{{ $booking->unit->project->nama_proyek ?? 'Proyek N/A' }}</div>
            <div style="font-size: 12px; color: #64748b; font-weight: 600;">Blok {{ $booking->unit->block ?? '-' }} No. {{ $booking->unit->no_unit ?? '-' }}</div>
        </div>

        <div style="font-weight: 600; color: #475569;">
            {{ \Carbon\Carbon::parse($booking->tanggal_booking)->translatedFormat('d M Y') }}
        </div>

        <div>
            @if($booking->dokumen)
                <a href="{{ asset('storage/'.$booking->dokumen) }}" target="_blank" style="color: #1e5eff; font-weight: 800; text-decoration: none; font-size: 12px;">
                    <i class="fas fa-file-pdf"></i> Lihat KTP
                </a>
            @else
                <span style="color: #cbd5e1; font-style: italic;">Tidak ada berkas</span>
            @endif
        </div>

        <div>
            <span class="badge-status status-{{ strtolower($booking->status) }}">
                ● {{ $booking->status }}
            </span>
        </div>

        <div>
            @if($booking->status == 'pending')
                <div style="display: flex; gap: 8px;">
                    {{-- FORM SETUJU --}}
                    <form action="{{ route('admin.booking.updateStatus', $booking->id) }}" method="POST">
                        @csrf
                        @method('PUT') {{-- Pastikan ini PUT sesuai web.php --}}
                        <input type="hidden" name="status" value="disetujui">
                        <button type="submit" class="btn-action btn-approve" title="Setujui" onclick="return confirm('Setujui booking ini?')">
                            <i class="fas fa-check"></i>
                        </button>
                    </form>

                    {{-- FORM TOLAK --}}
                    <form action="{{ route('admin.booking.updateStatus', $booking->id) }}" method="POST">
                        @csrf
                        @method('PUT') {{-- Pastikan ini PUT sesuai web.php --}}
                        <input type="hidden" name="status" value="ditolak">
                        <button type="submit" class="btn-action btn-reject" title="Tolak" onclick="return confirm('Tolak booking ini?')">
                            <i class="fas fa-times"></i>
                        </button>
                    </form>
                </div>
            @else
                <span style="font-size: 11px; font-weight: 700; color: #94a3b8; text-transform: uppercase;">Selesai</span>
            @endif
        </div>
    </div>
@empty
    <div class="table-row" style="display: block; text-align: center; padding: 40px;">
        <div style="color: #94a3b8; font-weight: 600;">Belum ada data booking yang masuk.</div>
    </div>
@endforelse

@endsection