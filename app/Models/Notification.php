<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Notification extends Model
{
    use HasFactory;

    /**
     * Kolom yang dapat diisi (Mass Assignment).
     * Sesuai dengan struktur tabel migrasi yang kita buat.
     */
    protected $fillable = [
        'user_id', 
        'title', 
        'message', 
        'type', 
        'is_read'
    ];

    /**
     * Relasi ke User.
     * Setiap notifikasi dimiliki oleh satu User (Customer).
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }
}