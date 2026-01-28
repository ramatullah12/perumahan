@extends('layout.admin')

@section('content')
<div class="p-6 md:p-8 bg-[#F8FAFC] min-h-screen">
    <div class="max-w-3xl mx-auto">
        {{-- Navigation --}}
        <div class="mb-8 flex items-center justify-between">
            <a href="{{ route('admin.unit.index') }}" class="group flex items-center text-slate-400 hover:text-blue-600 transition-all">
                <div class="w-10 h-10 bg-white rounded-xl shadow-sm border border-slate-100 flex items-center justify-center mr-4 group-hover:-translate-x-1 transition-transform">
                    <i class="fas fa-chevron-left text-xs"></i>
                </div>
                <span class="text-[10px] font-black uppercase tracking-[0.2em]">Inventory Management</span>
            </a>
        </div>

        {{-- Error Alert --}}
        @if ($errors->any())
        <div class="mb-6 p-4 bg-red-50 border-l-4 border-red-500 rounded-xl">
            <div class="flex">
                <i class="fas fa-exclamation-circle text-red-500 mt-1 mr-3"></i>
                <div>
                    <p class="text-sm text-red-700 font-bold">Terjadi Kesalahan:</p>
                    <ul class="text-xs text-red-600 list-disc list-inside mt-1">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            </div>
        </div>
        @endif

        <div class="bg-white rounded-[3rem] shadow-sm border border-slate-100 overflow-hidden">
            {{-- Header Section --}}
            <div class="p-10 border-b border-slate-50 bg-slate-50/30 relative overflow-hidden">
                <div class="relative z-10">
                    <h2 class="text-3xl font-black text-slate-800 tracking-tighter">Tambah Unit Baru</h2>
                    <p class="text-slate-400 font-medium text-sm mt-1">Input data unit inventaris baru ke dalam sistem.</p>
                </div>
                <i class="fas fa-plus-circle absolute -right-4 -bottom-4 text-8xl text-slate-100/50"></i>
            </div>

            <form action="{{ route('admin.unit.store') }}" method="POST" class="p-10 space-y-8">
                @csrf

                <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
                    {{-- Proyek Selection --}}
                    <div class="space-y-2">
                        <label class="text-[10px] font-black text-blue-600 uppercase tracking-widest ml-1">Proyek Strategis</label>
                        <div class="relative">
                            {{-- Menggunakan window.location untuk reload tipe berdasarkan proyek --}}
                            <select name="project_id" id="project_select" required 
                                    onchange="window.location.href = '{{ route('admin.unit.create') }}?project_id=' + this.value"
                                    class="w-full bg-slate-50 border-none rounded-2xl p-4 text-sm font-bold text-slate-700 focus:ring-2 focus:ring-blue-500 outline-none appearance-none shadow-inner transition-all">
                                <option value="" disabled {{ !$selectedProjectId ? 'selected' : '' }}>Pilih Proyek</option>
                                @foreach($projects as $project)
                                    <option value="{{ $project->id }}" {{ $selectedProjectId == $project->id ? 'selected' : '' }}>
                                        {{ $project->nama_proyek }}
                                    </option>
                                @endforeach
                            </select>
                            <i class="fas fa-chevron-down absolute right-4 top-1/2 -translate-y-1/2 text-slate-300 text-xs pointer-events-none"></i>
                        </div>
                    </div>

                    {{-- Tipe Selection --}}
                    <div class="space-y-2">
                        <label class="text-[10px] font-black text-blue-600 uppercase tracking-widest ml-1">Varian Tipe Rumah</label>
                        <div class="relative">
                            <select name="tipe_id" id="tipe_select" required 
                                    class="w-full bg-slate-50 border-none rounded-2xl p-4 text-sm font-bold text-slate-700 focus:ring-2 focus:ring-blue-500 outline-none appearance-none shadow-inner transition-all">
                                @if($tipes->isEmpty())
                                    <option value="">{{ $selectedProjectId ? 'Tidak ada tipe tersedia' : 'Pilih Proyek Terlebih Dahulu' }}</option>
                                @else
                                    <option value="" disabled {{ !old('tipe_id') ? 'selected' : '' }}>Pilih Tipe Rumah</option>
                                    @foreach($tipes as $tipe)
                                        <option value="{{ $tipe->id }}" data-harga="{{ $tipe->harga }}" {{ old('tipe_id') == $tipe->id ? 'selected' : '' }}>
                                            Tipe {{ $tipe->nama_tipe }}
                                        </option>
                                    @endforeach
                                @endif
                            </select>
                            <i class="fas fa-chevron-down absolute right-4 top-1/2 -translate-y-1/2 text-slate-300 text-xs pointer-events-none"></i>
                        </div>
                    </div>

                    {{-- Blok & No Unit --}}
                    <div class="space-y-2 text-center md:text-left">
                        <label class="text-[10px] font-black text-slate-400 uppercase tracking-widest block">Blok</label>
                        <input type="text" name="block" value="{{ old('block') }}" placeholder="Contoh: A" required 
                               class="w-full bg-white border-2 border-slate-100 rounded-2xl p-4 text-lg font-black text-center text-slate-800 focus:border-blue-500 outline-none transition-all uppercase">
                    </div>

                    <div class="space-y-2 text-center md:text-left">
                        <label class="text-[10px] font-black text-slate-400 uppercase tracking-widest block">No. Unit</label>
                        <input type="text" name="no_unit" value="{{ old('no_unit') }}" placeholder="01" required 
                               class="w-full bg-white border-2 border-slate-100 rounded-2xl p-4 text-lg font-black text-center text-slate-800 focus:border-blue-500 outline-none transition-all">
                    </div>

                    {{-- Harga --}}
                    <div class="md:col-span-2 space-y-2">
                        <label class="text-[10px] font-black text-blue-600 uppercase tracking-widest ml-1">Penyesuaian Harga (IDR)</label>
                        <div class="relative group">
                            <span class="absolute inset-y-0 left-0 flex items-center pl-5 text-blue-400 font-bold text-xs">Rp</span>
                            <input type="number" name="harga" id="harga_input" value="{{ old('harga') }}" required 
                                   class="w-full pl-12 pr-4 py-4 bg-blue-50 border-none rounded-2xl text-sm font-black text-blue-600 focus:ring-2 focus:ring-blue-500 outline-none shadow-inner transition-all">
                        </div>
                        <p class="text-[9px] text-slate-400 ml-1 italic">* Harga otomatis mengikuti tipe, namun tetap dapat diubah manual</p>
                    </div>

                    {{-- Status Selection --}}
                    <div class="md:col-span-2 space-y-4">
                        <label class="text-[10px] font-black text-slate-400 uppercase tracking-widest block ml-1 text-center">Status Awal Unit</label>
                        <div class="grid grid-cols-3 gap-4">
                            @foreach(['Tersedia' => 'fa-check-circle', 'Dibooking' => 'fa-clock', 'Terjual' => 'fa-handshake'] as $status => $icon)
                            <label class="relative cursor-pointer group">
                                <input type="radio" name="status" value="{{ $status }}" class="hidden peer" {{ (old('status') == $status || (is_null(old('status')) && $loop->first)) ? 'checked' : '' }}>
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
                        <i class="fas fa-save mr-2"></i> Tambah Unit Ke Inventaris
                    </button>
                    <a href="{{ route('admin.unit.index') }}" class="px-10 py-5 bg-slate-100 text-slate-400 rounded-[2rem] font-bold uppercase tracking-widest hover:bg-slate-200 transition-all text-[10px] flex items-center justify-center">
                        Batal
                    </a>
                </div>
            </form>
        </div>
    </div>
</div>

{{-- Script Sederhana untuk Harga --}}
<script>
    document.addEventListener('DOMContentLoaded', function() {
        const tipeSelect = document.getElementById('tipe_select');
        const hargaInput = document.getElementById('harga_input');

        // Fungsi isi harga otomatis saat tipe dipilih
        tipeSelect.addEventListener('change', function() {
            const selectedOption = this.options[this.selectedIndex];
            const harga = selectedOption.getAttribute('data-harga');
            if (harga) {
                hargaInput.value = harga;
            }
        });

        // Cek harga saat halaman dimuat (untuk kasus old value atau filter)
        if (tipeSelect.selectedIndex > 0) {
            const initialSelected = tipeSelect.options[tipeSelect.selectedIndex];
            const initialHarga = initialSelected.getAttribute('data-harga');
            if (initialHarga && !hargaInput.value) {
                hargaInput.value = initialHarga;
            }
        }
    });
</script>
@endsection