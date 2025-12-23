@extends('layout.admin')

@section('content')
<div class="p-8 bg-gray-50 min-h-screen">
    <div class="max-w-3xl mx-auto">
        {{-- Breadcrumb & Back Button --}}
        <a href="{{ route('admin.unit.index') }}" class="flex items-center text-gray-400 hover:text-blue-600 transition-colors mb-6 group">
            <i class="fas fa-arrow-left mr-2 group-hover:-translate-x-1 transition-transform"></i>
            <span class="text-sm font-bold uppercase tracking-widest">Kembali ke Daftar Unit</span>
        </a>

        <div class="bg-white rounded-[2.5rem] shadow-sm border border-gray-100 overflow-hidden">
            <div class="p-10 border-b border-gray-50">
                <h2 class="text-3xl font-black text-gray-800 tracking-tighter">Tambah Unit Baru</h2>
                <p class="text-gray-500 italic text-sm mt-1">Masukkan detail nomor unit dan lokasi blok sesuai proyek.</p>
            </div>

            <form action="{{ route('admin.unit.store') }}" method="POST" class="p-10 space-y-8">
                @csrf

                <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
                    {{-- Pilih Proyek --}}
                    <div class="space-y-2">
                        <label class="text-[10px] font-black text-blue-600 uppercase tracking-widest">Proyek Perumahan</label>
                        <select name="project_id" id="project_select" required 
                                class="w-full bg-gray-50 border-none rounded-2xl p-4 text-sm font-bold text-gray-600 focus:ring-2 focus:ring-blue-500 outline-none transition-all">
                            <option value="">Pilih Proyek</option>
                            @foreach($projects as $project)
                                <option value="{{ $project->id }}" {{ old('project_id') == $project->id ? 'selected' : '' }}>
                                    {{ $project->nama_proyek }}
                                </option>
                            @endforeach
                        </select>
                        @error('project_id') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                    </div>

                    {{-- Pilih Tipe --}}
                    <div class="space-y-2">
                        <label class="text-[10px] font-black text-blue-600 uppercase tracking-widest">Tipe Rumah</label>
                        <select name="tipe_id" id="tipe_select" required 
                                class="w-full bg-gray-50 border-none rounded-2xl p-4 text-sm font-bold text-gray-600 focus:ring-2 focus:ring-blue-500 outline-none transition-all">
                            <option value="">Pilih Proyek Dahulu</option>
                        </select>
                        @error('tipe_id') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                    </div>

                    {{-- Blok --}}
                    <div class="space-y-2">
                        <label class="text-[10px] font-black text-gray-400 uppercase tracking-widest">Blok</label>
                        <input type="text" name="block" value="{{ old('block') }}" required placeholder="Contoh: A / B / C" 
                               class="w-full bg-gray-50 border-none rounded-2xl p-4 text-sm font-bold text-gray-600 focus:ring-2 focus:ring-blue-500 outline-none transition-all">
                        @error('block') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                    </div>

                    {{-- Nomor Unit --}}
                    <div class="space-y-2">
                        <label class="text-[10px] font-black text-gray-400 uppercase tracking-widest">Nomor Unit</label>
                        <input type="text" name="no_unit" value="{{ old('no_unit') }}" required placeholder="Contoh: 01 / 02" 
                               class="w-full bg-gray-50 border-none rounded-2xl p-4 text-sm font-bold text-gray-600 focus:ring-2 focus:ring-blue-500 outline-none transition-all">
                        @error('no_unit') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                    </div>

                    {{-- Status --}}
                    <div class="md:col-span-2 space-y-2">
                        <label class="text-[10px] font-black text-gray-400 uppercase tracking-widest">Status Awal</label>
                        <div class="flex gap-4">
                            @foreach(['Tersedia', 'Dibooking'] as $status)
                            <label class="flex-1 cursor-pointer">
                                <input type="radio" name="status" value="{{ $status }}" class="hidden peer" {{ $loop->first ? 'checked' : '' }}>
                                <div class="p-4 rounded-2xl bg-gray-50 text-center font-bold text-sm text-gray-400 peer-checked:bg-blue-600 peer-checked:text-white transition-all">
                                    {{ $status }}
                                </div>
                            </label>
                            @endforeach
                        </div>
                    </div>
                </div>

                <div class="pt-6 border-t border-gray-50 flex gap-4">
                    <button type="submit" class="flex-1 bg-blue-600 text-white p-5 rounded-3xl font-black uppercase tracking-widest shadow-xl shadow-blue-100 hover:bg-blue-700 hover:-translate-y-1 transition-all">
                        Simpan Unit Baru
                    </button>
                    <a href="{{ route('admin.unit.index') }}" class="px-10 py-5 bg-gray-100 text-gray-500 rounded-3xl font-bold hover:bg-gray-200 transition-all">
                        Batal
                    </a>
                </div>
            </form>
        </div>
    </div>
</div>

{{-- AJAX Script untuk Dropdown Tipe --}}
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script>
    $(document).ready(function() {
        $('#project_select').on('change', function() {
            let projectId = $(this).val();
            let tipeSelect = $('#tipe_select');
            
            tipeSelect.empty().append('<option value="">Memuat...</option>');

            if (projectId) {
                $.ajax({
                    url: '/admin/get-tipe/' + projectId, // Pastikan route ini sesuai di web.php
                    type: 'GET',
                    success: function(data) {
                        tipeSelect.empty().append('<option value="">Pilih Tipe</option>');
                        $.each(data, function(key, value) {
                            tipeSelect.append('<option value="' + value.id + '">Tipe ' + value.nama_tipe + '</option>');
                        });
                    },
                    error: function() {
                        tipeSelect.empty().append('<option value="">Gagal memuat tipe</option>');
                    }
                });
            } else {
                tipeSelect.empty().append('<option value="">Pilih Proyek Dahulu</option>');
            }
        });
    });
</script>
@endsection