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

    /**
     * PERBAIKAN: Nama class harus Tipe (Capital T) 
     * Ini penyebab error 500 pada gambar image_98415e.png
     */
    public function tipe(): BelongsTo
    {
        return $this->belongsTo(Tipe::class, 'tipe_id');
    }

    // Menggunakan hasMany untuk daftar progres
    public function progres(): HasMany 
    {
        return $this->hasMany(Progres::class, 'unit_id')->latest();
    }

    /**
     * PERBAIKAN: Gunakan nama ini di Blade untuk ambil data terbaru.
     * Pastikan di Blade Customer memanggil $unit->latestProgres
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