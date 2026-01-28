<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Booking extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'project_id',
        'unit_id',
        'tanggal_booking',
        'dokumen',
        'keterangan',
        'status', // pastikan di database defaultnya 'pending'
    ];

    // Tambahkan casting tanggal agar Carbon bisa membaca formatnya di Blade
    protected $casts = [
        'tanggal_booking' => 'date',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function unit(): BelongsTo
    {
        return $this->belongsTo(Unit::class, 'unit_id');
    }

    public function project(): BelongsTo
    {
        return $this->belongsTo(Project::class, 'project_id');
    }
}