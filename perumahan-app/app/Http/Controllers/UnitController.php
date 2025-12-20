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
     * CUSTOMER - JELAJAHI PROYEK
     * Menampilkan unit yang tersedia untuk dipesan oleh Customer.
     */
    public function jelajahiProyek()
    {
        // Mengambil unit berstatus 'Tersedia' beserta relasi proyek dan tipe
        $units = Unit::with(['project', 'tipe'])
            ->where('status', 'Tersedia') // Filter utama agar sinkron dengan stok asli
            ->latest()
            ->get();

        // Diarahkan ke folder 'project.customer' sesuai struktur folder Anda
        return view('project.customer.index', compact('units'));
    }

    /**
     * ADMIN - DAFTAR UNIT
     */
    public function index(Request $request)
    {
        $projects = Project::all();
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
     * ADMIN - FORM TAMBAH UNIT
     */
    public function create()
    {
        $projects = Project::all();
        return view('unit.admin.create', compact('projects'));
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

        // Langsung redirect ke index untuk menghindari tampilan JSON
        return redirect()->route('admin.unit.index')->with('success', 'Unit berhasil ditambahkan!');
    }

    /**
     * ADMIN - EDIT DATA UNIT
     */
    public function edit(Unit $unit)
    {
        $projects = Project::all();
        // Memastikan tipe sinkron dengan project yang dipilih
        $tipes = Tipe::where('project_id', $unit->project_id)->get();
        
        return view('unit.admin.edit', compact('unit', 'projects', 'tipes'));
    }

    /**
     * ADMIN - UPDATE DATA UNIT
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
     * ADMIN - HAPUS UNIT
     */
    public function destroy(Unit $unit)
    {
        $unit->delete();
        return back()->with('success', 'Unit berhasil dihapus!');
    }

    /**
     * API - AJAX TIPE RUMAH
     */
    public function getTipeByProject($projectId)
    {
        $tipes = Tipe::where('project_id', $projectId)->select('id', 'nama_tipe')->get();
        return response()->json($tipes);
    }
}