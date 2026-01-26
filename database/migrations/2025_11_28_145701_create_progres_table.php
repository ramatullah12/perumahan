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
        Schema::create('progres', function (Blueprint $table) {
            $table->id();
            
            // Relasi ke tabel units (Satu unit memiliki banyak histori progres)
            $table->foreignId('unit_id')->constrained('units')->onDelete('cascade');
            
            // Angka progres (0-100)
            $table->integer('persentase')->default(0); 
            
            // Nama tahapan (Contoh: Fondasi, Pasang Dinding, Atap, dll)
            $table->string('tahap'); 
            
            // Path untuk menyimpan foto bukti pembangunan di lapangan
            $table->string('foto')->nullable(); 
            
            // Catatan tambahan jika diperlukan
            $table->text('keterangan')->nullable(); 
            
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('progres');
    }
};