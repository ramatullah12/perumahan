<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
// Tambahkan import di bawah ini agar class Tipe dikenali
use App\Models\Tipe; 

class Unit extends Model
{
    use HasFactory;

    protected $fillable = [
        'project_id',
        'tipe_id',
        'block',
        'no_unit', 
        'status', // 'Tersedia', 'Dibooking', 'Terjual'
        'progres', 
        'harga',
        'gambar', // Pastikan kolom gambar ada di fillable jika ingin upload fotokvjdj
    ];

    /**
     * Relasi ke Proyek
     */
    public function project(): BelongsTo
    {
        return $this->belongsTo(Project::class, 'project_id');
    }

    /**
     * Relasi ke Tipe Rumah
     */
    public function tipe(): BelongsTo
    {
        // PERBAIKAN: Ubah 'tipe::class' menjadi 'Tipe::class' (Huruf T Besar)
        return $this->belongsTo(Tipe::class, 'tipe_id');
    }

    /**
     * Relasi ke Riwayat Progres Pembangunan
     */
    public function progres_history(): HasMany 
    {
        return $this->hasMany(Progres::class, 'unit_id')->latest();
    }

    /**
     * Mengambil data progres pembangunan terakhir saja
     */
    public function latestProgres(): HasOne
    {
        return $this->hasOne(Progres::class, 'unit_id')->latestOfMany();
    }

    /**
     * Relasi ke Data Booking
     */
    public function booking(): HasOne
    {
        return $this->hasOne(Booking::class, 'unit_id');
    }

    /**
     * ACCESSOR: Nama Unit Lengkap (Blok A No. 01)
     */
    public function getNamaUnitAttribute(): string
    {
        return "Blok " . strtoupper($this->block) . " No. " . $this->no_unit;
    }

    /**
     * ACCESSOR: Detail Lengkap untuk tampilan (Blok A No. 01 - Tipe 36 - Rp 500.000.000)
     */
    public function getDetailUnitAttribute(): string
    {
        // Tambahkan pengecekan null safety agar tidak error jika relasi tipe kosong
        $namaTipe = $this->tipe->nama_tipe ?? 'Tanpa Tipe';
        $hargaFormatted = number_format($this->harga, 0, ',', '.');
        return "{$this->nama_unit} - {$namaTipe} (Rp {$hargaFormatted})";
    }
}