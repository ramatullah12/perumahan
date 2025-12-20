<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('notifications', function (Blueprint $table) {
            $table->id();
            
            // Relasi ke tabel users (untuk Customer)
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            
            // Judul Notifikasi (Contoh: "Update Progres Pembangunan - 70%")
            $table->string('title'); 
            
            // Isi Pesan Notifikasi secara detail
            $table->text('message'); 
            
            // Tipe Notifikasi untuk membedakan warna dan ikon
            // 'booking' (hijau), 'progres' (biru)
            $table->string('type')->default('progres'); 
            
            // Status apakah notifikasi sudah dibaca untuk memunculkan badge "Baru"
            $table->boolean('is_read')->default(false);
            
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('notifications');
    }
};