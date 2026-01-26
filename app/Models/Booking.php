<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Booking extends Model
{
    use HasFactory;

    /**
     * Kolom yang dapat diisi secara massal.
     * Menambahkan 'project_id', 'unit_id', 'tanggal_booking', dan 'dokumen'.
     */
    protected $fillable = [
        'user_id',
        'nama',
        'project_id',   // Menghubungkan ke Proyek
        'unit_id',      // Menghubungkan ke Unit (Pusat Data)
        'tanggal_booking',
        'dokumen',      // Untuk menyimpan foto KTP/NPWP
        'keterangan',   // Pengganti catatan
        'status',       // pending / disetujui / ditolak
    ];

    /**
     * Relasi ke User: Satu booking dimiliki oleh satu user.
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    /**
     * Relasi ke Unit: Menghubungkan booking dengan unit rumah spesifik.
     * Ini kunci agar status unit bisa berubah otomatis.
     */
    public function unit(): BelongsTo
    {
        return $this->belongsTo(Unit::class, 'unit_id');
    }

    /**
     * Relasi ke Project: Memudahkan filter laporan berdasarkan proyek.
     */
    public function project(): BelongsTo
    {
        return $this->belongsTo(Project::class, 'project_id');
    }
}