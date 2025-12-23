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
            
            // Relasi ke tabel projects (Foreign Key)
            $table->foreignId('project_id')->constrained('projects')->onDelete('cascade');
            
            // Relasi ke tabel tipes (Foreign Key)
            $table->foreignId('tipe_id')->constrained('tipes')->onDelete('cascade');
            
            // Spesifikasi posisi unit
            $table->string('block'); 
            $table->string('no_unit'); 

            /**
             * PERBAIKAN: Hapus ->after('no_unit')
             * Database akan otomatis meletakkan kolom 'harga' setelah 'no_unit' 
             * karena urutan penulisannya di sini.
             */
            $table->decimal('harga', 15, 2)->default(0);
            
            // Status unit
            $table->enum('status', ['Tersedia', 'Dibooking', 'Terjual'])->default('Tersedia');

            /**
             * PERBAIKAN: Hapus ->after('status')
             * Kolom 'progres' akan otomatis berada setelah 'status'.
             * Kolom ini wajib ada agar dashboard Owner tidak error.
             */
            $table->integer('progres')->default(0);
            
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