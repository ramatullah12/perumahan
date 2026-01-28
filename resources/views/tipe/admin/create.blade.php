@extends('layout.admin')

@section('content')
<div class="p-4 md:p-8 bg-[#F8FAFC] min-h-screen">
    <div class="max-w-4xl mx-auto">
        {{-- Breadcrumb & Title --}}
        <div class="mb-8 flex flex-col md:flex-row md:items-center justify-between gap-4">
            <div>
                <nav class="flex mb-2 text-[10px] font-black uppercase tracking-[0.2em]" aria-label="Breadcrumb">
                    <ol class="inline-flex items-center space-x-2">
                        <li class="text-gray-400">Tipe Rumah</li>
                        <li class="text-gray-300">/</li>
                        <li class="text-blue-600">Registrasi Baru</li>
                    </ol>
                </nav>
                <h2 class="text-3xl font-black text-slate-800 tracking-tight">Tambah Katalog Tipe</h2>
            </div>
            <a href="{{ route('admin.tipe.index') }}" class="text-slate-400 hover:text-slate-600 font-bold text-sm transition-colors flex items-center">
                <i class="fas fa-times-circle mr-2 text-lg"></i> Batalkan
            </a>
        </div>
        
        <div class="bg-white p-8 md:p-12 rounded-[3rem] shadow-sm border border-slate-100">
            <form action="{{ route('admin.tipe.store') }}" method="POST" enctype="multipart/form-data" class="space-y-8">
                @csrf
                
                {{-- Pemilihan Proyek --}}
                <div class="space-y-2">
                    <label class="block text-[10px] font-black text-slate-400 mb-1 uppercase tracking-[0.2em] ml-1">Koneksikan ke Proyek</label>
                    <div class="relative group">
                        <span class="absolute inset-y-0 left-0 flex items-center pl-4 text-slate-300 group-focus-within:text-blue-500 transition-colors">
                            <i class="fas fa-layer-group"></i>
                        </span>
                        <select name="project_id" class="w-full pl-11 pr-4 py-4 bg-slate-50 border border-transparent rounded-2xl outline-none focus:bg-white focus:border-blue-500 transition-all font-bold text-slate-700 appearance-none shadow-inner" required>
                            <option value="" disabled selected>Pilih Lokasi Perumahan...</option>
                            @foreach($projects as $p)
                                <option value="{{ $p->id }}">{{ $p->nama_proyek }}</option>
                            @endforeach
                        </select>
                        <div class="absolute inset-y-0 right-0 flex items-center pr-4 pointer-events-none text-slate-400">
                            <i class="fas fa-chevron-down text-xs"></i>
                        </div>
                    </div>
                </div>
                
                {{-- Nama Tipe & Harga --}}
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div class="space-y-2">
                        <label class="block text-[10px] font-black text-slate-400 mb-1 uppercase tracking-[0.2em] ml-1">Identitas Tipe</label>
                        <input type="text" name="nama_tipe" placeholder="Contoh: Deluxe Type A" 
                            class="w-full p-4 bg-slate-50 border border-transparent rounded-2xl outline-none focus:bg-white focus:border-blue-500 transition-all font-bold text-slate-700 shadow-inner" required>
                    </div>
                    <div class="space-y-2">
                        <label class="block text-[10px] font-black text-slate-400 mb-1 uppercase tracking-[0.2em] ml-1">Nilai Investasi (Rp)</label>
                        <div class="relative group">
                            <span class="absolute inset-y-0 left-0 flex items-center pl-4 text-slate-400 font-bold">Rp</span>
                            <input type="number" name="harga" placeholder="0" 
                                class="w-full pl-12 pr-4 py-4 bg-slate-50 border border-transparent rounded-2xl outline-none focus:bg-white focus:border-blue-500 transition-all font-bold text-slate-700 shadow-inner" required>
                        </div>
                    </div>
                </div>

                {{-- Grid Spesifikasi --}}
                <div class="space-y-3">
                    <label class="block text-[10px] font-black text-slate-400 mb-1 uppercase tracking-[0.2em] ml-1">Parameter Spesifikasi Bangunan</label>
                    <div class="grid grid-cols-2 md:grid-cols-4 gap-4">
                        <div class="bg-slate-50 p-4 rounded-2xl border border-slate-100 flex flex-col items-center group hover:bg-white hover:border-blue-200 transition-all">
                            <i class="fas fa-expand-alt text-slate-300 mb-2 text-xs group-hover:text-blue-500"></i>
                            <input type="number" name="luas_tanah" placeholder="LT (m²)" class="w-full bg-transparent border-none text-center font-black text-slate-700 outline-none p-0 placeholder:text-slate-300" required>
                        </div>
                        <div class="bg-slate-50 p-4 rounded-2xl border border-slate-100 flex flex-col items-center group hover:bg-white hover:border-emerald-200 transition-all">
                            <i class="fas fa-home text-slate-300 mb-2 text-xs group-hover:text-emerald-500"></i>
                            <input type="number" name="luas_bangunan" placeholder="LB (m²)" class="w-full bg-transparent border-none text-center font-black text-slate-700 outline-none p-0 placeholder:text-slate-300" required>
                        </div>
                        <div class="bg-slate-50 p-4 rounded-2xl border border-slate-100 flex flex-col items-center group hover:bg-white hover:border-orange-200 transition-all">
                            <i class="fas fa-bed text-slate-300 mb-2 text-xs group-hover:text-orange-500"></i>
                            <input type="number" name="kamar_tidur" placeholder="Kamar" class="w-full bg-transparent border-none text-center font-black text-slate-700 outline-none p-0 placeholder:text-slate-300" required>
                        </div>
                        <div class="bg-slate-50 p-4 rounded-2xl border border-slate-100 flex flex-col items-center group hover:bg-white hover:border-cyan-200 transition-all">
                            <i class="fas fa-shower text-slate-300 mb-2 text-xs group-hover:text-cyan-500"></i>
                            <input type="number" name="kamar_mandi" placeholder="Mandi" class="w-full bg-transparent border-none text-center font-black text-slate-700 outline-none p-0 placeholder:text-slate-300" required>
                        </div>
                    </div>
                </div>

                {{-- Image Upload & Preview --}}
                <div class="space-y-4">
                    <label class="block text-[10px] font-black text-slate-400 mb-1 uppercase tracking-[0.2em] ml-1">Visual Produk (Fasad/Denah)</label>
                    <div id="upload-zone" class="relative h-64 border-4 border-dashed border-slate-100 rounded-[2.5rem] bg-slate-50 group hover:bg-white hover:border-blue-100 transition-all duration-300 flex flex-col items-center justify-center overflow-hidden">
                        
                        {{-- Placeholder --}}
                        <div id="placeholder-text" class="text-center p-6 transition-all">
                            <div class="w-16 h-16 bg-white rounded-2xl shadow-sm flex items-center justify-center mx-auto mb-4 text-blue-500 group-hover:scale-110 transition-transform">
                                <i class="fas fa-cloud-arrow-up text-2xl"></i>
                            </div>
                            <p class="text-sm font-bold text-slate-500">Klik untuk mengunggah gambar tipe</p>
                            <p class="text-[10px] font-medium text-slate-400 uppercase mt-1">Format: JPG, PNG, WEBP (Maks. 2MB)</p>
                        </div>

                        {{-- Preview Image --}}
                        <img id="image-preview" src="#" alt="Preview" class="absolute inset-0 w-full h-full object-cover hidden z-10">
                        
                        <input type="file" name="gambar" id="img-input" accept="image/*" class="absolute inset-0 opacity-0 cursor-pointer z-20" required>
                        
                        {{-- Reset Button (Internal) --}}
                        <button type="button" id="reset-img" class="absolute bottom-4 right-4 z-30 bg-white/90 backdrop-blur p-3 rounded-xl shadow-lg text-rose-500 hover:bg-rose-500 hover:text-white transition-all hidden">
                            <i class="fas fa-undo"></i>
                        </button>
                    </div>
                </div>

                {{-- Action Buttons --}}
                <div class="pt-8 flex flex-col md:flex-row items-center gap-4">
                    <button type="submit" class="w-full md:flex-1 bg-blue-600 text-white py-5 rounded-2xl font-black uppercase tracking-widest text-xs shadow-xl shadow-blue-100 hover:bg-blue-700 hover:-translate-y-1 active:scale-95 transition-all flex items-center justify-center">
                        <i class="fas fa-plus-circle mr-3"></i> Daftarkan Tipe Rumah
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
    const imgInput = document.getElementById('img-input');
    const preview = document.getElementById('image-preview');
    const placeholder = document.getElementById('placeholder-text');
    const resetBtn = document.getElementById('reset-img');

    imgInput.onchange = evt => {
        const [file] = imgInput.files;
        if (file) {
            preview.src = URL.createObjectURL(file);
            preview.classList.remove('hidden');
            placeholder.classList.add('opacity-0');
            resetBtn.classList.remove('hidden');
        }
    }

    resetBtn.onclick = () => {
        imgInput.value = "";
        preview.src = "#";
        preview.classList.add('hidden');
        placeholder.classList.remove('opacity-0');
        resetBtn.classList.add('hidden');
    }
</script>
@endsection