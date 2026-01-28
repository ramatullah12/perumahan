@extends('layout.admin')

@section('content')
<div class="booking-admin-container" style="padding: clamp(15px, 4vw, 30px); position: relative; background: #f8fafc; min-height: 100vh;">
    
    <style>
        .booking-title { font-size: 28px; font-weight: 900; color: #1e293b; margin-bottom: 4px; letter-spacing: -1px; }
        .booking-sub { color: #64748b; font-size: 14px; margin-bottom: 30px; font-weight: 500; }
        
        /* Layout Grid yang Presisi */
        .grid-layout {
            display: grid;
            grid-template-columns: 1.5fr 1.5fr 1fr 1.2fr 1fr 180px;
            gap: 15px;
            align-items: center;
        }

        .table-header {
            background: white;
            padding: 18px 25px;
            border-radius: 16px;
            border: 1px solid #e2e8f0;
            font-weight: 800;
            font-size: 11px;
            color: #94a3b8;
            text-transform: uppercase;
            letter-spacing: 1px;
            margin-bottom: 15px;
        }

        .table-row {
            background: white;
            padding: 20px 25px;
            border-radius: 16px;
            border: 1px solid #e2e8f0;
            margin-bottom: 12px;
            font-size: 14px;
            transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
        }

        .table-row:hover { 
            border-color: #1e5eff; 
            box-shadow: 0 10px 25px -5px rgba(30, 94, 255, 0.1);
            transform: translateY(-2px);
        }

        /* Status Badge Styling */
        .badge-status {
            padding: 6px 12px;
            border-radius: 10px;
            font-size: 10px;
            font-weight: 800;
            display: inline-flex;
            align-items: center;
            gap: 6px;
            text-transform: uppercase;
        }
        .status-pending { background: #fff7ed; color: #c2410c; border: 1px solid #ffedd5; }
        .status-disetujui { background: #f0fdf4; color: #15803d; border: 1px solid #dcfce7; }
        .status-ditolak { background: #fef2f2; color: #b91c1c; border: 1px solid #fee2e2; }

        /* Action Buttons */
        .btn-action { 
            width: 38px; height: 38px; border-radius: 12px; 
            border: none; cursor: pointer; transition: 0.3s; 
            color: white; display: flex; align-items: center; 
            justify-content: center; text-decoration: none; 
        }
        .btn-approve { background: #10b981; box-shadow: 0 4px 10px rgba(16, 185, 129, 0.2); }
        .btn-approve:hover { background: #059669; transform: scale(1.1); }
        
        .btn-reject { background: #ef4444; box-shadow: 0 4px 10px rgba(239, 68, 68, 0.2); }
        .btn-reject:hover { background: #dc2626; transform: scale(1.1); }
        
        .btn-revoke { 
            background: #f1f5f9; color: #64748b; width: auto; 
            padding: 0 15px; height: 36px; font-size: 10px; 
            font-weight: 800; gap: 8px; border-radius: 10px;
            border: 1px solid #e2e8f0;
        }
        .btn-revoke:hover { background: #e2e8f0; color: #1e293b; }

        .btn-cloud {
            background: #eff6ff;
            color: #1e5eff;
            font-weight: 800;
            text-decoration: none;
            font-size: 11px;
            display: inline-flex;
            align-items: center;
            gap: 6px;
            padding: 6px 12px;
            border-radius: 8px;
            border: 1px solid #dbeafe;
            transition: 0.2s;
        }
        .btn-cloud:hover { background: #1e5eff; color: white; }

        .form-hidden { display: none !important; }

        @media (max-width: 1024px) {
            .grid-layout { grid-template-columns: 1fr 1fr; }
            .table-header { display: none; }
            .action-cell { grid-column: span 2; justify-content: flex-start; margin-top: 15px; border-top: 1px solid #f1f5f9; padding-top: 15px; }
        }
    </style>

    <div class="booking-title">Manajemen Booking</div>
    <div class="booking-sub">Validasi dokumen via Cloudinary & kontrol akses unit customer</div>

    {{-- Header Tabel --}}
    <div class="table-header grid-layout">
        <div>Customer</div>
        <div>Proyek & Unit</div>
        <div>Tanggal</div>
        <div>Dokumen (Cloud)</div>
        <div>Status</div>
        <div style="text-align: right;">Aksi</div>
    </div>

    @forelse($bookings as $booking)
        <div class="table-row grid-layout">
            {{-- Info Customer --}}
            <div>
                <div style="font-weight: 800; color: #1e293b; font-size: 15px;">{{ $booking->user->name ?? $booking->nama ?? 'N/A' }}</div>
                <div style="font-size: 12px; color: #94a3b8; display: flex; align-items: center; gap: 5px;">
                    <i class="far fa-envelope"></i> {{ $booking->user->email ?? '-' }}
                </div>
            </div>

            {{-- Info Proyek --}}
            <div>
                <div style="font-weight: 700; color: #1e5eff;">{{ $booking->unit->project->nama_proyek ?? 'N/A' }}</div>
                <div style="font-size: 12px; color: #64748b; font-weight: 600;">
                    <span style="background: #f1f5f9; padding: 2px 6px; border-radius: 4px;">Blok {{ $booking->unit->block ?? '-' }}</span> 
                    No. {{ $booking->unit->no_unit ?? '-' }}
                </div>
            </div>

            {{-- Tanggal --}}
            <div style="font-weight: 600; color: #475569;">
                <i class="far fa-calendar-alt me-1" style="color: #cbd5e1;"></i>
                {{ \Carbon\Carbon::parse($booking->tanggal_booking)->translatedFormat('d M Y') }}
            </div>

            {{-- Dokumen dengan Deteksi Cloudinary --}}
            <div>
                @php
                    $path = $booking->dokumen;
                    // Cek apakah string diawali dengan http (Cloudinary) atau path lokal
                    $isExternal = Str::startsWith($path, ['http://', 'https://']);
                    $finalUrl = $isExternal ? $path : asset('storage/' . $path);
                @endphp

                @if($path)
                    <a href="{{ $finalUrl }}" target="_blank" class="btn-cloud">
                        <i class="{{ $isExternal ? 'fas fa-cloud' : 'fas fa-file-pdf' }}"></i>
                        {{ $isExternal ? 'BUKA CLOUDINARY' : 'KTP.PDF' }}
                    </a>
                @else
                    <span style="color: #cbd5e1; font-size: 12px; font-style: italic;">
                        <i class="fas fa-exclamation-circle me-1"></i>Belum Upload
                    </span>
                @endif
            </div>

            {{-- Status --}}
            <div>
                <span class="badge-status status-{{ strtolower($booking->status) }}">
                    <i class="fas fa-circle" style="font-size: 6px;"></i> {{ $booking->status }}
                </span>
            </div>

            {{-- Aksi --}}
            <div class="action-cell" style="display: flex; gap: 8px; justify-content: flex-end;">
                @if($booking->status == 'pending')
                    <button type="button" onclick="confirmAction('{{ $booking->id }}', 'approve', '{{ $booking->user->name ?? $booking->nama }}')" class="btn-action btn-approve" title="Setujui">
                        <i class="fas fa-check"></i>
                    </button>
                    <form id="form-approve-{{ $booking->id }}" action="{{ route('admin.booking.updateStatus', $booking->id) }}" method="POST" class="form-hidden">
                        @csrf @method('PUT')
                        <input type="hidden" name="status" value="disetujui">
                    </form>

                    <button type="button" onclick="confirmAction('{{ $booking->id }}', 'reject', '{{ $booking->user->name ?? $booking->nama }}')" class="btn-action btn-reject" title="Tolak">
                        <i class="fas fa-times"></i>
                    </button>
                    <form id="form-reject-{{ $booking->id }}" action="{{ route('admin.booking.updateStatus', $booking->id) }}" method="POST" class="form-hidden">
                        @csrf @method('PUT')
                        <input type="hidden" name="status" value="ditolak">
                    </form>
                @elseif($booking->status == 'disetujui')
                    <button type="button" onclick="confirmAction('{{ $booking->id }}', 'revoke', '{{ $booking->user->name ?? $booking->nama }}')" class="btn-action btn-revoke">
                        <i class="fas fa-undo-alt"></i> CABUT AKSES
                    </button>
                    <form id="form-revoke-{{ $booking->id }}" action="{{ route('admin.booking.updateStatus', $booking->id) }}" method="POST" class="form-hidden">
                        @csrf @method('PUT')
                        <input type="hidden" name="status" value="pending">
                    </form>
                @else
                    <div style="display: flex; flex-direction: column; align-items: flex-end;">
                        <span style="font-size: 10px; font-weight: 800; color: #94a3b8; letter-spacing: 1px;">SELESAI</span>
                        <span style="font-size: 9px; color: #cbd5e1;">Data Terarsip</span>
                    </div>
                @endif
            </div>
        </div>
    @empty
        <div style="background: white; border-radius: 20px; padding: 60px; text-align: center; border: 2px dashed #e2e8f0;">
            <img src="https://cdn-icons-png.flaticon.com/512/7486/7486744.png" style="width: 80px; opacity: 0.2; margin-bottom: 20px;">
            <div style="color: #94a3b8; font-weight: 600;">Belum ada data booking yang masuk.</div>
        </div>
    @endforelse
</div>

{{-- Scripting SweetAlert2 --}}
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
    function confirmAction(id, action, name) {
        let config = {
            approve: { title: 'Setujui Pesanan?', text: `Konfirmasi dokumen & beri akses dashboard untuk ${name}`, icon: 'success', color: '#10b981' },
            reject: { title: 'Tolak Pesanan?', text: `Dokumen tidak valid atau unit batal dipesan oleh ${name}`, icon: 'error', color: '#ef4444' },
            revoke: { title: 'Cabut Akses?', text: `Batalkan status disetujui untuk ${name}`, icon: 'warning', color: '#64748b' }
        };

        const selected = config[action];

        Swal.fire({
            title: selected.title,
            text: selected.text,
            icon: selected.icon,
            showCancelButton: true,
            confirmButtonColor: selected.color,
            cancelButtonColor: '#94a3b8',
            confirmButtonText: 'Ya, Proses',
            cancelButtonText: 'Batal',
            reverseButtons: true,
            customClass: {
                popup: 'swal-rounded'
            }
        }).then((result) => {
            if (result.isConfirmed) {
                Swal.fire({
                    title: 'Sinkronisasi Cloud...',
                    allowOutsideClick: false,
                    didOpen: () => { Swal.showLoading(); }
                });
                document.getElementById(`form-${action}-${id}`).submit();
            }
        });
    }
</script>

<style>
    .swal-rounded { border-radius: 24px !important; }
</style>

{{-- Notifikasi Sukses --}}
@if(session('success'))
<script>
    Swal.fire({
        toast: true,
        position: 'top-end',
        icon: 'success',
        title: "{{ session('success') }}",
        showConfirmButton: false,
        timer: 3000,
        timerProgressBar: true
    });
</script>
@endif
@endsection