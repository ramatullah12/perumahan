<?php

namespace App\Http\Controllers;

use App\Models\Unit;
use App\Models\Project;
use App\Models\Tipe;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class UnitController extends Controller
{
    /**
     * Menampilkan daftar unit dengan fitur filter proyek dan status.
     */
    public function index(Request $request)
    {
        $projects = Project::all();
        
        // Eager loading relasi agar query efisien (mencegah N+1 problem)
        $query = Unit::with(['project', 'tipe']);

        // Filter berdasarkan Proyek
        if ($request->filled('project_id')) {
            $query->where('project_id', $request->project_id);
        }

        // Filter berdasarkan Status
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        $units = $query->latest()->get();

        return view('unit.admin.index', compact('units', 'projects'));
    }

    /**
     * Form tambah unit.
     */
    public function create()
    {
        $projects = Project::all();
        // Tipe dikosongkan dulu, akan diisi via AJAX berdasarkan proyek yang dipilih
        return view('unit.admin.create', compact('projects'));
    }

    /**
     * Menyimpan unit baru dengan validasi keamanan.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'project_id' => 'required|exists:projects,id',
            'tipe_id'    => 'required|exists:tipes,id',
            'block'      => 'required|string|max:10',
            // Validasi: No Unit tidak boleh sama dalam Proyek & Blok yang sama
            'no_unit'    => [
                'required', 'string', 'max:10',
                Rule::unique('units')->where(function ($query) use ($request) {
                    return $query->where('project_id', $request->project_id)
                                 ->where('block', $request->block);
                }),
            ],
            'status'     => 'required|in:Tersedia,Dibooking,Terjual'
        ], [
            'no_unit.unique' => 'Nomor unit ini sudah terdaftar di blok tersebut.'
        ]);

        Unit::create($validated);

        return redirect()->route('admin.unit.index')->with('success', 'Unit berhasil ditambahkan!');
    }

    /**
     * Menampilkan detail unit.
     */
    public function show(Unit $unit)
    {
        return view('unit.admin.show', compact('unit'));
    }

    /**
     * Edit data unit.
     */
    public function edit(Unit $unit)
    {
        $projects = Project::all();
        // Hanya ambil tipe yang sesuai dengan proyek unit ini
        $tipes = Tipe::where('project_id', $unit->project_id)->get();
        
        return view('unit.admin.edit', compact('unit', 'projects', 'tipes'));
    }

    /**
     * Update data unit.
     */
    public function update(Request $request, Unit $unit)
    {
        $validated = $request->validate([
            'project_id' => 'required|exists:projects,id',
            'tipe_id'    => 'required|exists:tipes,id',
            'block'      => 'required|string|max:10',
            'no_unit'    => [
                'required', 'string', 'max:10',
                Rule::unique('units')->ignore($unit->id)->where(function ($query) use ($request) {
                    return $query->where('project_id', $request->project_id)
                                 ->where('block', $request->block);
                }),
            ],
            'status'     => 'required|in:Tersedia,Dibooking,Terjual'
        ]);

        $unit->update($validated);

        return redirect()->route('admin.unit.index')->with('success', 'Data unit diperbarui!');
    }

    /**
     * Menghapus unit.
     */
    public function destroy(Unit $unit)
    {
        $unit->delete();
        return back()->with('success', 'Unit berhasil dihapus!');
    }

    /**
     * API untuk AJAX: Mengambil tipe rumah berdasarkan proyek yang dipilih.
     */
    public function getTipeByProject($projectId)
    {
        $tipes = Tipe::where('project_id', $projectId)->select('id', 'nama_tipe')->get();
        return response()->json($tipes);
    }
}