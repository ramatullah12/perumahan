@extends('layout.customer')

@section('content')
<style>
    .content-wrapper { padding: 30px; font-family: 'Inter', sans-serif; background: #f8fafc; }
    .header-section { margin-bottom: 30px; }
    .title-box { font-size: 28px; font-weight: 900; color: #1a202c; letter-spacing: -1px; }
    .subtitle { color: #718096; font-size: 14px; font-weight: 500; }
    
    .form-card { 
        background: white; 
        border-radius: 30px; 
        padding: 40px; 
        border: 1px solid #e2e8f0; 
        box-shadow: 0 20px 25px -5px rgba(0, 0, 0, 0.05); 
    }

    .form-group { margin-bottom: 25px; }
    .label-custom { display: block; text-transform: uppercase; font-size: 11px; font-weight: 800; color: #a0aec0; letter-spacing: 1.5px; margin-bottom: 10px; margin-left: 4px; }
    
    .input-custom { 
        width: 100%; 
        background: #f1f5f9; 
        border: 2px solid transparent; 
        border-radius: 18px; 
        padding: 16px 20px; 
        font-weight: 600; 
        color: #1a202c; 
        transition: 0.3s; 
        outline: none; 
    }
    
    input[type="file"].input-custom { padding: 12px 20px; cursor: pointer; background: #fff; border: 2px dashed #e2e8f0; }
    .input-custom:focus { background: white; border-color: #1e5eff; box-shadow: 0 0 0 4px rgba(30, 94, 255, 0.1); }
    
    .info-unit-box { 
        background: linear-gradient(135deg, #ebf4ff 0%, #e1efff 100%); 
        border-radius: 20px; 
        padding: 25px; 
        margin-bottom: 25px; 
        display: none; 
        border: 1px solid #d1e9ff;
        animation: fadeIn 0.4s ease-out;
    }

    @keyframes fadeIn { from { opacity: 0; transform: scale(0.98); } to { opacity: 1; transform: scale(1); } }

    .btn-submit { 
        width: 100%; 
        background: #1e5eff; 
        color: white; 
        padding: 18px; 
        border-radius: 20px; 
        font-size: 16px; 
        font-weight: 800; 
        text-transform: uppercase; 
        letter-spacing: 1px; 
        border: none; 
        cursor: pointer; 
        transition: 0.3s; 
        box-shadow: 0 10px 15px -3px rgba(30, 94, 255, 0.2); 
    }
    .btn-submit:hover { background: #0046ff; transform: translateY(-2px); box-shadow: 0 15px 20px -3px rgba(30, 94, 255, 0.3); }
    
    .alert-danger-custom { background: #fff5f5; color: #c53030; padding: 20px; border-radius: 20px; margin-bottom: 25px; border: 1px solid #feb2b2; }
</style>

<div class="content-wrapper">
    <div class="header-section">
        <div class="title-box">Booking Unit Baru</div>
        <div class="subtitle">Wujudkan hunian impian Anda dengan langkah mudah</div>
    </div>

    <div class="max-w-3xl mx-auto">
        {{-- Pesan Validasi & Error --}}
        @if ($errors->any() || session('error'))
            <div class="alert-danger-custom">
                <div style="display: flex; gap: 12px;">
                    <i class="fas fa-exclamation-circle" style="font-size: 20px; margin-top: 2px;"></i>
                    <div>
                        <strong style="display: block; margin-bottom: 5px;">Mohon Maaf, Terjadi Kesalahan:</strong>
                        <ul style="margin: 0; padding-left: 18px; font-size: 14px; font-weight: 600;">
                            @if(session('error')) <li>{{ session('error') }}</li> @endif
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                </div>
            </div>
        @endif

        <div class="form-card">
            <form action="{{ route('customer.booking.store') }}" method="POST" enctype="multipart/form-data" id="bookingForm">
                @csrf
                
                <div class="form-group">
                    <label class="label-custom">Pilih Unit Rumah Tersedia</label>
                    <select name="unit_id" id="unit_select" required class="input-custom">
                        <option value="" selected disabled>-- Pilih Unit --</option>
                        @isset($units)
                            @foreach($units as $namaProyek => $daftarUnit)
                                <optgroup label="PROYEK: {{ strtoupper($namaProyek) }}">
                                    @foreach($daftarUnit as $unit)
                                        <option value="{{ $unit->id }}" 
                                                data-harga="Rp {{ number_format($unit->tipe->harga ?? 0, 0, ',', '.') }}"
                                                data-proyek="{{ $unit->project->nama_proyek ?? $namaProyek }}"
                                                data-tipe="Tipe {{ $unit->tipe->nama_tipe ?? 'N/A' }}"
                                                {{ old('unit_id') == $unit->id ? 'selected' : '' }}>
                                            Blok {{ $unit->block ?? '-' }} No. {{ $unit->no_unit ?? '-' }} ({{ $unit->tipe->nama_tipe ?? 'N/A' }})
                                        </option>
                                    @endforeach
                                </optgroup>
                            @endforeach
                        @endisset
                    </select>
                </div>

                {{-- Preview Info Unit --}}
                <div id="unit_info" class="info-unit-box">
                    <div style="font-size: 11px; font-weight: 800; color: #1e5eff; text-transform: uppercase; margin-bottom: 8px;">
                        <i class="fas fa-tag mr-1"></i> Informasi Harga & Proyek:
                    </div>
                    <div id="display_harga" style="font-size: 28px; font-weight: 900; color: #1a202c; letter-spacing: -1px;"></div>
                    <div id="display_detail" style="font-size: 14px; color: #4a5568; margin-top: 5px; font-weight: 600; display: flex; align-items: center; gap: 8px;"></div>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div class="form-group">
                        <label class="label-custom">Tanggal Booking</label>
                        <input type="date" name="tanggal_booking" required class="input-custom" 
                               value="{{ old('tanggal_booking', date('Y-m-d')) }}" 
                               min="{{ date('Y-m-d') }}">
                    </div>

                    <div class="form-group">
                        <label class="label-custom">Foto KTP (Wajib)</label>
                        <input type="file" name="dokumen_ktp" required class="input-custom" accept="image/*,.pdf">
                        <small style="display:block; margin-top: 8px; color: #94a3b8; font-size: 11px; font-weight: 600;">Format: JPG, PNG, atau PDF (Maks. 5MB)</small>
                    </div>
                </div>

                <div class="form-group">
                    <label class="label-custom">Catatan Tambahan</label>
                    <textarea name="keterangan" rows="3" class="input-custom" 
                              placeholder="Contoh: Preferensi waktu survey lokasi, dll...">{{ old('keterangan') }}</textarea>
                </div>

                <button type="submit" class="btn-submit" id="submitBtn">
                    <span id="btnText">Ajukan Pemesanan Sekarang</span>
                </button>
            </form>
        </div>
    </div>
</div>

<script>
    const unitSelect = document.getElementById('unit_select');
    const infoBox = document.getElementById('unit_info');
    
    function updateInfo() {
        const selectedOption = unitSelect.options[unitSelect.selectedIndex];
        if (selectedOption && selectedOption.value) {
            infoBox.style.display = 'block';
            document.getElementById('display_harga').innerText = selectedOption.getAttribute('data-harga');
            document.getElementById('display_detail').innerHTML = 
                `<i class="fas fa-map-marker-alt" style="color:#1e5eff"></i> ${selectedOption.getAttribute('data-proyek')} <span style="color:#cbd5e1">|</span> ${selectedOption.getAttribute('data-tipe')}`;
        } else {
            infoBox.style.display = 'none';
        }
    }

    unitSelect.addEventListener('change', updateInfo);
    window.addEventListener('load', updateInfo); // Jalankan saat reload jika ada old value

    // Proteksi Double Click saat submit
    document.getElementById('bookingForm').addEventListener('submit', function() {
        const btn = document.getElementById('submitBtn');
        btn.style.opacity = '0.7';
        btn.style.pointerEvents = 'none';
        document.getElementById('btnText').innerText = 'Sedang Memproses...';
    });
</script>
@endsection