<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('master_mitras', function (Blueprint $table) {
            $table->id();
            $table->string('kode_mitra')->unique(); // Format: SBI-001, SBI-002, dst.
            $table->string('nama_mitra');           // Nama Resmi Mitra Expeditur
            $table->boolean('is_active')->default(true);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('master_mitras');
    }
};