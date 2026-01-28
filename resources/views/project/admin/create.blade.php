@extends('layout.admin')

@section('content')
<div class="p-4 md:p-8 bg-[#F9FAFB] min-h-screen">
    {{-- Breadcrumb & Header --}}
    <div class="max-w-4xl mx-auto mb-8">
        <nav class="flex mb-4 text-sm font-medium" aria-label="Breadcrumb">
            <ol class="inline-flex items-center space-x-2">
                <li>
                    <a href="{{ route('admin.project.index') }}" class="text-gray-400 hover:text-blue-600 transition-colors flex items-center">
                        <i class="fas fa-layer-group mr-2"></i> Manajemen Proyek
                    </a>
                </li>
                <li class="text-gray-300">/</li>
                <li class="text-blue-600 font-bold tracking-wide">Tambah Baru</li>
            </ol>
        </nav>
        <h2 class="text-3xl font-black text-gray-900 tracking-tight">Buat Proyek Properti</h2>
        <p class="text-gray-500 mt-1 font-medium">Lengkapi parameter proyek untuk mulai memasarkan unit kepada calon pembeli.</p>
    </div>

    <div class="max-w-4xl mx-auto">
        {{-- Alert Kesalahan Input --}}
        @if ($errors->any())
        <div class="mb-8 p-5 bg-rose-50 border-2 border-rose-100 rounded-3xl animate-pulse">
            <div class="flex items-start">
                <div class="flex-shrink-0 bg-rose-500 text-white rounded-lg p-2">
                    <i class="fas fa-exclamation-triangle"></i>
                </div>
                <div class="ml-4">
                    <h3 class="text-sm font-black text-rose-800 uppercase tracking-wider">Ups! Ada yang terlewat:</h3>
                    <ul class="mt-2 text-sm text-rose-700 font-medium list-disc list-inside space-y-1">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            </div>
        </div>
        @endif

        <form action="{{ route('admin.project.store') }}" method="POST" enctype="multipart/form-data" class="space-y-8">
            @csrf
            
            <div class="bg-white rounded-[2.5rem] shadow-sm border border-gray-100 overflow-hidden transition-all hover:shadow-md">
                <div class="p-10 space-y-8">
                    
                    {{-- Baris 1: Nama & Unit --}}
                    <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
                        <div class="md:col-span-2 space-y-2">
                            <label class="text-[10px] font-black text-gray-400 uppercase tracking-[0.2em] ml-1">Nama Proyek <span class="text-rose-500">*</span></label>
                            <div class="relative group">
                                <span class="absolute inset-y-0 left-0 flex items-center pl-4 text-gray-400 group-focus-within:text-blue-500 transition-colors">
                                    <i class="fas fa-building"></i>
                                </span>
                                <input type="text" name="nama_proyek" value="{{ old('nama_proyek') }}" 
                                    class="w-full pl-11 pr-4 py-4 bg-gray-50 border border-gray-100 rounded-2xl focus:ring-4 focus:ring-blue-50 focus:bg-white focus:border-blue-500 outline-none transition-all font-bold text-gray-700" 
                                    placeholder="Contoh: Griya Pesona Indah" required>
                            </div>
                        </div>

                        <div class="space-y-2">
                            <label class="text-[10px] font-black text-gray-400 uppercase tracking-[0.2em] ml-1">Total Kapasitas <span class="text-rose-500">*</span></label>
                            <div class="relative group">
                                <input type="number" name="total_unit" value="{{ old('total_unit') }}" 
                                    class="w-full px-4 py-4 bg-gray-50 border border-gray-100 rounded-2xl focus:ring-4 focus:ring-blue-50 focus:bg-white focus:border-blue-500 outline-none transition-all font-bold text-gray-700 text-center" 
                                    placeholder="0" required>
                            </div>
                        </div>
                    </div>

                    {{-- Baris 2: Lokasi & Status --}}
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
                        <div class="space-y-2">
                            <label class="text-[10px] font-black text-gray-400 uppercase tracking-[0.2em] ml-1">Lokasi Geografis <span class="text-rose-500">*</span></label>
                            <div class="relative group">
                                <span class="absolute inset-y-0 left-0 flex items-center pl-4 text-gray-400 group-focus-within:text-rose-500 transition-colors">
                                    <i class="fas fa-map-marker-alt"></i>
                                </span>
                                <input type="text" name="lokasi" value="{{ old('lokasi') }}" 
                                    class="w-full pl-11 pr-4 py-4 bg-gray-50 border border-gray-100 rounded-2xl focus:ring-4 focus:ring-blue-50 focus:bg-white focus:border-blue-500 outline-none transition-all font-bold text-gray-700" 
                                    placeholder="Kecamatan, Kota" required>
                            </div>
                        </div>

                        <div class="space-y-2">
                            <label class="text-[10px] font-black text-gray-400 uppercase tracking-[0.2em] ml-1">Status Awal</label>
                            <div class="relative group">
                                <span class="absolute inset-y-0 left-0 flex items-center pl-4 text-gray-400 pointer-events-none">
                                    <i class="fas fa-flag"></i>
                                </span>
                                <select name="status" class="w-full pl-11 pr-4 py-4 bg-gray-50 border border-gray-100 rounded-2xl focus:ring-4 focus:ring-blue-50 focus:bg-white focus:border-blue-500 outline-none transition-all font-bold text-gray-700 appearance-none">
                                    <option value="Pre-Launch">Pre-Launch</option>
                                    <option value="Sedang Berjalan" selected>Sedang Berjalan</option>
                                    <option value="Selesai">Selesai</option>
                                </select>
                            </div>
                        </div>
                    </div>

                    {{-- Deskripsi --}}
                    <div class="space-y-2">
                        <label class="text-[10px] font-black text-gray-400 uppercase tracking-[0.2em] ml-1">Narasi Pemasaran <span class="text-rose-500">*</span></label>
                        <textarea name="deskripsi" rows="4" 
                            class="w-full p-5 bg-gray-50 border border-gray-100 rounded-3xl focus:ring-4 focus:ring-blue-50 focus:bg-white focus:border-blue-500 outline-none transition-all font-medium text-gray-600 leading-relaxed" 
                            placeholder="Jelaskan selling point proyek ini kepada konsumen..." required>{{ old('deskripsi') }}</textarea>
                    </div>

                    {{-- Upload Gambar dengan Preview yang Dipercantik --}}
                    <div class="space-y-4">
                        <label class="text-[10px] font-black text-gray-400 uppercase tracking-[0.2em] ml-1">Cover Proyek (Hero Image)</label>
                        <div class="relative">
                            <label for="gambar-input" class="flex flex-col items-center justify-center w-full h-80 border-4 border-gray-50 border-dashed rounded-[2rem] cursor-pointer bg-gray-50 hover:bg-white hover:border-blue-200 transition-all duration-300 overflow-hidden group">
                                
                                {{-- Preview Container --}}
                                <div id="preview-container" class="absolute inset-0 hidden z-10">
                                    <img id="image-preview" src="#" alt="Preview" class="w-full h-full object-cover">
                                    <div class="absolute inset-0 bg-black/40 flex items-center justify-center opacity-0 group-hover:opacity-100 transition-opacity backdrop-blur-sm">
                                        <div class="bg-white/20 p-4 rounded-2xl border border-white/30 text-white text-center">
                                            <i class="fas fa-sync-alt mb-2 text-2xl"></i>
                                            <p class="text-xs font-black uppercase tracking-widest">Ganti Gambar</p>
                                        </div>
                                    </div>
                                </div>

                                {{-- Placeholder --}}
                                <div id="upload-placeholder" class="flex flex-col items-center justify-center text-center px-6">
                                    <div class="w-20 h-20 bg-blue-100 text-blue-600 rounded-3xl flex items-center justify-center mb-4 group-hover:scale-110 transition-transform duration-500">
                                        <i class="fas fa-cloud-upload-alt text-3xl"></i>
                                    </div>
                                    <h4 class="text-gray-700 font-black text-lg">Pilih Foto Utama</h4>
                                    <p class="text-gray-400 text-sm mt-1 font-medium">Klik atau tarik file ke sini untuk mengunggah</p>
                                    <div class="mt-4 flex gap-2">
                                        <span class="px-3 py-1 bg-white border border-gray-100 rounded-lg text-[9px] font-black text-gray-400 uppercase tracking-tighter shadow-sm">JPG</span>
                                        <span class="px-3 py-1 bg-white border border-gray-100 rounded-lg text-[9px] font-black text-gray-400 uppercase tracking-tighter shadow-sm">PNG</span>
                                        <span class="px-3 py-1 bg-white border border-gray-100 rounded-lg text-[9px] font-black text-gray-400 uppercase tracking-tighter shadow-sm">Max 2MB</span>
                                    </div>
                                </div>
                                
                                <input type="file" name="gambar" id="gambar-input" class="hidden" accept="image/*" required />
                            </label>
                        </div>
                    </div>
                </div>

                {{-- Footer Action --}}
                <div class="p-8 bg-gray-50 border-t border-gray-50 flex flex-col md:flex-row items-center justify-end gap-4">
                    <a href="{{ route('admin.project.index') }}" class="w-full md:w-auto px-8 py-3.5 text-sm font-bold text-gray-400 hover:text-gray-600 transition-colors text-center">
                        Batalkan
                    </a>
                    <button type="submit" class="w-full md:w-auto bg-blue-600 hover:bg-blue-700 text-white px-12 py-4 rounded-2xl font-black shadow-xl shadow-blue-100 transition-all hover:-translate-y-1 active:scale-95 flex items-center justify-center">
                        <i class="fas fa-check-circle mr-3"></i> Simpan & Publikasikan
                    </button>
                </div>
            </div>
        </form>
    </div>
</div>

<script>
    document.getElementById('gambar-input').onchange = function (evt) {
        const [file] = this.files;
        if (file) {
            const preview = document.getElementById('image-preview');
            const container = document.getElementById('preview-container');
            const placeholder = document.getElementById('upload-placeholder');
            
            preview.src = URL.createObjectURL(file);
            container.classList.remove('hidden');
            placeholder.classList.add('hidden');
        }
    }
</script>
@endsection