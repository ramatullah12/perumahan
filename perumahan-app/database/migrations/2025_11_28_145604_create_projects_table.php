<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('projects', function (Blueprint $table) {
            $table->id();
            $table->string('nama_proyek'); 
            $table->string('lokasi');
            $table->text('deskripsi');
            $table->integer('total_unit');
            
            // PERBAIKAN: Tambahkan default(0) agar tidak error saat disimpan
            // atau pastikan Controller selalu mengisi nilai ini
            $table->integer('tersedia')->default(0);
            $table->integer('booked')->default(0);
            $table->integer('terjual')->default(0);
            
            $table->string('gambar'); 
            $table->string('status')->default('Sedang Berjalan');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('projects');
    }
};