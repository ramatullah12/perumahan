@extends('layout.admin')

@section('content')
<div class="p-4 md:p-8 bg-[#F8FAFC] min-h-screen">
    <div class="max-w-3xl mx-auto">
        {{-- Navigation & Header --}}
        <div class="mb-8 flex items-center justify-between">
            <a href="{{ route('admin.unit.index') }}" class="group flex items-center text-slate-400 hover:text-blue-600 transition-all">
                <div class="w-10 h-10 bg-white rounded-xl shadow-sm border border-slate-100 flex items-center justify-center mr-4 group-hover:-translate-x-1 transition-transform">
                    <i class="fas fa-chevron-left text-xs"></i>
                </div>
                <span class="text-[10px] font-black uppercase tracking-[0.2em]">List Inventory Unit</span>
            </a>
            <div class="hidden md:block">
                <p class="text-[10px] font-black text-slate-300 uppercase tracking-widest text-right leading-none">Status Terakhir</p>
                <p class="text-sm font-bold text-slate-500 text-right uppercase tracking-tighter">{{ $unit->status }}</p>
            </div>
        </div>

        <div class="bg-white rounded-[3rem] shadow-sm border border-slate-100 overflow-hidden">
            {{-- Form Header --}}
            <div class="p-10 border-b border-slate-50 bg-slate-50/30 flex items-start justify-between">
                <div class="space-y-1">
                    <h2 class="text-3xl font-black text-slate-800 tracking-tighter">Edit Detil Unit</h2>
                    <p class="text-slate-400 font-medium text-sm">Update parameter lokasi, nilai aset, dan progres fisik.</p>
                </div>
                <div class="w-16 h-16 bg-blue-600 rounded-3xl flex items-center justify-center shadow-xl shadow-blue-100">
                    <i class="fas fa-home-edit text-white text-2xl"></i>
                </div>
            </div>

            <form action="{{ route('admin.unit.update', $unit->id) }}" method="POST" class="p-10 space-y-8">
                @csrf
                @method('PUT')

                <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
                    {{-- Proyek Selection --}}
                    <div class="space-y-3">
                        <label class="text-[10px] font-black text-slate-400 uppercase tracking-widest ml-1">Penempatan Proyek</label>
                        <div class="relative">
                            <select name="project_id" id="project_select_edit" required 
                                    class="w-full bg-slate-50 border-none rounded-2xl p-4 text-sm font-bold text-slate-700 focus:ring-2 focus:ring-blue-500 outline-none transition-all cursor-pointer appearance-none shadow-inner">
                                @foreach($projects as $project)
                                    <option value="{{ $project->id }}" {{ $unit->project_id == $project->id ? 'selected' : '' }}>
                                        {{ $project->nama_proyek }}
                                    </option>
                                @endforeach
                            </select>
                            <i class="fas fa-chevron-down absolute right-4 top-1/2 -translate-y-1/2 text-slate-300 pointer-events-none"></i>
                        </div>
                    </div>

                    {{-- Tipe Selection --}}
                    <div class="space-y-3">
                        <label class="text-[10px] font-black text-slate-400 uppercase tracking-widest ml-1">Klasifikasi Tipe</label>
                        <div class="relative">
                            <select name="tipe_id" id="tipe_select_edit" required 
                                    class="w-full bg-slate-50 border-none rounded-2xl p-4 text-sm font-bold text-slate-700 focus:ring-2 focus:ring-blue-500 outline-none transition-all cursor-pointer appearance-none shadow-inner">
                                @foreach($tipes as $tipe)
                                    <option value="{{ $tipe->id }}" {{ $unit->tipe_id == $tipe->id ? 'selected' : '' }}>
                                        Tipe {{ $tipe->nama_tipe }}
                                    </option>
                                @endforeach
                            </select>
                            <i class="fas fa-chevron-down absolute right-4 top-1/2 -translate-y-1/2 text-slate-300 pointer-events-none"></i>
                        </div>
                    </div>

                    {{-- Blok & No Unit --}}
                    <div class="grid grid-cols-2 gap-4 md:col-span-1">
                        <div class="space-y-3">
                            <label class="text-[10px] font-black text-slate-400 uppercase tracking-widest ml-1 text-center block">Blok</label>
                            <input type="text" name="block" value="{{ old('block', $unit->block) }}" required 
                                   class="w-full bg-slate-50 border-none rounded-2xl p-4 text-sm font-black text-center text-slate-700 focus:ring-2 focus:ring-blue-500 outline-none shadow-inner">
                        </div>
                        <div class="space-y-3">
                            <label class="text-[10px] font-black text-slate-400 uppercase tracking-widest ml-1 text-center block">No. Unit</label>
                            <input type="text" name="no_unit" value="{{ old('no_unit', $unit->no_unit) }}" required 
                                   class="w-full bg-slate-50 border-none rounded-2xl p-4 text-sm font-black text-center text-slate-700 focus:ring-2 focus:ring-blue-500 outline-none shadow-inner">
                        </div>
                    </div>

                    {{-- Harga Unit --}}
                    <div class="space-y-3">
                        <label class="text-[10px] font-black text-blue-500 uppercase tracking-widest ml-1">Penyesuaian Nilai Jual (Rp)</label>
                        <div class="relative group">
                            <span class="absolute inset-y-0 left-0 flex items-center pl-5 text-blue-400 font-bold text-xs group-focus-within:text-blue-600">Rp</span>
                            <input type="number" name="harga" value="{{ old('harga', $unit->harga) }}" required 
                                   class="w-full pl-12 pr-4 py-4 bg-blue-50 border-none rounded-2xl text-sm font-black text-blue-600 focus:ring-2 focus:ring-blue-500 outline-none shadow-inner">
                        </div>
                    </div>

                    {{-- Progres Pembangunan --}}
                    <div class="md:col-span-2 p-6 bg-slate-50 rounded-[2rem] border border-slate-100">
                        <div class="flex items-center justify-between mb-4">
                            <label class="text-[10px] font-black text-emerald-600 uppercase tracking-widest ml-1">Laporan Progres Fisik (%)</label>
                            <span id="prog-val" class="bg-emerald-500 text-white px-3 py-1 rounded-lg text-xs font-black">{{ old('progres', $unit->progres) }}%</span>
                        </div>
                        <div class="relative flex items-center">
                            <i class="fas fa-tools text-emerald-400 mr-4 text-xl"></i>
                            <input type="range" name="progres" value="{{ old('progres', $unit->progres) }}" required min="0" max="100" id="prog-range"
                                   class="w-full h-2 bg-slate-200 rounded-lg appearance-none cursor-pointer accent-emerald-500">
                        </div>
                        <p class="text-[10px] text-slate-400 mt-3 italic text-center font-medium uppercase tracking-tighter">Geser slider untuk memperbarui persentase pembangunan di dashboard utama.</p>
                    </div>

                    {{-- Status Unit --}}
                    <div class="md:col-span-2 space-y-4">
                        <label class="text-[10px] font-black text-slate-400 uppercase tracking-widest block ml-1">Otorisasi Status Ketersediaan</label>
                        <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                            @foreach(['Tersedia', 'Dibooking', 'Terjual'] as $status)
                            <label class="relative cursor-pointer group">
                                <input type="radio" name="status" value="{{ $status }}" class="hidden peer" {{ $unit->status == $status ? 'checked' : '' }}>
                                <div class="p-5 rounded-[1.5rem] bg-white border-2 border-slate-50 text-center transition-all peer-checked:border-blue-500 peer-checked:bg-blue-50 group-hover:border-slate-200">
                                    <div class="flex flex-col items-center">
                                        <i class="fas {{ $status == 'Tersedia' ? 'fa-check-circle' : ($status == 'Dibooking' ? 'fa-clock' : 'fa-handshake') }} 
                                           mb-2 text-lg {{ $unit->status == $status ? 'text-blue-600' : 'text-slate-300' }} peer-checked:text-blue-600"></i>
                                        <span class="font-black text-[11px] uppercase tracking-widest {{ $unit->status == $status ? 'text-blue-700' : 'text-slate-400' }} peer-checked:text-blue-700">
                                            {{ $status }}
                                        </span>
                                    </div>
                                </div>
                                <div class="absolute -top-2 -right-2 hidden peer-checked:block text-blue-500 bg-white rounded-full shadow-lg">
                                    <i class="fas fa-check-circle text-xl"></i>
                                </div>
                            </label>
                            @endforeach
                        </div>
                    </div>
                </div>

                {{-- Submission --}}
                <div class="pt-10 flex flex-col md:flex-row gap-4">
                    <button type="submit" class="flex-1 bg-blue-600 text-white p-6 rounded-[2rem] font-black uppercase tracking-widest text-[11px] shadow-2xl shadow-blue-100 hover:bg-blue-700 hover:-translate-y-1 transition-all active:scale-95">
                        <i class="fas fa-save mr-2"></i> Konfirmasi Perubahan
                    </button>
                    <a href="{{ route('admin.unit.index') }}" class="px-12 py-6 bg-slate-100 text-slate-400 rounded-[2rem] font-black uppercase tracking-widest text-[10px] hover:bg-slate-200 transition-all flex items-center justify-center">
                        Batalkan
                    </a>
                </div>
            </form>
        </div>
    </div>
</div>

<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script>
    // Live Progress Update
    $('#prog-range').on('input', function() {
        $('#prog-val').text($(this).val() + '%');
    });

    // Dynamic Type Selection
    $('#project_select_edit').on('change', function() {
        let projectId = $(this).val();
        let tipeSelect = $('#tipe_select_edit');
        tipeSelect.empty().append('<option value="">Memuat...</option>').addClass('opacity-50');

        if (projectId) {
            $.ajax({
                url: '/admin/get-tipe/' + projectId,
                type: 'GET',
                success: function(data) {
                    tipeSelect.removeClass('opacity-50').empty().append('<option value="">-- Pilih Tipe --</option>');
                    $.each(data, function(key, value) {
                        tipeSelect.append('<option value="' + value.id + '">Tipe ' + value.nama_tipe + '</option>');
                    });
                },
                error: function() {
                    tipeSelect.removeClass('opacity-50').empty().append('<option value="">Gagal sinkronisasi data</option>');
                }
            });
        }
    });
</script>
@endsection