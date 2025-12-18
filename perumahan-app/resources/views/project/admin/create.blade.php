@extends('dashboard.admin')

@section('content')
<div class="p-4 md:p-8 bg-[#F9FAFB] min-h-screen">
    <div class="max-w-4xl mx-auto mb-6">
        <nav class="flex mb-4 text-sm" aria-label="Breadcrumb">
            <ol class="inline-flex items-center space-x-2">
                <li class="text-gray-500">Manajemen Proyek</li>
                <li class="text-gray-400">/</li>
                <li class="text-blue-600 font-medium">Tambah Baru</li>
            </ol>
        </nav>
        <h2 class="text-3xl font-extrabold text-gray-900 tracking-tight">Tambah Proyek Baru</h2>
        <p class="text-gray-500 mt-1">Lengkapi informasi di bawah ini untuk mempublikasikan proyek perumahan baru.</p>
    </div>

    <div class="max-w-4xl mx-auto">
        @if ($errors->any())
        <div class="mb-6 p-4 bg-red-50 border-l-4 border-red-500 rounded-r-lg">
            <div class="flex">
                <div class="flex-shrink-0">
                    <svg class="h-5 w-5 text-red-400" viewBox="0 0 20 20" fill="currentColor">
                        <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd" />
                    </svg>
                </div>
                <div class="ml-3">
                    <h3 class="text-sm font-medium text-red-800">Terdapat beberapa kesalahan input:</h3>
                    <ul class="mt-1 text-sm text-red-700 list-disc list-inside">
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
            
            <div class="bg-white rounded-2xl shadow-sm border border-gray-200 overflow-hidden">
                <div class="p-8 space-y-6">
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
                        <div class="space-y-2">
                            <label class="text-sm font-bold text-gray-700 uppercase tracking-wider">Nama Proyek</label>
                            <input type="text" name="nama_proyek" value="{{ old('nama_proyek') }}" placeholder="" 
                                class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-blue-500 outline-none transition-all duration-200" required>
                        </div>

                        <div class="space-y-2">
                            <label class="text-sm font-bold text-gray-700 uppercase tracking-wider">Total Unit</label>
                            <div class="relative">
                                <input type="number" name="total_unit" value="{{ old('total_unit') }}" placeholder="0" 
                                    class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-blue-500 outline-none transition-all duration-200" required>
                                <span class="absolute right-4 top-3 text-gray-400"></span>
                            </div>
                        </div>
                    </div>

                    <div class="space-y-2">
                        <label class="text-sm font-bold text-gray-700 uppercase tracking-wider">Lokasi Strategis</label>
                        <div class="relative">
                            <span class="absolute left-4 top-3.5 text-gray-400">
                                <i class="fas fa-map-marker-alt"></i>
                            </span>
                            <input type="text" name="lokasi" value="{{ old('lokasi') }}" placeholder="Contoh: Borang, Mata Merah" 
                                class="w-full pl-10 pr-4 py-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-blue-500 outline-none transition-all duration-200" required>
                        </div>
                    </div>

                    <div class="space-y-2">
                        <label class="text-sm font-bold text-gray-700 uppercase tracking-wider">Deskripsi Lengkap</label>
                        <textarea name="deskripsi" rows="5" placeholder="Gambarkan keunggulan, fasilitas, dan konsep perumahan..." 
                            class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-blue-500 outline-none transition-all duration-200" required>{{ old('deskripsi') }}</textarea>
                    </div>

                    <div class="space-y-2">
                        <label class="text-sm font-bold text-gray-700 uppercase tracking-wider">Foto Utama Proyek</label>
                        <div class="flex items-center justify-center w-full">
                            <label class="flex flex-col items-center justify-center w-full h-64 border-2 border-gray-300 border-dashed rounded-2xl cursor-pointer bg-gray-50 hover:bg-gray-100 transition-all duration-200 overflow-hidden relative">
                                <div id="preview-container" class="absolute inset-0 hidden">
                                    <img id="image-preview" src="#" alt="Preview" class="w-full h-full object-cover">
                                    <div class="absolute inset-0 bg-black bg-opacity-40 flex items-center justify-center opacity-0 hover:opacity-100 transition-opacity">
                                        <p class="text-white text-sm font-semibold">Ganti Gambar</p>
                                    </div>
                                </div>
                                <div id="upload-placeholder" class="flex flex-col items-center justify-center pt-5 pb-6">
                                    <svg class="w-10 h-10 mb-3 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 16a4 4 0 01-.88-7.903A5 5 0 1115.9 6L16 6a5 5 0 011 9.9M15 13l-3-3m0 0l-3 3m3-3v12"></path>
                                    </svg>
                                    <p class="mb-2 text-sm text-gray-500 font-semibold text-center px-4">Klik untuk unggah atau seret gambar ke sini</p>
                                    <p class="text-xs text-gray-400 uppercase tracking-tighter">PNG, JPG atau JPEG (Max. 2MB)</p>
                                </div>
                                <input type="file" name="gambar" id="gambar-input" class="hidden" accept="image/*" required />
                            </label>
                        </div>
                    </div>
                </div>

                <div class="p-6 bg-gray-50 border-t border-gray-100 flex items-center justify-end gap-4">
                    <a href="{{ route('admin.project.index') }}" class="px-6 py-2.5 text-sm font-bold text-gray-600 hover:text-gray-800 transition-colors">
                        Batal
                    </a>
                    <button type="submit" class="bg-blue-600 hover:bg-blue-700 text-white px-10 py-2.5 rounded-xl font-bold shadow-lg shadow-blue-200 transition-all active:scale-95 flex items-center">
                        <i class="fas fa-save mr-2"></i> Publikasikan Proyek
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