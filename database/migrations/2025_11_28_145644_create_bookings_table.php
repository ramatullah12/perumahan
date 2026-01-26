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
        Schema::create('bookings', function (Blueprint $table) {
            $table->id();
            
            // RELASI UTAMA (Menghubungkan semua Role)
            $table->foreignId('user_id')->constrained()->onDelete('cascade'); // Customer
            $table->foreignId('project_id')->constrained()->onDelete('cascade'); // Laporan Owner
            $table->foreignId('unit_id')->constrained()->onDelete('cascade'); // Update Progres Admin

            // DATA IDENTITAS & TRANSAKSI
            $table->string('nama'); 
            $table->string('telepon')->nullable();
            $table->date('tanggal_booking');
            
            // KOLOM DOKUMEN (Untuk upload KTP/NPWP)
            $table->string('dokumen')->nullable(); 
            
            // CATATAN & STATUS
            $table->text('keterangan')->nullable();
            $table->enum('status', ['pending', 'disetujui', 'ditolak'])->default('pending');

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('bookings');
    }
};