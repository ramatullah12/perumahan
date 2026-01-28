@extends('layout.customer')

@section('content')
<div style="padding: 30px; font-family: 'Inter', sans-serif; background: #f8fafc; min-height: 100vh;">
    {{-- HEADER --}}
    <div style="margin-bottom: 35px; display: flex; flex-wrap: wrap; justify-content: space-between; align-items: center; gap: 15px;">
        <div>
            <h2 style="font-size: 28px; font-weight: 900; color: #1e293b; letter-spacing: -1px; margin: 0;">Progres Unit Saya</h2>
            <p style="color: #64748b; font-weight: 500; margin-top: 5px;">Pantau real-time pembangunan rumah impian Anda secara akurat</p>
        </div>
        <div style="background: white; padding: 10px 22px; border-radius: 16px; border: 1px solid #e2e8f0; box-shadow: 0 4px 6px -1px rgba(0,0,0,0.03); display: flex; align-items: center; gap: 8px;">
            <i class="fas fa-clock" style="color: #3b82f6; font-size: 12px;"></i>
            <span style="font-size: 12px; font-weight: 800; color: #475569; text-transform: uppercase; letter-spacing: 0.5px;">Terakhir Update: {{ date('d M Y') }}</span>
        </div>
    </div>

    @forelse($bookings as $b)
        @php
            // Mengambil nilai progres terbaru. Jika kolom unit kosong, cek histori terbaru.
            $persentase = $b->unit->progres_pembangunan ?? ($b->unit->latestProgres->persentase ?? 0);
            $persentase = max(0, min(100, $persentase));
            $latestHistory = $b->unit->latestProgres ?? null; 
        @endphp

        <div style="background: white; border-radius: 32px; padding: 40px; border: 1px solid #e2e8f0; margin-bottom: 35px; box-shadow: 0 25px 50px -12px rgba(0,0,0,0.04); position: relative; overflow: hidden;">
            
            {{-- AKSEN STATUS SAMPING --}}
            <div style="position: absolute; top: 0; left: 0; width: 8px; height: 100%; background: linear-gradient(to bottom, #1e5eff, #3b82f6);"></div>

            <div style="display: flex; flex-wrap: wrap; justify-content: space-between; align-items: flex-start; margin-bottom: 35px; gap: 20px;">
                <div>
                    <h4 style="font-weight: 900; color: #1e5eff; font-size: 24px; margin: 0; letter-spacing: -0.5px;">{{ $b->unit->project->nama_proyek ?? 'Proyek Perumahan' }}</h4>
                    <div style="display: flex; flex-wrap: wrap; align-items: center; gap: 12px; margin-top: 10px;">
                        <span style="background: #eff6ff; color: #1e5eff; font-weight: 800; font-size: 14px; padding: 4px 12px; border-radius: 8px;">Blok {{ $b->unit->block }} No. {{ $b->unit->no_unit }}</span>
                        <span style="color: #64748b; font-weight: 600; font-size: 15px;">Tipe {{ $b->unit->tipe->nama_tipe ?? 'N/A' }}</span>
                    </div>
                </div>
                <div>
                    @php
                        $statusColor = $b->status == 'Approve' ? '#166534' : ($b->status == 'Pending' ? '#9a3412' : '#1e293b');
                        $statusBg = $b->status == 'Approve' ? '#f0fdf4' : ($b->status == 'Pending' ? '#fff7ed' : '#f8fafc');
                    @endphp
                    <span style="background: {{ $statusBg }}; color: {{ $statusColor }}; padding: 10px 20px; border-radius: 12px; font-size: 12px; font-weight: 900; text-transform: uppercase; border: 1px solid rgba(0,0,0,0.05); display: inline-flex; align-items: center; gap: 8px;">
                        <i class="fas fa-certificate"></i> {{ $b->status }}
                    </span>
                </div>
            </div>

            {{-- PROGRESS BAR AREA --}}
            <div style="background: #f8fafc; padding: 30px; border-radius: 24px; border: 1px solid #f1f5f9; margin-bottom: 35px;">
                <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 15px;">
                    <span style="color: #475569; font-weight: 800; font-size: 14px; text-transform: uppercase; letter-spacing: 1px;">
                        Tahap: <span style="color: #1e5eff;">{{ $latestHistory->tahap ?? 'Persiapan' }}</span>
                    </span>
                    <span style="color: #1e5eff; font-weight: 900; font-size: 20px;">{{ $persentase }}%</span>
                </div>
                
                {{-- TRACK BAR --}}
                <div style="width: 100%; background: #e2e8f0; height: 16px; border-radius: 20px; overflow: hidden; position: relative;">
                    <div style="width: {{ $persentase }}%; 
                                background: linear-gradient(90deg, #1e5eff 0%, #60a5fa 100%); 
                                height: 100%; 
                                border-radius: 20px;
                                transition: width 1.5s ease-in-out;
                                box-shadow: 0 0 15px rgba(30, 94, 255, 0.2);">
                    </div>
                </div>
            </div>

            {{-- INFO FOOTER --}}
            <div class="grid-container" style="display: grid; grid-template-columns: repeat(auto-fit, minmax(300px, 1fr)); gap: 30px;">
                
                {{-- CATATAN LAPANGAN --}}
                <div>
                    <h5 style="font-size: 15px; font-weight: 900; color: #1e293b; margin-bottom: 15px; display: flex; align-items: center; gap: 8px;">
                        <i class="fas fa-clipboard-check" style="color: #1e5eff;"></i> Catatan Pembangunan:
                    </h5>
                    <div style="background: #ffffff; padding: 25px; border-radius: 20px; border: 1px solid #f1f5f9; min-height: 120px; box-shadow: 0 4px 6px -1px rgba(0,0,0,0.02);">
                        <p style="margin: 0; font-size: 14px; color: #64748b; line-height: 1.6;">
                            @if($latestHistory && $latestHistory->keterangan)
                                {{ $latestHistory->keterangan }}
                            @else
                                <span style="font-style: italic; color: #94a3b8;">Tim kami sedang memproses tahap awal pembangunan. Informasi detail pengerjaan akan segera diperbarui di sini.</span>
                            @endif
                        </p>
                    </div>
                </div>

                {{-- FOTO DOKUMENTASI --}}
                <div>
                    <h5 style="font-size: 15px; font-weight: 900; color: #1e293b; margin-bottom: 15px; display: flex; align-items: center; gap: 8px;">
                        <i class="fas fa-image" style="color: #1e5eff;"></i> Visual Progres:
                    </h5>
                    @if($latestHistory && $latestHistory->foto)
                        @php $fotoUrl = Str::startsWith($latestHistory->foto, 'http') ? $latestHistory->foto : asset('storage/' . $latestHistory->foto); @endphp
                        <a href="{{ $fotoUrl }}" target="_blank" style="text-decoration: none; display: block; position: relative; border-radius: 20px; overflow: hidden; border: 4px solid white; box-shadow: 0 10px 20px rgba(0,0,0,0.1);">
                            <img src="{{ $fotoUrl }}" 
                                 style="width: 100%; height: 200px; object-fit: cover;"
                                 alt="Dokumentasi Unit">
                            <div style="position: absolute; bottom: 0; left: 0; right: 0; padding: 10px; background: linear-gradient(transparent, rgba(0,0,0,0.6)); color: white; font-size: 10px; font-weight: 700; text-align: center; text-transform: uppercase;">
                                Klik Untuk Memperbesar
                            </div>
                        </a>
                    @else
                        <div style="width: 100%; height: 200px; background: #f8fafc; border: 2px dashed #e2e8f0; border-radius: 24px; display: flex; flex-direction: column; align-items: center; justify-content: center; color: #cbd5e1; text-align: center; padding: 20px;">
                            <i class="fas fa-drafting-compass" style="font-size: 32px; margin-bottom: 10px;"></i>
                            <span style="font-size: 12px; font-weight: 800; color: #94a3b8; text-transform: uppercase;">Belum Ada Foto Dokumentasi</span>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    @empty
        <div style="text-align: center; padding: 80px 20px; background: white; border-radius: 40px; border: 2px dashed #cbd5e1; margin-top: 20px;">
            <div style="font-size: 50px; margin-bottom: 20px;">🏗️</div>
            <h3 style="color: #1e293b; font-weight: 900; font-size: 22px; margin-bottom: 10px;">Data Pembangunan Belum Tersedia</h3>
            <p style="color: #64748b; font-size: 15px; max-width: 400px; margin: 0 auto; line-height: 1.6;">Setelah pengajuan booking Anda disetujui, progres fisik rumah Anda akan muncul secara otomatis di halaman ini.</p>
        </div>
    @endforelse
</div>

<style>
    @media (max-width: 768px) {
        .grid-container { grid-template-columns: 1fr !important; }
    }
</style>
@endsection