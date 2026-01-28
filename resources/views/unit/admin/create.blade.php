@extends('layout.admin')

@section('content')
<div class="p-6 md:p-8 bg-[#F8FAFC] min-h-screen">
    <div class="max-w-3xl mx-auto">
        {{-- Navigation & Breadcrumb --}}
        <div class="mb-8 flex items-center justify-between">
            <a href="{{ route('admin.unit.index') }}" class="group flex items-center text-slate-400 hover:text-blue-600 transition-all">
                <div class="w-10 h-10 bg-white rounded-xl shadow-sm border border-slate-100 flex items-center justify-center mr-4 group-hover:-translate-x-1 transition-transform">
                    <i class="fas fa-chevron-left text-xs"></i>
                </div>
                <span class="text-[10px] font-black uppercase tracking-[0.2em]">Inventory Management</span>
            </a>
            <div class="px-4 py-2 bg-white rounded-xl border border-slate-100 shadow-sm">
                <p class="text-[9px] font-black text-slate-300 uppercase tracking-widest leading-none">Internal ID</p>
                <p class="text-xs font-bold text-slate-500">UNIT-{{ str_pad($unit->id, 5, '0', STR_PAD_LEFT) }}</p>
            </div>
        </div>

        <div class="bg-white rounded-[3rem] shadow-sm border border-slate-100 overflow-hidden">
            {{-- Header Section --}}
            <div class="p-10 border-b border-slate-50 bg-slate-50/30 relative overflow-hidden">
                <div class="relative z-10">
                    <h2 class="text-3xl font-black text-slate-800 tracking-tighter">Konfigurasi Unit</h2>
                    <p class="text-slate-400 font-medium text-sm mt-1">Sesuaikan spesifikasi aset dan pantau fase konstruksi.</p>
                </div>
                <i class="fas fa-home-edit absolute -right-4 -bottom-4 text-8xl text-slate-100/50"></i>
            </div>

            <form action="{{ route('admin.unit.update', $unit->id) }}" method="POST" class="p-10 space-y-8">
                @csrf
                @method('PUT')

                <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
                    {{-- Proyek & Tipe --}}
                    <div class="space-y-6">
                        <div class="space-y-2">
                            <label class="text-[10px] font-black text-blue-600 uppercase tracking-widest ml-1">Proyek Strategis</label>
                            <div class="relative">
                                <select name="project_id" id="project_select_edit" required 
                                        class="w-full bg-slate-50 border-none rounded-2xl p-4 text-sm font-bold text-slate-400 cursor-not-allowed outline-none appearance-none shadow-inner">
                                    @foreach($projects as $project)
                                        <option value="{{ $project->id }}" {{ $unit->project_id == $project->id ? 'selected' : '' }}>
                                            {{ $project->nama_proyek }}
                                        </option>
                                    @endforeach
                                </select>
                                <i class="fas fa-lock absolute right-4 top-1/2 -translate-y-1/2 text-slate-300 text-xs"></i>
                            </div>
                        </div>

                        <div class="space-y-2">
                            <label class="text-[10px] font-black text-blue-600 uppercase tracking-widest ml-1 text-xs">Tipe Arsitektur</label>
                            <div class="relative">
                                <select name="tipe_id" id="tipe_select_edit" required 
                                        class="w-full bg-slate-50 border-none rounded-2xl p-4 text-sm font-bold text-slate-700 focus:ring-2 focus:ring-blue-500 outline-none transition-all appearance-none shadow-inner">
                                    @foreach($tipes as $tipe)
                                        <option value="{{ $tipe->id }}" {{ $unit->tipe_id == $tipe->id ? 'selected' : '' }}>
                                            Tipe {{ $tipe->nama_tipe }}
                                        </option>
                                    @endforeach
                                </select>
                                <i class="fas fa-chevron-down absolute right-4 top-1/2 -translate-y-1/2 text-slate-300 text-xs pointer-events-none"></i>
                            </div>
                        </div>
                    </div>

                    {{-- Identitas Posisi --}}
                    <div class="bg-slate-50/50 rounded-[2rem] p-6 border border-slate-100 flex flex-col justify-center gap-6">
                        <div class="grid grid-cols-2 gap-4">
                            <div class="space-y-2 text-center">
                                <label class="text-[10px] font-black text-slate-400 uppercase tracking-widest block">Blok</label>
                                <input type="text" name="block" value="{{ old('block', $unit->block) }}" required 
                                       class="w-full bg-white border-2 border-slate-100 rounded-2xl p-4 text-lg font-black text-center text-slate-800 focus:border-blue-500 outline-none transition-all uppercase">
                            </div>
                            <div class="space-y-2 text-center">
                                <label class="text-[10px] font-black text-slate-400 uppercase tracking-widest block">No. Unit</label>
                                <input type="text" name="no_unit" value="{{ old('no_unit', $unit->no_unit) }}" required 
                                       class="w-full bg-white border-2 border-slate-100 rounded-2xl p-4 text-lg font-black text-center text-slate-800 focus:border-blue-500 outline-none transition-all">
                            </div>
                        </div>
                    </div>

                    {{-- Harga Unit --}}
                    <div class="space-y-2">
                        <label class="text-[10px] font-black text-blue-600 uppercase tracking-widest ml-1">Nilai Aset (IDR)</label>
                        <div class="relative group">
                            <span class="absolute inset-y-0 left-0 flex items-center pl-5 text-blue-400 font-bold text-xs group-focus-within:text-blue-600 transition-colors">Rp</span>
                            <input type="number" name="harga" value="{{ old('harga', $unit->harga) }}" required 
                                   class="w-full pl-12 pr-4 py-4 bg-blue-50 border-none rounded-2xl text-sm font-black text-blue-600 focus:ring-2 focus:ring-blue-500 outline-none shadow-inner">
                        </div>
                    </div>

                    {{-- Progres Pembangunan Visual --}}
                    <div class="space-y-2">
                        <div class="flex justify-between items-center px-1">
                            <label class="text-[10px] font-black text-emerald-600 uppercase tracking-widest">Fase Konstruksi</label>
                            <span id="prog-label" class="text-xs font-black text-emerald-600 bg-emerald-50 px-2 py-0.5 rounded-lg border border-emerald-100">{{ old('progres', $unit->progres) }}%</span>
                        </div>
                        <div class="bg-emerald-50/30 p-5 rounded-2xl border border-emerald-100/50">
                            <input type="range" name="progres" id="progres_range" value="{{ old('progres', $unit->progres) }}" required min="0" max="100"
                                   class="w-full h-2 bg-emerald-100 rounded-lg appearance-none cursor-pointer accent-emerald-500">
                            <div class="flex justify-between mt-2 text-[8px] font-bold text-emerald-400 uppercase tracking-tighter">
                                <span>Lahan</span>
                                <span>Struktur</span>
                                <span>Finishing</span>
                                <span>Selesai</span>
                            </div>
                        </div>
                    </div>

                    {{-- Status Selection --}}
                    <div class="md:col-span-2 space-y-4">
                        <label class="text-[10px] font-black text-slate-400 uppercase tracking-widest block ml-1 text-center">Otorisasi Status Ketersediaan</label>
                        <div class="grid grid-cols-3 gap-4">
                            @foreach([
                                'Tersedia' => 'fa-check-circle', 
                                'Dibooking' => 'fa-clock', 
                                'Terjual' => 'fa-handshake'
                            ] as $status => $icon)
                            <label class="relative cursor-pointer group">
                                <input type="radio" name="status" value="{{ $status }}" class="hidden peer" {{ $unit->status == $status ? 'checked' : '' }}>
                                <div class="p-4 rounded-2xl bg-slate-50 border-2 border-transparent text-center transition-all peer-checked:bg-blue-600 peer-checked:text-white peer-checked:shadow-lg peer-checked:shadow-blue-200 group-hover:bg-slate-100 peer-checked:group-hover:bg-blue-600">
                                    <i class="fas {{ $icon }} mb-2 text-lg block opacity-50 peer-checked:opacity-100"></i>
                                    <span class="text-[10px] font-black uppercase tracking-widest">{{ $status }}</span>
                                </div>
                            </label>
                            @endforeach
                        </div>
                    </div>
                </div>

                <div class="pt-8 border-t border-slate-50 flex flex-col md:flex-row gap-4">
                    <button type="submit" class="flex-1 bg-blue-600 text-white p-5 rounded-[2rem] font-black uppercase tracking-widest shadow-xl shadow-blue-100 hover:bg-blue-700 hover:-translate-y-1 transition-all active:scale-95 text-xs">
                        <i class="fas fa-save mr-2"></i> Update Inventaris
                    </button>
                    <a href="{{ route('admin.unit.index') }}" class="px-10 py-5 bg-slate-100 text-slate-400 rounded-[2rem] font-bold uppercase tracking-widest hover:bg-slate-200 transition-all text-[10px] flex items-center justify-center">
                        Batal
                    </a>
                </div>
            </form>
        </div>
    </div>
</div>

<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script>
    // Live Progress Label
    $('#progres_range').on('input', function() {
        $('#prog-label').text($(this).val() + '%');
    });

    // AJAX Tipe Update
    $('#project_select_edit').on('change', function() {
        let projectId = $(this).val();
        let tipeSelect = $('#tipe_select_edit');
        tipeSelect.empty().append('<option value="">Memuat...</option>');

        if (projectId) {
            $.ajax({
                url: '/admin/get-tipe/' + projectId,
                type: 'GET',
                success: function(data) {
                    tipeSelect.empty().append('<option value="">Pilih Tipe</option>');
                    $.each(data, function(key, value) {
                        tipeSelect.append('<option value="' + value.id + '">Tipe ' + value.nama_tipe + '</option>');
                    });
                }
            });
        }
    });
</script>
@endsection