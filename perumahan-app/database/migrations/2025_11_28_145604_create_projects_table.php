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
            $table->integer('tersedia');
            $table->integer('booked')->default(0);
            $table->integer('terjual')->default(0);
            $table->string('gambar'); // Menyimpan path foto
            $table->string('status')->default('Sedang Berjalan');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('projects');
    }
};