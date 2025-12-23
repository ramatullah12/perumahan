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
     */
    protected $fillable = [
        'project_id',
        'tipe_id',
        'block',
        'no_unit',
        'status',
        'progres', 
        // TAMBAHKAN kolom ini agar harga yang diinput di form tersimpan ke DB
        'harga', 
    ];

    /**
     * Relasi ke Model Project.
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
        return $this->belongsTo(Tipe::class, 'tipe_id');
    }

    // =========================================================
    // FITUR PROGRES & BOOKING
    // =========================================================

    /**
     * Relasi progres terbaru (untuk efisiensi).
     */
    public function latestProgres(): HasOne
    {
        return $this->hasOne(Progres::class, 'unit_id')->latestOfMany();
    }

    /**
     * Relasi histori progres pembangunan.
     */
    public function progres(): HasMany
    {
        return $this->hasMany(Progres::class, 'unit_id')->latest();
    }

    /**
     * Relasi ke Booking.
     */
    public function booking(): HasOne
    {
        return $this->hasOne(Booking::class, 'unit_id');
    }

    /**
     * Accessor untuk nama unit yang rapi.
     */
    public function getNamaUnitAttribute(): string
    {
        return "Blok {$this->block} No. {$this->no_unit}";
    }
    
    /**
     * Accessor untuk format harga rupiah.
     * Contoh di Blade: {{ $unit->harga_formatted }}
     */
    public function getHargaFormattedAttribute(): string
    {
        return "Rp " . number_format($this->harga, 0, ',', '.');
    }
}