<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Tipe extends Model
{
    use HasFactory;

    protected $fillable = [
        'project_id', 
        'nama_tipe', 
        'harga', 
        'luas_tanah', 
        'luas_bangunan', 
        'kamar_tidur', 
        'kamar_mandi', 
        'gambar'
    ];

    /**
     * Relasi ke Proyek.
     * Menghubungkan tipe rumah dengan proyek induknya.
     */
    public function project(): BelongsTo
    {
        return $this->belongsTo(Project::class, 'project_id');
    }

    /**
     * Relasi ke Unit.
     * Satu tipe rumah (misal: Tipe 36) bisa memiliki banyak unit fisik.
     * Ini penting agar saat Anda menghapus/mengedit tipe, sistem tahu unit mana yang terdampak.
     */
    public function units(): HasMany
    {
        return $this->hasMany(Unit::class, 'tipe_id');
    }

    /**
     * Accessor untuk format harga Rupiah.
     * Memudahkan Anda menampilkan harga di Blade tanpa perlu menulis number_format berulang kali.
     */
    public function getHargaFormattedAttribute(): string
    {
        return 'Rp ' . number_format($this->harga, 0, ',', '.');
    }
}