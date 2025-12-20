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
        'total_unit', // Tetap ada jika Anda ingin menentukan kapasitas maksimal manual
        'gambar', 
        'status',
    ];

    /**
     * Relasi ke Unit (One to Many)
     * Digunakan untuk menghitung statistik unit secara real-time.
     */
    public function units()
    {
        return $this->hasMany(Unit::class, 'project_id');
    }

    /**
     * Relasi ke Booking (One to Many)
     */
    public function bookings()
    {
        return $this->hasMany(Booking::class, 'project_id');
    }

    /**
     * SINKRONISASI OTOMATIS:
     * Menambahkan atribut bantuan (accessors) agar status unit selalu terupdate
     * saat dipanggil di dashboard Admin maupun Customer.
     */
    
    // Menghitung Unit Tersedia secara otomatis
    public function getTersediaCountAttribute()
    {
        return $this->units()->where('status', 'Tersedia')->count();
    }

    // Menghitung Unit yang sedang Dibooking
    public function getBookedCountAttribute()
    {
        return $this->units()->where('status', 'Dibooking')->count();
    }

    // Menghitung Unit yang sudah Terjual
    public function getTerjualCountAttribute()
    {
        return $this->units()->where('status', 'Terjual')->count();
    }

    // Menghitung Total Unit yang terdaftar di proyek ini
    public function getTotalCountAttribute()
    {
        return $this->units()->count();
    }
}