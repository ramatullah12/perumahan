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
        'status',
        'progres', 
        'harga', 
    ];

    public function project(): BelongsTo
    {
        return $this->belongsTo(Project::class, 'project_id');
    }

    public function tipe(): BelongsTo
    {
        return $this->belongsTo(Tipe::class, 'tipe_id');
    }

    /**
     * PERBAIKAN: Tambahkan alias progres_history
     * Agar query di ProgresController $bookings = Booking::with(['unit.progres_history']) tidak error.
     */
    public function progres_history(): HasMany 
    {
        return $this->hasMany(Progres::class, 'unit_id')->latest();
    }

    /**
     * Relasi progres (tetap dipertahankan jika ada bagian lain yang pakai)
     */
    public function progres(): HasMany 
    {
        return $this->hasMany(Progres::class, 'unit_id')->latest();
    }

    /**
     * Digunakan di Controller Admin/Owner untuk mengambil data terbaru saja
     */
    public function latestProgres(): HasOne
    {
        return $this->hasOne(Progres::class, 'unit_id')->latestOfMany();
    }

    public function booking(): HasOne
    {
        return $this->hasOne(Booking::class, 'unit_id');
    }

    public function getNamaUnitAttribute(): string
    {
        return "Blok {$this->block} No. {$this->no_unit}";
    }
}