@extends('layout.customer')

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
    .input-custom:focus { background: white; border-color: #1e5eff; box-shadow: 0 0 0 4px rgba(30, 94, 255, 0.1); }
    .info-unit-box { background: #ebf4ff; border-radius: 20px; padding: 20px; margin-bottom: 25px; display: none; border: 1px solid #d1e9ff; }
    .btn-submit { width: 100%; background: #1e5eff; color: white; padding: 18px; border-radius: 20px; font-size: 16px; font-weight: 800; text-transform: uppercase; letter-spacing: 1px; border: none; cursor: pointer; transition: 0.3s; box-shadow: 0 10px 15px -3px rgba(30, 94, 255, 0.2); }
    .btn-submit:hover { background: #0046ff; transform: translateY(-2px); }
</style>

<div class="content-wrapper">
    <div class="header-section">
        <div class="title-box">Booking Unit Baru</div>
        <div class="subtitle">Silakan pilih proyek terlebih dahulu untuk melihat unit yang tersedia</div>
    </div>

    <div class="max-w-3xl mx-auto">
        {{-- Notifikasi Sukses/Error --}}
        @if(session('success'))
            <div class="alert alert-success border-0 shadow-sm mb-4" style="border-radius: 20px;">
                {{ session('success') }}
            </div>
        @endif

        @if(session('error'))
            <div class="alert alert-danger border-0 shadow-sm mb-4" style="border-radius: 20px;">
                {{ session('error') }}
            </div>
        @endif

        <div class="form-card">
            <form action="{{ route('customer.booking.store') }}" method="POST" enctype="multipart/form-data">
                @csrf
                
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    {{-- DROPDOWN PROYEK --}}
                    <div class="form-group md:col-span-2">
                        <label class="label-custom">1. Pilih Proyek</label>
                        <select id="project_select" class="input-custom" required>
                            <option value="" selected disabled>-- Pilih Proyek --</option>
                            @foreach($projects as $project)
                                <option value="{{ $project->id }}">{{ strtoupper($project->nama_proyek) }}</option>
                            @endforeach
                        </select>
                    </div>

                    {{-- DROPDOWN UNIT (DIISI VIA AJAX) --}}
                    <div class="form-group md:col-span-2">
                        <label class="label-custom">2. Pilih Unit Tersedia</label>
                        <select name="unit_id" id="unit_select" required class="input-custom">
                            <option value="" selected disabled>-- Pilih Proyek Terlebih Dahulu --</option>
                        </select>
                    </div>

                    {{-- INFO RINGKASAN UNIT --}}
                    <div id="unit_info" class="info-unit-box md:col-span-2">
                        <div style="font-size: 11px; font-weight: 800; color: #1e5eff; text-transform: uppercase; margin-bottom: 5px;">
                            <i class="fas fa-tag mr-1"></i> Estimasi Harga:
                        </div>
                        <div id="display_harga" style="font-size: 24px; font-weight: 900; color: #1a202c;"></div>
                        <div id="display_detail" style="font-size: 14px; color: #4a5568; margin-top: 4px; font-weight: 600;"></div>
                    </div>

                    <div class="form-group">
                        <label class="label-custom">Rencana Tanggal Booking</label>
                        <input type="date" name="tanggal_booking" required class="input-custom" 
                               value="{{ date('Y-m-d') }}" min="{{ date('Y-m-d') }}">
                    </div>

                    <div class="form-group">
                        <label class="label-custom">Upload KTP (.jpg, .png, .pdf)</label>
                        <input type="file" name="dokumen_ktp" required class="input-custom" accept="image/*,.pdf">
                    </div>
                </div>

                <div class="form-group">
                    <label class="label-custom">Keterangan Tambahan (Opsional)</label>
                    <textarea name="keterangan" rows="3" class="input-custom" placeholder="Contoh: Request posisi hook..."></textarea>
                </div>

                <button type="submit" class="btn-submit">Kirim Pengajuan Booking</button>
            </form>
        </div>
    </div>
</div>

{{-- Tambahkan JQuery jika belum ada di layout --}}
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

<script>
    $(document).ready(function() {
        // Ketika Proyek dipilih
        $('#project_select').on('change', function() {
            var projectId = $(this).val();
            var unitSelect = $('#unit_select');
            var infoBox = $('#unit_info');

            // Reset dropdown unit & info box
            unitSelect.empty().append('<option value="">Memuat unit...</option>');
            infoBox.fadeOut();

            if (projectId) {
                $.ajax({
                    url: '/customer/get-units/' + projectId, // Memanggil rute yang kita buat di web.php
                    type: "GET",
                    dataType: "json",
                    success: function(data) {
                        unitSelect.empty().append('<option value="" selected disabled>-- Pilih Unit --</option>');
                        
                        if (data.length > 0) {
                            $.each(data, function(key, value) {
                                unitSelect.append('<option value="'+ value.id +'" data-harga="'+ value.harga_format +'" data-tipe="'+ value.nama_tipe +'">Blok '+ value.block +' No. '+ value.no_unit +' ('+ value.nama_tipe +')</option>');
                            });
                        } else {
                            unitSelect.empty().append('<option value="">Tidak ada unit tersedia</option>');
                        }
                    },
                    error: function() {
                        alert('Gagal mengambil data unit. Pastikan rute get-units sudah terdaftar.');
                    }
                });
            }
        });

        // Ketika Unit dipilih (Menampilkan Info Harga)
        $('#unit_select').on('change', function() {
            var selected = $(this).find('option:selected');
            var harga = selected.data('harga');
            var tipe = selected.data('tipe');

            if (harga) {
                $('#display_harga').text(harga);
                $('#display_detail').text('Tipe Unit: ' + tipe);
                $('#unit_info').fadeIn();
            } else {
                $('#unit_info').fadeOut();
            }
        });
    });
</script>
@endsection