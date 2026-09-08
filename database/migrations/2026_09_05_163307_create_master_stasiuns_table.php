<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('master_stasiuns', function (Blueprint $table) {
            $table->id();
            $table->string('kode_stasiun', 20)->unique(); // Contoh: BBT, BJ, CU, dst.
            $table->string('nama_stasiun')->nullable();   // Nama panjang stasiun (opsional, bisa diisi belakangan)
            $table->boolean('is_active')->default(true);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('master_stasiuns');
    }
};
