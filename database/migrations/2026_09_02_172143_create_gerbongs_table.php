<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('master_gerbongs', function (Blueprint $table) {
            $table->id();
            $table->string('kode_gerbong', 20)->unique();
            $table->string('nama_gerbong', 100)->nullable();
            $table->decimal('kapasitas_ton', 8, 2)->default(20.00);
            $table->enum('status', ['Aktif', 'Non-Aktif', 'Perawatan'])->default('Aktif');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('master_gerbongs');
    }
};