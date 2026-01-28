<?php

namespace App\Http\Controllers;

use App\Models\Unit;
use App\Models\Project;
use App\Models\Tipe; 
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;

class UnitController extends Controller
{
    /**
     * SINKRONISASI STOK: Memperbarui statistik di tabel projects secara otomatis.
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
     * AJAX: Mengambil tipe rumah berdasarkan project_id.
     * Fungsi ini yang akan menghilangkan pesan "Gagal memuat tipe".
     */
    public function getTipeByProject($projectId)
    {
        try {
            // Mengambil tipe yang hanya dimiliki oleh proyek tersebut
            $tipes = Tipe::where('project_id', $projectId)
                         ->orderBy('nama_tipe')
                         ->get(['id', 'nama_tipe', 'harga']); 

            return response()->json($tipes);
        } catch (\Exception $e) {
            Log::error("AJAX Error Get Tipe: " . $e->getMessage());
            return response()->json(['error' => 'Gagal mengambil data'], 500);
        }
    }

    /**
     * CUSTOMER: Menampilkan daftar proyek bagi pembeli (Solusi Error 500).
     */
    public function jelajahiProyek()
    {
        // Mengambil semua proyek beserta unit yang tersedia
        $projects = Project::withCount(['units' => function($query) {
            $query->where('status', 'Tersedia');
        }])->orderBy('nama_proyek')->get();

        return view('project.customer.index', compact('projects'));
    }

    /**
     * ADMIN: Daftar Unit dengan filter.
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
     * ADMIN: Halaman tambah unit.
     */
    public function create(Request $request)
    {
        $projects = Project::orderBy('nama_proyek')->get();
        $selectedProjectId = $request->query('project_id') ?? old('project_id');
        
        $tipes = $selectedProjectId 
            ? Tipe::where('project_id', $selectedProjectId)->orderBy('nama_tipe')->get() 
            : collect();

        $unit = new Unit(); 
        return view('unit.admin.create', compact('projects', 'unit', 'tipes', 'selectedProjectId'));
    }

    /**
     * ADMIN: Proses simpan unit.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'project_id' => 'required|exists:projects,id',
            'tipe_id'    => 'required|exists:tipes,id',
            'block'      => 'required|string|max:10',
            'no_unit'    => 'required|string|max:10',
            'harga'      => 'required|numeric|min:0', 
            'status'     => 'required|in:Tersedia,Dibooking,Terjual',
            'gambar'     => 'nullable|image|mimes:jpg,jpeg,png|max:2048'
        ]);

        $exists = Unit::where('project_id', $request->project_id)
                      ->where('block', $request->block)
                      ->where('no_unit', $request->no_unit)
                      ->exists();

        if ($exists) {
            return redirect()->back()->withInput()->with('error', 'Nomor Unit pada Blok ini sudah ada di proyek tersebut.');
        }

        try {
            DB::transaction(function () use ($validated, $request) {
                if ($request->hasFile('gambar')) {
                    $validated['gambar'] = $request->file('gambar')->store('units', 'public');
                }

                $validated['progres'] = 0; 
                Unit::create($validated);
                $this->syncProjectStock($request->project_id);
            });

            return redirect()->route('admin.unit.index')->with('success', 'Unit berhasil ditambahkan!');
        } catch (\Exception $e) {
            Log::error("Error Store Unit: " . $e->getMessage());
            return redirect()->back()->withInput()->with('error', 'Gagal menambah unit: ' . $e->getMessage());
        }
    }

    /**
     * ADMIN: Halaman edit unit.
     */
    public function edit(Unit $unit)
    {
        $projects = Project::orderBy('nama_proyek')->get();
        $tipes = Tipe::where('project_id', $unit->project_id)->orderBy('nama_tipe')->get();
        return view('unit.admin.edit', compact('unit', 'projects', 'tipes'));
    }

    /**
     * ADMIN: Proses update unit.
     */
    public function update(Request $request, Unit $unit)
    {
        $oldProjectId = $unit->project_id;

        $validated = $request->validate([
            'project_id' => 'required|exists:projects,id',
            'tipe_id'    => 'required|exists:tipes,id',
            'block'      => 'required|string|max:10',
            'no_unit'    => 'required|string|max:10',
            'harga'      => 'required|numeric|min:0', 
            'progres'    => 'required|integer|min:0|max:100',
            'status'     => 'required|in:Tersedia,Dibooking,Terjual',
            'gambar'     => 'nullable|image|mimes:jpg,jpeg,png|max:2048'
        ]);

        try {
            DB::transaction(function () use ($validated, $request, $unit, $oldProjectId) {
                if ($request->hasFile('gambar')) {
                    if ($unit->gambar) {
                        Storage::disk('public')->delete($unit->gambar);
                    }
                    $validated['gambar'] = $request->file('gambar')->store('units', 'public');
                }

                $unit->update($validated);
                $this->syncProjectStock($request->project_id);
                if ($oldProjectId != $request->project_id) {
                    $this->syncProjectStock($oldProjectId);
                }
            });

            return redirect()->route('admin.unit.index')->with('success', 'Unit berhasil diperbarui!');
        } catch (\Exception $e) {
            Log::error("Error Update Unit: " . $e->getMessage());
            return redirect()->back()->withInput()->with('error', 'Gagal memperbarui unit.');
        }
    }

    /**
     * ADMIN: Hapus unit.
     */
    public function destroy(Unit $unit)
    {
        $projectId = $unit->project_id;
        try {
            DB::transaction(function () use ($unit, $projectId) {
                if ($unit->gambar) {
                    Storage::disk('public')->delete($unit->gambar);
                }
                $unit->delete();
                $this->syncProjectStock($projectId);
            });
            return back()->with('success', 'Unit berhasil dihapus!');
        } catch (\Exception $e) {
            return back()->with('error', 'Gagal menghapus unit.');
        }
    }

    /**
     * ADMIN: Update status cepat (AJAX/Patch).
     */
    public function updateStatus(Request $request, Unit $unit)
    {
        $validated = $request->validate([
            'status' => 'required|in:Tersedia,Dibooking,Terjual'
        ]);

        $unit->update($validated);
        $this->syncProjectStock($unit->project_id);

        return response()->json(['success' => true]);
    }
}