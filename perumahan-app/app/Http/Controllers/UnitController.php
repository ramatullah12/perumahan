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
     * CUSTOMER: Menampilkan daftar proyek beserta statistik unitnya.
     * PERBAIKAN: Method ini wajib ada agar halaman customer tidak error 500.
     */
    public function jelajahiProyek()
    {
        $projects = Project::withCount([
            'units as tersedia_count' => fn($q) => $q->where('status', 'Tersedia'),
            'units as booked_count'   => fn($q) => $q->where('status', 'Dibooking'),
            'units as terjual_count'  => fn($q) => $q->where('status', 'Terjual')
        ])->latest()->get();

        return view('project.customer.index', compact('projects'));
    }

    public function index(Request $request)
    {
        $projects = Project::all();
        $query = Unit::with(['project', 'tipe']);

        if ($request->filled('project_id')) $query->where('project_id', $request->project_id);
        if ($request->filled('status')) $query->where('status', $request->status);

        $units = $query->latest()->get(); 
        return view('unit.admin.index', compact('units', 'projects'));
    }

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

        DB::transaction(function () use ($validated, $request) {
            $validated['progres'] = 0; 
            Unit::create($validated);
            $this->syncProjectStock($request->project_id);
        });

        return redirect()->route('admin.unit.index')->with('success', 'Unit berhasil ditambahkan!');
    }

    public function edit(Unit $unit)
    {
        $projects = Project::all();
        $tipes = Tipe::where('project_id', $unit->project_id)->get();
        
        return view('unit.admin.edit', compact('unit', 'projects', 'tipes'));
    }

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

        DB::transaction(function () use ($validated, $unit, $request, $oldProjectId) {
            $unit->update($validated);
            $this->syncProjectStock($request->project_id);
            if($oldProjectId != $request->project_id) {
                $this->syncProjectStock($oldProjectId);
            }
        });

        return redirect()->route('admin.unit.index')->with('success', 'Data unit diperbarui!');
    }

    public function destroy(Unit $unit)
    {
        $projectId = $unit->project_id;

        if ($unit->status !== 'Tersedia') {
            return back()->with('error', 'Unit yang sudah dibooking/terjual tidak bisa dihapus!');
        }

        DB::transaction(function () use ($unit, $projectId) {
            $unit->delete();
            $this->syncProjectStock($projectId);
        });

        return back()->with('success', 'Unit berhasil dihapus!');
    }

    public function getTipeByProject($projectId)
    {
        $tipes = Tipe::where('project_id', $projectId)->orderBy('nama_tipe')->get(['id', 'nama_tipe']);
        return response()->json($tipes);
    }
}