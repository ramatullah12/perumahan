@extends('layout.admin')

@section('content')
<div class="p-8 max-w-4xl mx-auto">
    <div class="bg-white p-10 rounded-[2.5rem] shadow-sm border border-gray-100">
        <h2 class="text-3xl font-black mb-6 text-gray-800">Tambah Tipe Rumah</h2>
        
        <form action="{{ route('admin.tipe.store') }}" method="POST" enctype="multipart/form-data">
            @csrf
            <div class="space-y-6">
                <div>
                    <label class="block text-xs font-bold text-gray-400 mb-2 uppercase">Pilih Proyek</label>
                    <select name="project_id" class="w-full p-4 bg-gray-50 border-none rounded-2xl outline-none" required>
                        <option value="" disabled selected>Pilih Proyek Perumahan</option>
                        @foreach($projects as $p)
                            <option value="{{ $p->id }}">{{ $p->nama_proyek }}</option>
                        @endforeach
                    </select>
                </div>
                
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <input type="text" name="nama_tipe" placeholder="Nama Tipe" class="p-4 bg-gray-50 border-none rounded-2xl outline-none" required>
                    <input type="number" name="harga" placeholder="Harga Jual" class="p-4 bg-gray-50 border-none rounded-2xl outline-none" required>
                </div>

                <div class="grid grid-cols-2 md:grid-cols-4 gap-4">
                    <input type="number" name="luas_tanah" placeholder="LT" class="p-4 bg-gray-50 border-none rounded-2xl outline-none" required>
                    <input type="number" name="luas_bangunan" placeholder="LB" class="p-4 bg-gray-50 border-none rounded-2xl outline-none" required>
                    <input type="number" name="kamar_tidur" placeholder="Kamar" class="p-4 bg-gray-50 border-none rounded-2xl outline-none" required>
                    <input type="number" name="kamar_mandi" placeholder="Mandi" class="p-4 bg-gray-50 border-none rounded-2xl outline-none" required>
                </div>

                <div class="p-6 border-2 border-dashed border-gray-100 rounded-[2rem] bg-gray-50/50">
                    <input type="file" name="gambar" accept="image/*" class="w-full text-sm text-gray-400 file:bg-blue-600 file:text-white file:rounded-full file:border-0 file:px-4 file:py-2" required>
                </div>

                <button type="submit" class="w-full bg-blue-600 text-white p-4 rounded-2xl font-bold shadow-lg">Simpan Tipe</button>
            </div>
        </form>
    </div>
</div>
@endsection