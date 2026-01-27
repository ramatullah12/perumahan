<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory; // Tambahkan baris ini!
use Illuminate\Database\Eloquent\Model;

class Tipe extends Model
{
    use HasFactory; // Sekarang baris ini akan berfungsi

    protected $fillable = [
        'project_id', 'nama_tipe', 'harga', 'luas_tanah', 
        'luas_bangunan', 'kamar_tidur', 'kamar_mandi', 'gambar'
    ];

    public function project()
    {
        return $this->belongsTo(Project::class);
    }   
}
