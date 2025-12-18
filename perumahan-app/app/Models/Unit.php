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
    ];

    /**
     * Relasi ke Model Project.
     * Satu unit dimiliki oleh satu proyek.
     */
    public function project(): BelongsTo
    {
        return $this->belongsTo(Project::class, 'project_id');
    }

    /**
     * Relasi ke Model Tipe.
     * Satu unit memiliki satu tipe spesifik.
     */
    public function tipe(): BelongsTo
    {
        return $this->belongsTo(Tipe::class, 'tipe_id');
    }

    // =========================================================
    // FITUR PROGRES & BOOKING
    // =========================================================

    /**
     * Relasi untuk mengambil progres terbaru dari unit ini.
     * Menggunakan latestOfMany() untuk efisiensi pengambilan data terakhir.
     */
    public function latestProgres(): HasOne
    {
        return $this->hasOne(Progres::class, 'unit_id')->latestOfMany();
    }

    /**
     * Relasi ke semua histori progres pembangunan.
     */
    public function progres(): HasMany
    {
        return $this->hasMany(Progres::class, 'unit_id');
    }

    /**
     * Relasi ke Booking untuk mendapatkan data pembeli.
     * Digunakan untuk menampilkan nama customer pada dashboard progres.
     */
    public function booking(): HasOne
    {
        return $this->hasOne(Booking::class, 'unit_id');
    }
}