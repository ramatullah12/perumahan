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
        Schema::create('units', function (Blueprint $table) {
            $table->id();
            // Relasi ke tabel projects
            $table->foreignId('project_id')->constrained('projects')->onDelete('cascade');
            
            // Relasi ke tabel tipes
            $table->foreignId('tipe_id')->constrained('tipes')->onDelete('cascade');
            
            // Spesifikasi posisi unit
            $table->string('block'); // Contoh: A, B, atau C
            $table->string('no_unit'); // Contoh: 01, 02, dsb
            
            // Status ketersediaan menggunakan enum agar validasi lebih ketat
            $table->enum('status', ['Tersedia', 'Dibooking', 'Terjual'])->default('Tersedia');
            
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('units');
    }
};