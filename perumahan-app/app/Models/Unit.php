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
     * PERBAIKAN: Mengembalikan 'nomor_unit' menjadi 'no_unit'.
     */
    protected $fillable = [
        'project_id',
        'tipe_id',
        'block',
        'no_unit', // Diubah kembali ke no_unit sesuai instruksi Anda
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
     * Menggunakan 'tipe::class' karena nama file model Anda adalah tipe.php.
     */
    public function tipe(): BelongsTo
    {
        return $this->belongsTo(tipe::class, 'tipe_id');
    }

    // =========================================================
    // FITUR PROGRES & BOOKING
    // =========================================================

    /**
     * Relasi progres terbaru.
     */
    public function latestProgres(): HasOne
    {
        return $this->hasOne(Progres::class, 'unit_id')->latestOfMany();
    }

    /**
     * Relasi histori progres pembangunan.
     */
    public function progres_history(): HasMany 
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
     * PERBAIKAN: Menggunakan $this->no_unit.
     */
    public function getNamaUnitAttribute(): string
    {
        return "Blok {$this->block} No. {$this->no_unit}";
    }
    
    /**
     * Accessor untuk format harga rupiah yang profesional.
     * Contoh di Blade: {{ $unit->harga_formatted }}.
     */
    public function getHargaFormattedAttribute(): string
    {
        return "Rp " . number_format($this->harga, 0, ',', '.');
    }
}