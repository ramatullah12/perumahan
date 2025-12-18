@extends('dashboard.admin')

@section('content')
<div class="p-8 max-w-4xl mx-auto">
    <div class="bg-white p-10 rounded-[2.5rem] shadow-sm border border-gray-100">
        <h2 class="text-3xl font-black mb-6 text-gray-800">Edit Tipe</h2>
        
        <form action="{{ route('admin.tipe.update', $tipe->id) }}" method="POST" enctype="multipart/form-data">
            @csrf
            @method('PUT')
            <div class="space-y-6">
                <div>
                    <label class="block text-xs font-bold text-gray-400 mb-2 uppercase">Pilih Proyek</label>
                    <select name="project_id" class="w-full p-4 bg-gray-50 border-none rounded-2xl outline-none">
                        @foreach($projects as $p)
                            <option value="{{ $p->id }}" {{ $tipe->project_id == $p->id ? 'selected' : '' }}>{{ $p->nama_proyek }}</option>
                        @endforeach
                    </select>
                </div>
                
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <input type="text" name="nama_tipe" value="{{ $tipe->nama_tipe }}" class="p-4 bg-gray-50 border-none rounded-2xl outline-none">
                    <input type="number" name="harga" value="{{ $tipe->harga }}" class="p-4 bg-gray-50 border-none rounded-2xl outline-none">
                </div>

                <div class="p-6 border-2 border-dashed border-gray-100 rounded-[2rem] bg-gray-50/50 text-center">
                    <img src="{{ asset('storage/'.$tipe->gambar) }}" class="h-32 mx-auto mb-4 rounded-xl">
                    <input type="file" name="gambar" class="w-full text-sm text-gray-400">
                </div>

                <button type="submit" class="w-full bg-blue-600 text-white p-4 rounded-2xl font-bold">Update Tipe</button>
            </div>
        </form>
    </div>
</div>
@endsection
