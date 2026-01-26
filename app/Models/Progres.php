<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Progres extends Model
{
    use HasFactory;

    /**
     * Nama tabel di database.
     */
    protected $table = 'progres';

    /**
     * Kolom yang dapat diisi secara massal.
     */
    protected $fillable = [
        'unit_id',
        'persentase',
        'tahap',
        'foto',
        'keterangan',
    ];

    /**
     * TAMBAHAN: Casting data.
     * Memastikan 'persentase' selalu dibaca sebagai angka (integer).
     */
    protected $casts = [
        'persentase' => 'integer',
    ];

    /**
     * Relasi ke Model Unit.
     * Satu data progres dimiliki oleh satu unit rumah.
     */
    public function unit()
    {
        return $this->belongsTo(Unit::class, 'unit_id');
    }
}