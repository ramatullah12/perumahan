<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Booking extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'nama',
        'alamat',
        'telepon',
        'tipe_rumah',
        'harga',
        'catatan',
        'status', // pending / disetujui / ditolak
    ];

    // Relasi: 1 booking dimiliki 1 user
    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
