<?php

namespace App\Http\Controllers;

use App\Models\Unit;
use App\Models\Project;
use App\Models\Tipe;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\DB;

class UnitController extends Controller
{
    /**
     * SINKRONISASI STOK: Memperbarui statistik di tabel projects secara riil.
     */
    private function syncProjectStock($projectId)
    {
        $project = Project::find($projectId);
        if ($project) {
            $project->update([
                'tersedia' => Unit::where('project_id', $projectId)->where('status', 'Tersedia')->count(),
                'booked'   => Unit::where('project_id', $projectId)->where('status', 'Dibooking')->count(),
                'terjual'  => Unit::where('project_id', $projectId)->where('status', 'Terjual')->count(),
            ]);
        }
    }

    /**
     * ADMIN - DAFTAR UNIT
     */
    public function index(Request $request)
    {
        $projects = Project::all();
        // Menggunakan Eager Loading agar tidak berat saat loading data di Vercel
        $query = Unit::with(['project', 'tipe']);

        if ($request->filled('project_id')) $query->where('project_id', $request->project_id);
        if ($request->filled('status')) $query->where('status', $request->status);

        $units = $query->latest()->get(); 
        return view('unit.admin.index', compact('units', 'projects'));
    }

    /**
     * ADMIN - SIMPAN UNIT BARU
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'project_id' => 'required|exists:projects,id',
            'tipe_id'    => 'required|exists:tipes,id',
            'block'      => 'required|string|max:10',
            'harga'      => 'required|numeric|min:0', 
            'no_unit'    => [ 
                'required', 'string', 'max:10',
                Rule::unique('units')->where(fn($q) => 
                    $q->where('project_id', $request->project_id)->where('block', $request->block)
                ),
            ],
            'status'     => 'required|in:Tersedia,Dibooking,Terjual'
        ]);

        try {
            DB::transaction(function () use ($validated, $request) {
                $validated['progres'] = 0; 
                Unit::create($validated);
                $this->syncProjectStock($request->project_id);
            });

            return redirect()->route('admin.unit.index')->with('success', 'Unit berhasil ditambahkan!');
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Gagal menambah unit: ' . $e->getMessage());
        }
    }

    /**
     * ADMIN - UPDATE DATA UNIT
     */
    public function update(Request $request, Unit $unit)
    {
        $oldProjectId = $unit->project_id; 

        $validated = $request->validate([
            'project_id' => 'required|exists:projects,id',
            'tipe_id'    => 'required|exists:tipes,id',
            'block'      => 'required|string|max:10',
            'harga'      => 'required|numeric|min:0', 
            'progres'    => 'required|integer|min:0|max:100', 
            'no_unit'    => [ 
                'required', 'string', 'max:10',
                Rule::unique('units')->ignore($unit->id)->where(fn($q) => 
                    $q->where('project_id', $request->project_id)->where('block', $request->block)
                ),
            ],
            'status'     => 'required|in:Tersedia,Dibooking,Terjual'
        ]);

        try {
            DB::transaction(function () use ($validated, $unit, $request, $oldProjectId) {
                $unit->update($validated);
                
                // Sync stok proyek baru
                $this->syncProjectStock($request->project_id);
                
                // Jika proyek diganti, sync stok proyek lama juga
                if($oldProjectId != $request->project_id) {
                    $this->syncProjectStock($oldProjectId);
                }
            });

            return redirect()->route('admin.unit.index')->with('success', 'Data unit diperbarui!');
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Gagal memperbarui unit: ' . $e->getMessage());
        }
    }

    /**
     * ADMIN - HAPUS UNIT
     */
    public function destroy(Unit $unit)
    {
        $projectId = $unit->project_id;

        // Proteksi agar data yang sudah dibayar tidak hilang dari record
        if ($unit->status !== 'Tersedia') {
            return back()->with('error', 'Unit yang sudah dibooking/terjual tidak bisa dihapus!');
        }

        try {
            DB::transaction(function () use ($unit, $projectId) {
                $unit->delete();
                $this->syncProjectStock($projectId);
            });

            return back()->with('success', 'Unit berhasil dihapus!');
        } catch (\Exception $e) {
            return back()->with('error', 'Gagal menghapus unit: ' . $e->getMessage());
        }
    }

    /**
     * AJAX - AMBIL TIPE BERDASARKAN PROYEK
     */
    public function getTipeByProject($projectId)
    {
        $tipes = Tipe::where('project_id', $projectId)
            ->orderBy('nama_tipe')
            ->get(['id', 'nama_tipe']);
            
        return response()->json($tipes);
    }

    /**
     * CUSTOMER - JELAJAHI PROYEK
     */
    public function jelajahiProyek()
    {
        // Menampilkan proyek terbaru di sisi customer
        $projects = Project::latest()->get();

        // Pastikan path view sesuai dengan struktur folder Anda
        return view('project.customer.index', compact('projects'));
    }
}