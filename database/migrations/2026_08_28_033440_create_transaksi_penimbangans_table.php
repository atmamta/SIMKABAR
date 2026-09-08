<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('transaksi_penimbangans', function (Blueprint $table) {
            $table->id();
            
            // Kolom Loket & Waktu Penimbangan (NAMA SAMA PERSIS)
            $table->unsignedTinyInteger('loket');
            $table->date('tanggal_timbang'); // Tanggal operasional
            
            // Komponen Berat Penimbangan (Kg) (NAMA SAMA PERSIS)
            $table->decimal('berat_kotor', 10, 2);
            $table->decimal('handpallet', 10, 2)->default(0);
            $table->json('kayu_list')->nullable();
            $table->decimal('total_kayu', 10, 2)->default(0);
            $table->decimal('berat_bersih', 10, 2);
            
            // Detail Angkutan & Logistik (NAMA SAMA PERSIS)
            $table->string('nama_mitra', 150);
            $table->string('tujuan', 50);
            $table->string('kode_gerbong', 20);
            
            $table->timestamps();

            /* -----------------------------------------------------------------
             * TAMBAHAN OPTIMASI (Tanpa mengubah nama kolom di atas)
             * ----------------------------------------------------------------- */
            $table->index(['loket', 'tanggal_timbang', 'kode_gerbong'], 'idx_loket_tanggal_gerbong');
            $table->index(['loket', 'tanggal_timbang', 'nama_mitra'], 'idx_loket_tanggal_mitra');
            $table->index('nama_mitra', 'idx_nama_mitra');
            $table->index('kode_gerbong', 'idx_kode_gerbong');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('transaksi_penimbangans');
    }
};