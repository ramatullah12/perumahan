@extends('layout.admin')

@section('content')
<div class="p-4 md:p-8 bg-gray-50 min-h-screen">
    <div class="max-w-4xl mx-auto">
        {{-- Header & Back Button --}}
        <div class="mb-8 flex items-center justify-between">
            <div>
                <a href="{{ route('admin.tipe.index') }}" class="text-blue-600 font-bold text-sm flex items-center mb-2 hover:underline">
                    <i class="fas fa-arrow-left mr-2"></i> Kembali ke Daftar Tipe
                </a>
                <h2 class="text-3xl font-black text-gray-800 tracking-tight">Perbarui Spesifikasi Tipe</h2>
            </div>
            <div class="hidden md:block">
                <span class="bg-blue-100 text-blue-700 px-4 py-2 rounded-xl text-xs font-black uppercase tracking-widest">
                    ID Tipe: #{{ $tipe->id }}
                </span>
            </div>
        </div>
        
        <div class="bg-white p-8 md:p-12 rounded-[2.5rem] shadow-sm border border-gray-100">
            <form action="{{ route('admin.tipe.update', $tipe->id) }}" method="POST" enctype="multipart/form-data" class="space-y-8">
                @csrf
                @method('PUT')

                {{-- Pilih Proyek --}}
                <div class="space-y-2">
                    <label class="block text-[10px] font-black text-gray-400 mb-1 uppercase tracking-[0.2em] ml-1">Koneksi Proyek</label>
                    <div class="relative">
                        <select name="project_id" class="w-full p-4 bg-gray-50 border border-transparent rounded-2xl outline-none focus:bg-white focus:border-blue-500 transition-all font-bold text-gray-700 appearance-none">
                            @foreach($projects as $p)
                                <option value="{{ $p->id }}" {{ $tipe->project_id == $p->id ? 'selected' : '' }}>{{ $p->nama_proyek }}</option>
                            @endforeach
                        </select>
                        <div class="absolute inset-y-0 right-0 flex items-center pr-4 pointer-events-none text-gray-400">
                            <i class="fas fa-chevron-down text-xs"></i>
                        </div>
                    </div>
                </div>
                
                {{-- Nama Tipe & Harga --}}
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div class="space-y-2">
                        <label class="block text-[10px] font-black text-gray-400 mb-1 uppercase tracking-[0.2em] ml-1">Nama Tipe Rumah</label>
                        <input type="text" name="nama_tipe" value="{{ old('nama_tipe', $tipe->nama_tipe) }}" 
                            class="w-full p-4 bg-gray-50 border border-transparent rounded-2xl outline-none focus:bg-white focus:border-blue-500 transition-all font-bold text-gray-700" placeholder="Contoh: Type 36/72">
                    </div>
                    <div class="space-y-2">
                        <label class="block text-[10px] font-black text-gray-400 mb-1 uppercase tracking-[0.2em] ml-1">Harga Unit (Rp)</label>
                        <input type="number" name="harga" value="{{ old('harga', $tipe->harga) }}" 
                            class="w-full p-4 bg-gray-50 border border-transparent rounded-2xl outline-none focus:bg-white focus:border-blue-500 transition-all font-bold text-gray-700" placeholder="0">
                    </div>
                </div>

                {{-- Spesifikasi Teknis --}}
                <div class="grid grid-cols-2 md:grid-cols-4 gap-4">
                    <div class="space-y-2">
                        <label class="block text-[10px] font-black text-gray-400 uppercase tracking-tighter">Luas Tanah (m²)</label>
                        <input type="number" name="luas_tanah" value="{{ old('luas_tanah', $tipe->luas_tanah) }}" class="w-full p-4 bg-gray-50 border border-transparent rounded-2xl outline-none focus:bg-white focus:border-blue-500 transition-all font-bold text-gray-700 text-center">
                    </div>
                    <div class="space-y-2">
                        <label class="block text-[10px] font-black text-gray-400 uppercase tracking-tighter">Luas Bangunan (m²)</label>
                        <input type="number" name="luas_bangunan" value="{{ old('luas_bangunan', $tipe->luas_bangunan) }}" class="w-full p-4 bg-gray-50 border border-transparent rounded-2xl outline-none focus:bg-white focus:border-blue-500 transition-all font-bold text-gray-700 text-center">
                    </div>
                    <div class="space-y-2">
                        <label class="block text-[10px] font-black text-gray-400 uppercase tracking-tighter">Kamar Tidur</label>
                        <input type="number" name="kamar_tidur" value="{{ old('kamar_tidur', $tipe->kamar_tidur) }}" class="w-full p-4 bg-gray-50 border border-transparent rounded-2xl outline-none focus:bg-white focus:border-blue-500 transition-all font-bold text-gray-700 text-center">
                    </div>
                    <div class="space-y-2">
                        <label class="block text-[10px] font-black text-gray-400 uppercase tracking-tighter">Kamar Mandi</label>
                        <input type="number" name="kamar_mandi" value="{{ old('kamar_mandi', $tipe->kamar_mandi) }}" class="w-full p-4 bg-gray-50 border border-transparent rounded-2xl outline-none focus:bg-white focus:border-blue-500 transition-all font-bold text-gray-700 text-center">
                    </div>
                </div>

                {{-- Image Manager --}}
                <div class="space-y-4">
                    <label class="block text-[10px] font-black text-gray-400 mb-1 uppercase tracking-[0.2em] ml-1">Visual Preview Tipe</label>
                    <div class="p-8 border-4 border-dashed border-gray-50 rounded-[2.5rem] bg-gray-50/30 flex flex-col md:flex-row items-center gap-8">
                        <div class="relative group w-full md:w-64">
                            <img id="preview" src="{{ asset('storage/'.$tipe->gambar) }}" 
                                class="w-full h-40 object-cover rounded-3xl shadow-lg border-4 border-white transition-transform group-hover:scale-105"
                                onerror="this.src='https://placehold.co/600x400?text=No+Image';">
                            <div class="absolute -top-3 -right-3 bg-emerald-500 text-white p-2 rounded-full shadow-lg text-[8px]">
                                <i class="fas fa-check"></i>
                            </div>
                        </div>
                        <div class="flex-1 space-y-4 text-center md:text-left">
                            <p class="text-sm text-gray-500 font-medium">Unggah denah atau fasad baru untuk mengganti gambar lama.</p>
                            <input type="file" name="gambar" id="imgInput" 
                                class="block w-full text-xs text-gray-400 file:mr-4 file:py-2.5 file:px-6 file:rounded-xl file:border-0 file:text-[10px] file:font-black file:uppercase file:bg-blue-50 file:text-blue-600 hover:file:bg-blue-100 transition-all cursor-pointer">
                        </div>
                    </div>
                </div>

                {{-- Actions --}}
                <div class="pt-6 flex flex-col md:flex-row gap-4">
                    <button type="submit" class="flex-1 bg-blue-600 text-white p-5 rounded-2xl font-black uppercase tracking-widest text-xs shadow-xl shadow-blue-100 hover:bg-blue-700 hover:-translate-y-1 active:scale-95 transition-all">
                        <i class="fas fa-save mr-2"></i> Simpan Perubahan
                    </button>
                    <a href="{{ route('admin.tipe.index') }}" class="px-10 py-5 rounded-2xl font-bold text-gray-400 bg-white border border-gray-100 hover:bg-gray-50 transition-all text-center text-xs uppercase tracking-widest">
                        Batal
                    </a>
                </div>
            </div>
        </form>
    </div>
</div>

<script>
    // Live Preview Script
    imgInput.onchange = evt => {
        const [file] = imgInput.files
        if (file) {
            preview.src = URL.createObjectURL(file)
        }
    }
</script>
@endsection