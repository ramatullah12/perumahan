<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;

class Unit extends Model
{
    use HasFactory;

    /**
     * Kolom yang dapat diisi secara massal (mass assignable).
     * Memastikan 'no_unit' konsisten dengan database.
     */
    protected $fillable = [
        'project_id',
        'tipe_id',
        'block',
        'no_unit', 
        'status',
        'progres', 
        'harga', 
    ];

    /**
     * Relasi ke Model Project (Belongs To).
     */
    public function project(): BelongsTo
    {
        return $this->belongsTo(Project::class, 'project_id');
    }

    /**
     * Relasi ke Model Tipe.
     */
    public function tipe(): BelongsTo
    {
        return $this->belongsTo(tipe::class, 'tipe_id');
    }

    // =========================================================
    // FITUR PROGRES & BOOKING
    // =========================================================

    /**
     * PERBAIKAN KRUSIAL: Menambahkan relasi 'progres'.
     * Ini untuk menyamakan pemanggilan 'unit.progres' di ProgresController.
     */
    public function progres(): HasMany 
    {
        return $this->hasMany(Progres::class, 'unit_id')->latest();
    }

    /**
     * Relasi progres terbaru untuk ringkasan admin/owner.
     */
    public function latestProgres(): HasOne
    {
        return $this->hasOne(Progres::class, 'unit_id')->latestOfMany();
    }

    /**
     * Relasi histori progres pembangunan lengkap.
     */
    public function progres_history(): HasMany 
    {
        return $this->hasMany(Progres::class, 'unit_id')->latest();
    }

    /**
     * Relasi ke data Booking unit.
     */
    public function booking(): HasOne
    {
        return $this->hasOne(Booking::class, 'unit_id');
    }

    // =========================================================
    // ACCESSORS (Untuk Tampilan di Blade)
    // =========================================================

    /**
     * Menggabungkan Blok dan Nomor Unit.
     */
    public function getNamaUnitAttribute(): string
    {
        return "Blok {$this->block} No. {$this->no_unit}";
    }
    
    /**
     * Format harga Rupiah profesional.
     */
    public function getHargaFormattedAttribute(): string
    {
        return "Rp " . number_format($this->harga, 0, ',', '.');
    }
}