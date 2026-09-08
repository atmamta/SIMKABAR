<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Jalankan migrasi tabel antrean rekap file Excel.
     */
    public function up(): void
    {
        Schema::create('export_jobs', function (Blueprint $table) {
            $table->id();
            $table->string('nama_file', 150);                      // Contoh: B1_Data_Muat_16_8_2026.xlsx
            $table->date('tanggal');                               // Tanggal Data
            $table->string('nomor_gerbong', 20)->nullable();       // Gerbong
            $table->enum('status', ['Wait', 'Done'])->default('Wait'); // Status Badge di UI
            $table->string('file_path')->nullable();               // Lokasi simpan file Excel
            $table->timestamps();
        });
    }

    /**
     * Batalkan migrasi.
     */
    public function down(): void
    {
        Schema::dropIfExists('export_jobs');
    }
};