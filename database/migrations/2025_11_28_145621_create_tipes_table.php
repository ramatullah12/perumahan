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
        Schema::create('tipes', function (Blueprint $table) {
            $table->id();
            // Menghubungkan tipe rumah ke proyek tertentu (Foreign Key)
            // onDelete('cascade') berarti jika proyek dihapus, tipe di dalamnya ikut terhapus
            $table->foreignId('project_id')->constrained('projects')->onDelete('cascade');
            
            $table->string('nama_tipe'); // Contoh: Tipe 36/72
            $table->bigInteger('harga'); // Menggunakan bigInteger untuk nominal jutaan/miliaran
            $table->integer('luas_tanah');
            $table->integer('luas_bangunan');
            $table->integer('kamar_tidur');
            $table->integer('kamar_mandi');
            $table->string('gambar'); // Menyimpan path/nama file foto
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('tipes');
    }
};