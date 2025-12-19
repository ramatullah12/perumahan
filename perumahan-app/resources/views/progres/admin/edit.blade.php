@extends('dashboard.admin')

@section('content')
<div class="p-8 bg-slate-50 min-h-screen">
    <div class="max-w-4xl mx-auto">
        {{-- Breadcrumb & Back --}}
        <nav class="flex items-center space-x-2 text-sm font-medium mb-8">
            <a href="{{ route('admin.progres.index') }}" class="text-slate-400 hover:text-blue-600 transition-colors">Monitoring</a>
            <span class="text-slate-300">/</span>
            <span class="text-slate-900 font-bold">Kelola Unit</span>
        </nav>

        <div class="bg-white rounded-[2.5rem] shadow-2xl shadow-slate-200/50 overflow-hidden border border-slate-100 animate-fade-in">
            {{-- Professional Header --}}
            <div class="relative p-10 bg-slate-900 overflow-hidden">
                {{-- Decorative background element --}}
                <div class="absolute top-0 right-0 w-64 h-64 bg-blue-600/10 rounded-full -mr-20 -mt-20 blur-3xl"></div>
                
                <div class="relative z-10 flex flex-col md:flex-row md:items-center justify-between gap-6">
                    <div>
                        <span class="inline-flex items-center px-3 py-1 rounded-full text-[10px] font-black uppercase tracking-widest bg-blue-500/10 text-blue-400 border border-blue-500/20 mb-4">
                            System Update Manager
                        </span>
                        <h2 class="text-4xl font-black text-white tracking-tighter leading-none mb-3">
                            {{ $unit->project->nama_proyek ?? 'Unit Proyek' }}
                        </h2>
                        <div class="flex items-center text-slate-400 font-semibold">
                            <i class="fas fa-layer-group mr-2 text-blue-500"></i>
                            <span class="text-white">Blok {{ $unit->block }}</span>
                            <span class="mx-3 w-1 h-1 bg-slate-700 rounded-full"></span>
                            <i class="fas fa-door-open mr-2 text-blue-500"></i>
                            <span class="text-white">Nomor {{ $unit->no_unit }}</span>
                        </div>
                    </div>
                    
                    {{-- Current Progress Display --}}
                    <div class="flex items-center gap-4 bg-white/5 p-4 rounded-3xl border border-white/10 backdrop-blur-md">
                        <div class="text-right">
                            <p class="text-[10px] font-black text-slate-400 uppercase tracking-widest leading-none mb-1">Status Saat Ini</p>
                            <p class="text-2xl font-black text-white leading-none">{{ $unit->progres_pembangunan }}%</p>
                        </div>
                        <div class="w-12 h-12 rounded-2xl bg-blue-600 flex items-center justify-center shadow-lg shadow-blue-600/20">
                            <i class="fas fa-chart-line text-white"></i>
                        </div>
                    </div>
                </div>
            </div>

            {{-- Form Section --}}
            <form action="{{ route('admin.progres.update', $unit->id) }}" method="POST" enctype="multipart/form-data" class="p-10 space-y-10">
                @csrf
                @method('PUT')

                <div class="grid grid-cols-1 md:grid-cols-2 gap-10">
                    {{-- Input Persentase --}}
                    <div class="space-y-4">
                        <div class="flex items-center justify-between ml-2">
                            <label class="text-xs font-black text-slate-500 uppercase tracking-widest">Update Capaian (%)</label>
                            <span class="text-[10px] font-bold text-blue-600 bg-blue-50 px-2 py-1 rounded-md">Wajib Diisi</span>
                        </div>
                        <div class="relative group">
                            <input type="number" name="persentase" value="{{ old('persentase', $unit->progres_pembangunan) }}" min="0" max="100" required 
                                   class="w-full bg-slate-50 border-2 border-slate-100 rounded-3xl p-6 font-black text-blue-600 text-3xl outline-none focus:border-blue-500 focus:bg-white transition-all duration-300">
                            <span class="absolute right-8 top-1/2 -translate-y-1/2 text-3xl font-black text-slate-300 group-focus-within:text-blue-500 transition-colors">%</span>
                        </div>
                        <p class="text-[11px] text-slate-400 font-medium ml-2">Masukkan angka saja (0 - 100). Data ini akan mensinkronkan dashboard customer secara otomatis.</p>
                    </div>

                    {{-- Input Tahap --}}
                    <div class="space-y-4">
                        <div class="flex items-center justify-between ml-2">
                            <label class="text-xs font-black text-slate-500 uppercase tracking-widest">Tahap Pengerjaan</label>
                            <span class="text-[10px] font-bold text-blue-600 bg-blue-50 px-2 py-1 rounded-md">Wajib Diisi</span>
                        </div>
                        <div class="relative">
                            <i class="fas fa-hammer absolute left-6 top-1/2 -translate-y-1/2 text-slate-300"></i>
                            <input type="text" name="tahap" value="{{ old('tahap', $unit->latestProgres->tahap ?? '') }}" placeholder="Misal: Finishing / Atap" required 
                                   class="w-full bg-slate-50 border-2 border-slate-100 rounded-3xl p-6 pl-14 font-bold text-slate-700 outline-none focus:border-blue-500 focus:bg-white transition-all duration-300">
                        </div>
                        <p class="text-[11px] text-slate-400 font-medium ml-2">Gunakan istilah teknis yang mudah dipahami oleh pembeli rumah.</p>
                    </div>
                </div>

                {{-- Input Keterangan --}}
                <div class="space-y-4">
                    <label class="text-xs font-black text-slate-500 uppercase tracking-widest ml-2">Laporan Detail Lapangan</label>
                    <textarea name="keterangan" rows="4" 
                              placeholder="Contoh: Pemasangan keramik lantai dua sudah selesai, saat ini tim sedang mengerjakan instalasi listrik..." 
                              class="w-full bg-slate-50 border-2 border-slate-100 rounded-[2rem] p-8 font-medium text-slate-600 outline-none focus:border-blue-500 focus:bg-white transition-all resize-none shadow-inner">{{ old('keterangan', $unit->latestProgres->keterangan ?? '') }}</textarea>
                </div>

                {{-- Photo Documentation --}}
                <div class="space-y-6">
                    <label class="text-xs font-black text-slate-500 uppercase tracking-widest ml-2">Visual Dokumentasi</label>
                    
                    <div class="grid grid-cols-1 lg:grid-cols-2 gap-8">
                        {{-- Upload Area --}}
                        <div class="relative">
                            <input type="file" name="foto" id="foto-input" class="hidden" accept="image/*" onchange="previewImage(event)">
                            <label for="foto-input" class="flex flex-col items-center justify-center w-full h-64 border-2 border-dashed border-slate-200 rounded-[2.5rem] cursor-pointer bg-slate-50 hover:bg-blue-50/50 hover:border-blue-400 transition-all duration-500 group overflow-hidden">
                                <div id="upload-placeholder" class="flex flex-col items-center justify-center py-10 transition-all">
                                    <div class="w-16 h-16 bg-white rounded-2xl shadow-sm flex items-center justify-center mb-4 group-hover:scale-110 transition-transform duration-500">
                                        <i class="fas fa-cloud-upload-alt text-2xl text-blue-500"></i>
                                    </div>
                                    <p class="text-sm font-bold text-slate-600 uppercase tracking-widest">Ganti Dokumentasi</p>
                                    <p class="text-[10px] text-slate-400 mt-2 uppercase">Klik untuk mencari file (JPG, PNG)</p>
                                </div>
                                {{-- Live Preview Image --}}
                                <img id="live-preview" class="hidden absolute inset-0 w-full h-full object-cover rounded-[2rem]">
                            </label>
                        </div>

                        {{-- Old Photo Preview --}}
                        <div class="bg-slate-50 rounded-[2.5rem] p-6 border border-slate-100 flex flex-col justify-center">
                            @if($unit->latestProgres && $unit->latestProgres->foto)
                                <div class="flex items-center gap-6">
                                    <div class="relative w-40 h-40 flex-shrink-0">
                                        <img src="{{ asset('storage/' . $unit->latestProgres->foto) }}" class="w-full h-full object-cover rounded-[2rem] shadow-xl border-4 border-white">
                                        <div class="absolute -bottom-2 -right-2 bg-slate-900 text-white p-2 rounded-xl text-[8px] font-black uppercase">Aktif</div>
                                    </div>
                                    <div>
                                        <h4 class="text-sm font-black text-slate-800 uppercase tracking-tight mb-1">Foto Sebelumnya</h4>
                                        <p class="text-xs text-slate-500 leading-relaxed italic">Foto ini akan tetap digunakan jika Anda tidak memilih file baru.</p>
                                    </div>
                                </div>
                            @else
                                <div class="text-center">
                                    <i class="fas fa-images text-4xl text-slate-200 mb-3"></i>
                                    <p class="text-xs font-bold text-slate-400 uppercase tracking-widest">Belum ada foto</p>
                                </div>
                            @endif
                        </div>
                    </div>
                </div>

                {{-- Action Buttons --}}
                <div class="flex flex-col md:flex-row gap-6 pt-10 border-t border-slate-100">
                    <a href="{{ route('admin.progres.index') }}" 
                       class="flex-1 text-center py-5 bg-slate-100 text-slate-500 rounded-3xl font-black uppercase tracking-widest hover:bg-slate-200 transition-all duration-300">
                        Batal
                    </a>
                    <button type="submit" 
                            class="flex-[2] py-5 bg-blue-600 text-white rounded-3xl font-black uppercase tracking-widest shadow-2xl shadow-blue-600/30 hover:bg-blue-700 hover:-translate-y-1 transition-all duration-300 flex items-center justify-center gap-3">
                        <i class="fas fa-check-circle"></i>
                        Simpan & Update Progres
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
    function previewImage(event) {
        const input = event.target;
        const reader = new FileReader();
        const preview = document.getElementById('live-preview');
        const placeholder = document.getElementById('upload-placeholder');
        
        reader.onload = function(){
            preview.src = reader.result;
            preview.classList.remove('hidden');
            placeholder.classList.add('opacity-0');
        };
        
        if (input.files[0]) {
            reader.readAsDataURL(input.files[0]);
        }
    }
</script>

<style>
    @keyframes fade-in {
        from { opacity: 0; transform: translateY(30px); }
        to { opacity: 1; transform: translateY(0); }
    }
    .animate-fade-in {
        animation: fade-in 0.8s cubic-bezier(0.16, 1, 0.3, 1) forwards;
    }
    input::-webkit-outer-spin-button,
    input::-webkit-inner-spin-button {
        -webkit-appearance: none;
        margin: 0;
    }
    input[type=number] {
        -moz-appearance: textfield;
    }
</style>
@endsection