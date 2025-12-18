@extends('dashboard.customer')

@section('content')
<style>
    .content-wrapper { padding: 30px; font-family: 'Inter', sans-serif; background: #f8fafc; }
    .header-section { margin-bottom: 30px; }
    .title-box { font-size: 28px; font-weight: 900; color: #1a202c; letter-spacing: -1px; }
    .subtitle { color: #718096; font-size: 14px; font-weight: 500; }
    .form-card { background: white; border-radius: 30px; padding: 40px; border: 1px solid #e2e8f0; box-shadow: 0 20px 25px -5px rgba(0, 0, 0, 0.05); }
    .form-group { margin-bottom: 25px; }
    .label-custom { display: block; text-transform: uppercase; font-size: 11px; font-weight: 800; color: #a0aec0; letter-spacing: 1.5px; margin-bottom: 10px; margin-left: 4px; }
    .input-custom { width: 100%; background: #f1f5f9; border: 2px solid transparent; border-radius: 18px; padding: 16px 20px; font-weight: 600; color: #1a202c; transition: 0.3s; outline: none; }
    input[type="file"].input-custom { padding: 12px 20px; cursor: pointer; }
    .input-custom:focus { background: white; border-color: #1e5eff; box-shadow: 0 0 0 4px rgba(30, 94, 255, 0.1); }
    .info-unit-box { background: #ebf4ff; border-radius: 20px; padding: 20px; margin-bottom: 25px; display: none; border: 1px solid #d1e9ff; }
    .btn-submit { width: 100%; background: #1e5eff; color: white; padding: 18px; border-radius: 20px; font-size: 16px; font-weight: 800; text-transform: uppercase; letter-spacing: 1px; border: none; cursor: pointer; transition: 0.3s; box-shadow: 0 10px 15px -3px rgba(30, 94, 255, 0.2); }
    .btn-submit:hover { background: #0046ff; transform: translateY(-2px); }
    optgroup { font-weight: 800; color: #1e5eff; background: #f8fafc; }
</style>

<div class="content-wrapper">
    <div class="header-section">
        <div class="title-box">Booking Unit Baru</div>
        <div class="subtitle">Lengkapi formulir di bawah untuk mengajukan pemesanan unit rumah</div>
    </div>

    <div class="max-w-3xl mx-auto">
        <div class="form-card">
            <form action="{{ route('customer.booking.store') }}" method="POST" enctype="multipart/form-data">
                @csrf
                
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    {{-- PILIH UNIT BERDASARKAN PROYEK --}}
                    <div class="form-group md:col-span-2">
                        <label class="label-custom">Pilih Proyek & Unit Rumah</label>
                        <select name="unit_id" id="unit_select" required class="input-custom">
                            <option value="" selected disabled>-- Cari Unit Tersedia --</option>
                            @foreach($units as $namaProyek => $daftarUnit)
                                <optgroup label="PROYEK: {{ strtoupper($namaProyek) }}">
                                    @foreach($daftarUnit as $unit)
                                        <option value="{{ $unit->id }}" 
                                                data-harga="Rp {{ number_format($unit->tipe->harga, 0, ',', '.') }}"
                                                data-proyek="{{ $unit->project->nama_proyek }}"
                                                data-tipe="Tipe {{ $unit->tipe->nama_tipe }}">
                                            Blok {{ $unit->block }} No. {{ $unit->no_unit }} ({{ $unit->tipe->nama_tipe }})
                                        </option>
                                    @endforeach
                                </optgroup>
                            @endforeach
                        </select>
                    </div>

                    {{-- INFO UNIT DINAMIS --}}
                    <div id="unit_info" class="info-unit-box md:col-span-2">
                        <div style="font-size: 11px; font-weight: 800; color: #1e5eff; text-transform: uppercase; margin-bottom: 5px;">
                            <i class="fas fa-home mr-1"></i> Ringkasan Unit Terpilih:
                        </div>
                        <div id="display_harga" style="font-size: 24px; font-weight: 900; color: #1a202c;"></div>
                        <div id="display_detail" style="font-size: 14px; color: #4a5568; margin-top: 4px; font-weight: 600;"></div>
                    </div>

                    {{-- TANGGAL BOOKING --}}
                    <div class="form-group">
                        <label class="label-custom">Rencana Tanggal Booking</label>
                        <input type="date" name="tanggal_booking" required class="input-custom" value="{{ date('Y-m-d') }}" min="{{ date('Y-m-d') }}">
                    </div>

                    {{-- UPLOAD KTP --}}
                    <div class="form-group">
                        <label class="label-custom">Upload KTP (.jpg, .png, .pdf)</label>
                        <input type="file" name="dokumen_ktp" required class="input-custom" accept="image/*,.pdf">
                    </div>
                </div>

                {{-- CATATAN TAMBAHAN --}}
                <div class="form-group">
                    <label class="label-custom">Keterangan Tambahan (Opsional)</label>
                    <textarea name="keterangan" rows="3" class="input-custom" placeholder="Tuliskan pesan atau permintaan khusus untuk admin..."></textarea>
                </div>

                <div style="background: #fff7ed; border-radius: 15px; padding: 15px; margin-bottom: 25px; border: 1px solid #ffedd5;">
                    <p style="font-size: 12px; color: #9a3412; line-height: 1.6; font-weight: 600;">
                        <i class="fas fa-shield-alt mr-1"></i> Data Anda aman bersama kami. Admin akan memverifikasi pengajuan Anda dalam waktu maksimal 2x24 jam.
                    </p>
                </div>

                <button type="submit" class="btn-submit">
                    Kirim Pengajuan Booking
                </button>
            </form>
        </div>
    </div>
</div>

<script>
    document.getElementById('unit_select').addEventListener('change', function() {
        const selectedOption = this.options[this.selectedIndex];
        const infoBox = document.getElementById('unit_info');
        
        if (selectedOption.value) {
            infoBox.style.display = 'block';
            document.getElementById('display_harga').innerText = selectedOption.getAttribute('data-harga');
            document.getElementById('display_detail').innerText = 
                selectedOption.getAttribute('data-proyek') + ' • ' + selectedOption.getAttribute('data-tipe');
        } else {
            infoBox.style.display = 'none';
        }
    });
</script>
@endsection