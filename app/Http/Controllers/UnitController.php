<?php

namespace App\Http\Controllers;

use App\Models\Unit;
use App\Models\Project;
use App\Models\Tipe; // Pastikan menggunakan T kapital sesuai standar Laravel
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class UnitController extends Controller
{
    /**
     * SINKRONISASI STOK: Memperbarui statistik di tabel projects secara riil.
     */
    private function syncProjectStock($projectId)
    {
        $project = Project::find($projectId);
        if ($project) {
            $stats = Unit::where('project_id', $projectId)
                ->selectRaw("
                    count(case when status = 'Tersedia' then 1 end) as tersedia,
                    count(case when status = 'Dibooking' then 1 end) as booked,
                    count(case when status = 'Terjual' then 1 end) as terjual
                ")
                ->first();

            $project->update([
                'tersedia' => $stats->tersedia ?? 0,
                'booked'   => $stats->booked ?? 0,
                'terjual'  => $stats->terjual ?? 0,
            ]);
        }
    }

    /**
     * ADMIN - DAFTAR UNIT
     */
    public function index(Request $request)
    {
        $projects = Project::orderBy('nama_proyek')->get();
        $query = Unit::with(['project', 'tipe']);

        if ($request->filled('project_id')) {
            $query->where('project_id', $request->project_id);
        }
        
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        $units = $query->latest()->get(); 
        return view('unit.admin.index', compact('units', 'projects'));
    }

    /**
     * ADMIN - HALAMAN EDIT UNIT
     * Menambahkan fungsi yang sebelumnya menyebabkan error "Undefined method edit"
     */
    public function edit(Unit $unit)
    {
        $projects = Project::orderBy('nama_proyek')->get();
        // Mengambil tipe yang hanya tersedia untuk proyek unit tersebut
        $tipes = Tipe::where('project_id', $unit->project_id)->get();

        return view('unit.admin.edit', compact('unit', 'projects', 'tipes'));
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
        ], [
            'no_unit.unique' => 'Nomor unit ini sudah ada di blok dan proyek yang sama.'
        ]);

        try {
            DB::transaction(function () use ($validated, $request) {
                $validated['progres'] = 0; 
                Unit::create($validated);
                $this->syncProjectStock($request->project_id);
            });

            return redirect()->route('admin.unit.index')->with('success', 'Unit berhasil ditambahkan!');
        } catch (\Exception $e) {
            Log::error("Error Store Unit: " . $e->getMessage());
            return redirect()->back()->withInput()->with('error', 'Gagal menambah unit.');
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
                
                $this->syncProjectStock($request->project_id);
                
                if($oldProjectId != $request->project_id) {
                    $this->syncProjectStock($oldProjectId);
                }
            });

            return redirect()->route('admin.unit.index')->with('success', 'Data unit diperbarui!');
        } catch (\Exception $e) {
            Log::error("Error Update Unit: " . $e->getMessage());
            return redirect()->back()->withInput()->with('error', 'Gagal memperbarui unit.');
        }
    }

    /**
     * ADMIN - HAPUS UNIT
     */
    public function destroy(Unit $unit)
    {
        $projectId = $unit->project_id;

        if ($unit->status !== 'Tersedia') {
            return back()->with('error', 'Unit tidak bisa dihapus karena sudah berstatus ' . $unit->status);
        }

        try {
            DB::transaction(function () use ($unit, $projectId) {
                $unit->delete();
                $this->syncProjectStock($projectId);
            });

            return back()->with('success', 'Unit berhasil dihapus!');
        } catch (\Exception $e) {
            return back()->with('error', 'Gagal menghapus unit.');
        }
    }

    /**
     * AJAX - AMBIL TIPE BERDASARKAN PROYEK
     * Nama fungsi ini disesuaikan agar cocok dengan script AJAX di Blade Anda
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
        $projects = Project::latest()->get();
        return view('project.customer.index', compact('projects'));
    }
}