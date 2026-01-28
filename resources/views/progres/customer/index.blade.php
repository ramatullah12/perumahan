@extends('layout.customer')

@section('content')
<div style="padding: clamp(15px, 4vw, 30px); font-family: 'Inter', sans-serif; background: #f8fafc; min-height: 100vh;">
    
    {{-- HEADER --}}
    <div style="margin-bottom: 35px; display: flex; justify-content: space-between; align-items: center; flex-wrap: wrap; gap: 20px;">
        <div>
            <h2 style="font-size: clamp(22px, 5vw, 28px); font-weight: 900; color: #1e293b; letter-spacing: -1px; margin: 0;">Progres Unit Saya</h2>
            <p style="color: #64748b; font-weight: 500; margin-top: 5px; font-size: 14px;">Pantau real-time pembangunan rumah impian Anda secara akurat</p>
        </div>
        <div style="background: white; padding: 10px 22px; border-radius: 16px; border: 1px solid #e2e8f0; box-shadow: 0 4px 6px -1px rgba(0,0,0,0.03); display: flex; align-items: center; gap: 8px;">
            <i class="fas fa-sync-alt" style="color: #3b82f6; font-size: 12px;"></i>
            <span style="font-size: 12px; font-weight: 800; color: #475569; text-transform: uppercase; letter-spacing: 0.5px;">Update: {{ date('d M Y') }}</span>
        </div>
    </div>

    @forelse($bookings as $b)
        @php
            $unit = $b->unit;
            $persentase = $unit->progres ?? 0;
            $persentase = max(0, min(100, (int)$persentase));

            // Mengambil histori terbaru dari relasi yang sudah ada
            $latestHistory = $unit->latestProgres ?? null; 

            // LOGIKA PERBAIKAN GAMBAR:
            $fotoPath = $latestHistory->foto ?? null;
            $urlFinal = null;

            if ($fotoPath) {
                // Jika isinya link (http), jangan pakai asset('storage/'). Jika bukan link, pakai asset('storage/')
                $urlFinal = str_starts_with($fotoPath, 'http') ? $fotoPath : asset('storage/' . $fotoPath);
            }
        @endphp

        <div style="background: white; border-radius: 32px; padding: clamp(20px, 5vw, 40px); border: 1px solid #e2e8f0; margin-bottom: 35px; box-shadow: 0 25px 50px -12px rgba(0,0,0,0.04); position: relative; overflow: hidden;">
            
            <div style="position: absolute; top: 0; left: 0; width: 8px; height: 100%; background: linear-gradient(to bottom, #1e5eff, #3b82f6);"></div>

            <div style="display: flex; justify-content: space-between; align-items: flex-start; margin-bottom: 35px; flex-wrap: wrap; gap: 20px;">
                <div>
                    <h4 style="font-weight: 900; color: #1e5eff; font-size: clamp(20px, 4vw, 24px); margin: 0; letter-spacing: -0.5px;">
                        {{ $unit->project->nama_proyek ?? 'Proyek Kedaton' }}
                    </h4>
                    <div style="display: flex; align-items: center; gap: 12px; margin-top: 10px; flex-wrap: wrap;">
                        <span style="background: #eff6ff; color: #1e5eff; font-weight: 800; font-size: 14px; padding: 4px 12px; border-radius: 8px; border: 1px solid #dbeafe;">
                            Blok {{ $unit->block ?? '-' }} No. {{ $unit->no_unit ?? '-' }}
                        </span>
                        <span style="width: 5px; height: 5px; background: #cbd5e1; border-radius: 50%;" class="d-none d-sm-block"></span>
                        <span style="color: #64748b; font-weight: 600; font-size: 15px;">Tipe {{ $unit->tipe->nama_tipe ?? 'Standar' }}</span>
                    </div>
                </div>
                <div>
                    @php
                        $statusBg = '#f0fdf4'; $statusColor = '#166534'; $dotColor = '#22c55e';
                        if(strtolower($b->status) == 'pending') {
                            $statusBg = '#fff7ed'; $statusColor = '#9a3412'; $dotColor = '#f97316';
                        }
                    @endphp
                    <span style="background: {{ $statusBg }}; color: {{ $statusColor }}; padding: 12px 24px; border-radius: 16px; font-size: 13px; font-weight: 900; text-transform: uppercase; border: 1px solid rgba(0,0,0,0.05); display: inline-flex; align-items: center; gap: 10px;">
                        <span style="width: 10px; height: 10px; background: {{ $dotColor }}; border-radius: 50%; display: inline-block;"></span>
                        {{ $b->status }}
                    </span>
                </div>
            </div>

            <div style="background: #f8fafc; padding: clamp(20px, 4vw, 30px); border-radius: 24px; border: 1px solid #f1f5f9; margin-bottom: 35px;">
                <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 20px;">
                    <span style="color: #475569; font-weight: 800; font-size: 16px;">
                        Status: <span style="color: #1e5eff;">{{ $persentase == 100 ? 'Serah Terima Unit' : 'Tahap Pembangunan' }}</span>
                    </span>
                    <div style="text-align: right;">
                        <span style="color: #1e5eff; background: white; border: 1px solid #d1e9ff; padding: 6px 16px; border-radius: 12px; font-size: 14px; font-weight: 900; box-shadow: 0 2px 4px rgba(30, 94, 255, 0.05);">
                            {{ $persentase }}% Selesai
                        </span>
                    </div>
                </div>
                
                <div style="width: 100%; background: #e2e8f0; height: 22px; border-radius: 14px; overflow: hidden; box-shadow: inset 0 2px 4px rgba(0,0,0,0.05);">
                    <div style="width: {{ $persentase }}%; 
                                background: linear-gradient(90deg, #1e5eff 0%, #60a5fa 100%); 
                                height: 100%; 
                                border-radius: 14px;
                                transition: width 1.5s ease-out;
                                box-shadow: 0 4px 12px rgba(30, 94, 255, 0.3);
                                position: relative;">
                        <div style="position: absolute; top: 0; left: 0; right: 0; bottom: 0; background: linear-gradient(rgba(255,255,255,0.2), rgba(255,255,255,0));"></div>
                    </div>
                </div>
            </div>

            <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(300px, 1fr)); gap: 35px;">
                <div>
                    <h5 style="font-size: 15px; font-weight: 900; color: #1e293b; margin-bottom: 15px; display: flex; align-items: center; gap: 8px;">
                        <i class="fas fa-clipboard-list" style="color: #1e5eff;"></i> Catatan Pembangunan:
                    </h5>
                    <div style="background: #ffffff; padding: 25px; border-radius: 20px; border: 1px solid #f1f5f9; min-height: 120px; box-shadow: 0 4px 6px -1px rgba(0,0,0,0.02); display: flex; align-items: center;">
                        <p style="margin: 0; font-size: 15px; color: #64748b; line-height: 1.7; font-style: italic;">
                            "{{ $latestHistory->keterangan ?? 'Tim kami sedang memproses pembangunan di lokasi.' }}"
                        </p>
                    </div>
                </div>

                <div>
                    <h5 style="font-size: 15px; font-weight: 900; color: #1e293b; margin-bottom: 15px; display: flex; align-items: center; gap: 8px;">
                        <i class="fas fa-camera" style="color: #1e5eff;"></i> Dokumentasi Terbaru:
                    </h5>
                    <div style="position: relative;">
                        @if($urlFinal)
                            <a href="{{ $urlFinal }}" target="_blank" style="text-decoration: none; display: block; border-radius: 20px; overflow: hidden; border: 4px solid white; box-shadow: 0 15px 30px -5px rgba(0,0,0,0.12); transition: transform 0.3s ease;" onmouseover="this.style.transform='scale(1.02)'" onmouseout="this.style.transform='scale(1)'">
                                <img src="{{ $urlFinal }}" 
                                     style="width: 100%; height: 220px; object-fit: cover; display: block;"
                                     alt="Dokumentasi Unit">
                                <div style="position: absolute; bottom: 15px; right: 15px; background: rgba(0,0,0,0.5); color: white; padding: 5px 12px; border-radius: 8px; font-size: 11px; backdrop-filter: blur(4px);">
                                    <i class="fas fa-expand me-1"></i> Perbesar
                                </div>
                            </a>
                        @else
                            <div style="width: 100%; height: 220px; background: #f8fafc; border: 2px dashed #e2e8f0; border-radius: 24px; display: flex; flex-direction: column; align-items: center; justify-content: center; color: #cbd5e1;">
                                <i class="fas fa-hard-hat" style="font-size: 45px; margin-bottom: 12px; opacity: 0.5;"></i>
                                <span style="font-size: 13px; font-weight: 800; color: #94a3b8;">Belum Ada Foto Dokumentasi</span>
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    @empty
        <div style="text-align: center; padding: 100px 40px; background: white; border-radius: 48px; border: 3px dashed #e2e8f0; margin-top: 20px;">
            <p style="color: #64748b;">Belum Ada Data Progres</p>
        </div>
    @endforelse
</div>
@endsection