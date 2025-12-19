<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Project extends Model
{
    use HasFactory;

    protected $fillable = [
        'nama_proyek',
        'lokasi',
        'deskripsi',
        'total_unit',
        'tersedia',
        'booked',
        'terjual',
        'gambar', // Ubah menjadi 'thumbnail' jika di database Anda kolomnya bernama thumbnail
        'status',
    ];

    /**
     * Relasi ke Unit (One to Many)
     * Memperbaiki error: Call to undefined method Project::units()
     */
    public function units()
    {
        // Menghubungkan Project ke Model Unit
        return $this->hasMany(Unit::class);
    }

    /**
     * Relasi ke Booking (One to Many)
     * Digunakan agar foto proyek bisa dipanggil di dashboard Customer
     */
    public function bookings()
    {
        return $this->hasMany(Booking::class);
    }
}