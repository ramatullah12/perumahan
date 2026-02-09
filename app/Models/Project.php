<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use App\Models\Tipe; // <--- TAMBAHKAN INI agar class Tipe terbaca

class Project extends Model
{
    use HasFactory;

    protected $fillable = [
        'nama_proyek',
        'lokasi',
        'deskripsi',
        'total_unit', 
        'gambar', 
        'status',
        'tersedia',
        'booked',
        'terjual',
    ];

    /**
     * Relasi ke Tipe (One to Many)
     */
    public function tipes(): HasMany
    {
        // Gunakan Tipe::class dengan T kapital
        return $this->hasMany(Tipe::class, 'project_id');
    }

    /**
     * Relasi ke Unit (One to Many)
     */
    public function units(): HasMany
    {
        return $this->hasMany(Unit::class, 'project_id');
    }

    /**
     * Relasi ke Booking (One to Many)
     */
    public function bookings(): HasMany
    {
        return $this->hasMany(Booking::class, 'project_id');
    }

    /**
     * ACCESSORS (STATISTIK REAL-TIME):
     * Digunakan untuk dashboard ketersediaan unit di halaman detail
     */
    
    // Menghitung Unit Tersedia
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

    // Menghitung Total Unit yang terdaftar secara riil
    public function getTotalCountAttribute()
    {
        return $this->units()->count();
    }
}