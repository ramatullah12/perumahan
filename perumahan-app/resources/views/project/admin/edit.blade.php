@extends('dashboard.admin')

@section('content')
<div class="p-8 bg-gray-50 min-h-screen">
    <div class="max-w-3xl mx-auto bg-white p-8 rounded-3xl shadow-sm border border-gray-100">
        <div class="mb-8">
            <h2 class="text-2xl font-black text-gray-800 tracking-tight">Edit Proyek</h2>
            <p class="text-gray-500 text-sm">Perbarui informasi untuk proyek: <span class="font-bold text-blue-600">{{ $project->nama_proyek }}</span></p>
        </div>

        <form action="{{ route('admin.project.update', $project->id) }}" method="POST" enctype="multipart/form-data" class="space-y-6">
            @csrf
            @method('PUT') {{-- WAJIB untuk proses Update --}}

            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div>
                    <label class="block text-xs font-black uppercase tracking-widest text-gray-400 mb-2">Nama Proyek</label>
                    <input type="text" name="nama_proyek" value="{{ old('nama_proyek', $project->nama_proyek) }}" class="w-full p-3 border rounded-xl focus:ring-2 focus:ring-blue-500 outline-none" required>
                </div>
                <div>
                    <label class="block text-xs font-black uppercase tracking-widest text-gray-400 mb-2">Lokasi</label>
                    <input type="text" name="lokasi" value="{{ old('lokasi', $project->lokasi) }}" class="w-full p-3 border rounded-xl focus:ring-2 focus:ring-blue-500 outline-none" required>
                </div>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div>
                    <label class="block text-xs font-black uppercase tracking-widest text-gray-400 mb-2">Total Unit</label>
                    <input type="number" name="total_unit" value="{{ old('total_unit', $project->total_unit) }}" class="w-full p-3 border rounded-xl focus:ring-2 focus:ring-blue-500 outline-none" required>
                </div>
                <div>
                    <label class="block text-xs font-black uppercase tracking-widest text-gray-400 mb-2">Status Proyek</label>
                    <select name="status" class="w-full p-3 border rounded-xl focus:ring-2 focus:ring-blue-500 outline-none">
                        <option value="Sedang Berjalan" {{ $project->status == 'Sedang Berjalan' ? 'selected' : '' }}>Sedang Berjalan</option>
                        <option value="Selesai" {{ $project->status == 'Selesai' ? 'selected' : '' }}>Selesai</option>
                    </select>
                </div>
            </div>

            <div>
                <label class="block text-xs font-black uppercase tracking-widest text-gray-400 mb-2">Deskripsi</label>
                <textarea name="deskripsi" rows="4" class="w-full p-3 border rounded-xl focus:ring-2 focus:ring-blue-500 outline-none" required>{{ old('deskripsi', $project->deskripsi) }}</textarea>
            </div>

            <div>
                <label class="block text-xs font-black uppercase tracking-widest text-gray-400 mb-2 text-red-500">Ganti Foto Proyek (Kosongkan jika tidak diganti)</label>
                <input type="file" name="gambar" class="w-full p-2 border rounded-xl text-sm mb-2">
                
                {{-- Preview Gambar Lama --}}
                <div class="mt-4 p-4 bg-gray-50 rounded-2xl border border-dashed border-gray-200">
                    <p class="text-[10px] font-bold text-gray-400 uppercase mb-2 text-center">Foto Saat Ini</p>
                    <img src="{{ asset('storage/' . $project->gambar) }}" class="w-48 h-32 object-cover rounded-xl mx-auto shadow-sm" onerror="this.src='https://placehold.co/600x400?text=Gambar+Rusak';">
                </div>
            </div>

            <div class="flex gap-4 pt-6">
                <button type="submit" class="bg-blue-600 text-white px-8 py-3 rounded-xl font-bold hover:bg-blue-700 transition shadow-lg shadow-blue-100">Simpan Perubahan</button>
                <a href="{{ route('admin.project.index') }}" class="bg-gray-100 text-gray-600 px-8 py-3 rounded-xl font-bold hover:bg-gray-200 transition">Batal</a>
            </div>
        </form>
    </div>
</div>
@endsection