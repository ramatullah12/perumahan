<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Tipe extends Model
{
    use HasFactory;

    protected $fillable = [
        'project_id', 'nama_tipe', 'harga', 'luas_tanah', 
        'luas_bangunan', 'kamar_tidur', 'kamar_mandi', 'gambar'
    ];

    public function project()
    {
        return $this->belongsTo(Project::class);
    }
}