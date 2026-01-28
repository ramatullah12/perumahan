@extends('layout.admin')

@section('content')
<div class="p-8 bg-gray-50 min-h-screen">
    <div class="max-w-4xl mx-auto">
        {{-- Breadcrumb & Back --}}
        <a href="{{ route('admin.project.index') }}" class="inline-flex items-center text-sm font-bold text-gray-400 hover:text-blue-600 mb-6 transition-colors group">
            <i class="fas fa-arrow-left mr-2 group-hover:-translate-x-1 transition-transform"></i> Kembali ke Daftar Proyek
        </a>

        <div class="bg-white p-10 rounded-[2.5rem] shadow-sm border border-gray-100 relative overflow-hidden">
            {{-- Aksen Dekoratif --}}
            <div class="absolute top-0 right-0 w-32 h-32 bg-blue-50 rounded-bl-full opacity-50 -mr-10 -mt-10"></div>

            <div class="mb-10 relative">
                <h2 class="text-3xl font-black text-gray-800 tracking-tight">Edit Proyek</h2>
                <p class="text-gray-500 font-medium mt-1">Mengubah detail proyek: <span class="text-blue-600 font-extrabold uppercase tracking-wider">{{ $project->nama_proyek }}</span></p>
            </div>

            <form action="{{ route('admin.project.update', $project->id) }}" method="POST" enctype="multipart/form-data" class="space-y-8">
                @csrf
                @method('PUT')

                <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
                    {{-- Nama Proyek --}}
                    <div class="space-y-2">
                        <label class="block text-[10px] font-black uppercase tracking-[0.2em] text-gray-400 ml-1">Nama Proyek</label>
                        <div class="relative">
                            <span class="absolute inset-y-0 left-0 flex items-center pl-4 text-gray-400">
                                <i class="fas fa-building"></i>
                            </span>
                            <input type="text" name="nama_proyek" value="{{ old('nama_proyek', $project->nama_proyek) }}" 
                                class="w-full pl-11 pr-4 py-3.5 bg-gray-50 border border-gray-100 rounded-2xl focus:ring-4 focus:ring-blue-100 focus:bg-white focus:border-blue-500 outline-none transition-all font-bold text-gray-700" required>
                        </div>
                    </div>

                    {{-- Lokasi --}}
                    <div class="space-y-2">
                        <label class="block text-[10px] font-black uppercase tracking-[0.2em] text-gray-400 ml-1">Lokasi Proyek</label>
                        <div class="relative">
                            <span class="absolute inset-y-0 left-0 flex items-center pl-4 text-gray-400">
                                <i class="fas fa-map-marker-alt"></i>
                            </span>
                            <input type="text" name="lokasi" value="{{ old('lokasi', $project->lokasi) }}" 
                                class="w-full pl-11 pr-4 py-3.5 bg-gray-50 border border-gray-100 rounded-2xl focus:ring-4 focus:ring-blue-100 focus:bg-white focus:border-blue-500 outline-none transition-all font-bold text-gray-700" required>
                        </div>
                    </div>

                    {{-- Total Unit --}}
                    <div class="space-y-2">
                        <label class="block text-[10px] font-black uppercase tracking-[0.2em] text-gray-400 ml-1">Target Total Unit</label>
                        <div class="relative">
                            <span class="absolute inset-y-0 left-0 flex items-center pl-4 text-gray-400">
                                <i class="fas fa-th-large"></i>
                            </span>
                            <input type="number" name="total_unit" value="{{ old('total_unit', $project->total_unit) }}" 
                                class="w-full pl-11 pr-4 py-3.5 bg-gray-50 border border-gray-100 rounded-2xl focus:ring-4 focus:ring-blue-100 focus:bg-white focus:border-blue-500 outline-none transition-all font-bold text-gray-700" required>
                        </div>
                    </div>

                    {{-- Status --}}
                    <div class="space-y-2">
                        <label class="block text-[10px] font-black uppercase tracking-[0.2em] text-gray-400 ml-1">Status Konstruksi</label>
                        <div class="relative">
                            <span class="absolute inset-y-0 left-0 flex items-center pl-4 text-gray-400 pointer-events-none">
                                <i class="fas fa-tasks"></i>
                            </span>
                            <select name="status" class="w-full pl-11 pr-4 py-3.5 bg-gray-50 border border-gray-100 rounded-2xl focus:ring-4 focus:ring-blue-100 focus:bg-white focus:border-blue-500 outline-none transition-all font-bold text-gray-700 appearance-none">
                                <option value="Pre-Launch" {{ $project->status == 'Pre-Launch' ? 'selected' : '' }}>Pre-Launch</option>
                                <option value="Sedang Berjalan" {{ $project->status == 'Sedang Berjalan' ? 'selected' : '' }}>Sedang Berjalan</option>
                                <option value="Selesai" {{ $project->status == 'Selesai' ? 'selected' : '' }}>Selesai</option>
                            </select>
                        </div>
                    </div>
                </div>

                {{-- Deskripsi --}}
                <div class="space-y-2">
                    <label class="block text-[10px] font-black uppercase tracking-[0.2em] text-gray-400 ml-1">Deskripsi Proyek</label>
                    <textarea name="deskripsi" rows="4" 
                        class="w-full p-4 bg-gray-50 border border-gray-100 rounded-2xl focus:ring-4 focus:ring-blue-100 focus:bg-white focus:border-blue-500 outline-none transition-all font-medium text-gray-600 leading-relaxed" required>{{ old('deskripsi', $project->deskripsi) }}</textarea>
                </div>

                {{-- Image Upload Section --}}
                <div class="pt-4">
                    <label class="block text-[10px] font-black uppercase tracking-[0.2em] text-gray-400 mb-4 ml-1">Visual Proyek</label>
                    <div class="grid grid-cols-1 lg:grid-cols-2 gap-8 items-center bg-gray-50 p-6 rounded-[2rem] border border-gray-100">
                        
                        <div class="space-y-4">
                            <p class="text-sm text-gray-500 leading-relaxed font-medium">Unggah foto terbaru jika ingin mengganti visual proyek. Gunakan rasio 16:9 untuk hasil terbaik.</p>
                            <input type="file" name="gambar" id="imgInput" 
                                class="block w-full text-sm text-gray-500 file:mr-4 file:py-2.5 file:px-6 file:rounded-xl file:border-0 file:text-xs file:font-black file:uppercase file:tracking-widest file:bg-blue-600 file:text-white hover:file:bg-blue-700 transition-all cursor-pointer">
                        </div>

                        <div class="relative group">
                            <p class="absolute -top-3 left-1/2 -translate-x-1/2 bg-white px-4 py-1 rounded-full text-[9px] font-black text-gray-400 border border-gray-100 shadow-sm z-10 uppercase tracking-widest">Preview Visual</p>
                            <div class="w-full h-48 rounded-2xl overflow-hidden border-4 border-white shadow-xl relative">
                                <img id="imgPreview" src="{{ asset('storage/' . $project->gambar) }}" 
                                    class="w-full h-full object-cover transition-transform duration-500 group-hover:scale-105"
                                    onerror="this.src='https://placehold.co/800x450?text=Gambar+Kosong';">
                                <div class="absolute inset-0 bg-black/20 group-hover:bg-transparent transition-colors"></div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="flex flex-col md:flex-row gap-4 pt-6">
                    <button type="submit" class="flex-1 bg-blue-600 text-white px-10 py-4 rounded-2xl font-bold hover:bg-blue-700 transition shadow-xl shadow-blue-100 hover:-translate-y-1 active:scale-95">
                        <i class="fas fa-save mr-2"></i> Perbarui Data Proyek
                    </button>
                    <a href="{{ route('admin.project.index') }}" class="px-10 py-4 rounded-2xl font-bold text-gray-500 bg-white border border-gray-200 hover:bg-gray-50 transition text-center">
                        Batal
                    </a>
                </div>
            </form>
        </div>
    </div>
</div>

{{-- Image Preview Script --}}
<script>
    imgInput.onchange = evt => {
        const [file] = imgInput.files
        if (file) {
            imgPreview.src = URL.createObjectURL(file)
        }
    }
</script>
@endsection