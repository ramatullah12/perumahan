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

    protected $fillable = [
        'project_id',
        'tipe_id',
        'block',
        'no_unit', 
        'status', // 'Tersedia', 'Dibooking', 'Terjual'
        'progres', 
        'harga', 
    ];

    /**
     * Relasi ke Proyek
     */
    public function project(): BelongsTo
    {
        return $this->belongsTo(Project::class, 'project_id');
    }

    /**
     * Relasi ke Tipe (PENTING: Agar nama tipe rumah muncul di dropdown)
     */
    public function tipe(): BelongsTo
    {
        return $this->belongsTo(Tipe::class, 'tipe_id');
    }

    /**
     * Relasi progres_history (Solusi Error 500 di ProgresController)
     */
    public function progres_history(): HasMany 
    {
        return $this->hasMany(Progres::class, 'unit_id')->latest();
    }

    /**
     * Alias Relasi progres
     */
    public function progres(): HasMany 
    {
        return $this->hasMany(Progres::class, 'unit_id')->latest();
    }

    /**
     * Mengambil progres pembangunan terakhir saja
     */
    public function latestProgres(): HasOne
    {
        return $this->hasOne(Progres::class, 'unit_id')->latestOfMany();
    }

    /**
     * Relasi ke Booking
     */
    public function booking(): HasOne
    {
        return $this->hasOne(Booking::class, 'unit_id');
    }

    /**
     * Accessor untuk nama unit (Contoh: Blok A No. 01)
     */
    public function getNamaUnitAttribute(): string
    {
        return "Blok {$this->block} No. {$this->no_unit}";
    }

    /**
     * Accessor untuk Tipe dan Harga (Memudahkan tampilan di Dropdown AJAX)
     */
    public function getDetailUnitAttribute(): string
    {
        $namaTipe = $this->tipe->nama_tipe ?? 'Tanpa Tipe';
        $hargaFormatted = number_format($this->harga, 0, ',', '.');
        return "{$this->nama_unit} - {$namaTipe} (Rp {$hargaFormatted})";
    }
}