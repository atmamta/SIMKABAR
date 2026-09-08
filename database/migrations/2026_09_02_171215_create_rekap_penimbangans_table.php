<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('rekap_penimbangans', function (Blueprint $table) {
            $table->id();
            $table->string('nama_file');
            $table->string('kode_gerbong');
            $table->date('tanggal_rekap');
            $table->enum('status', ['Wait', 'Done'])->default('Done');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('rekap_penimbangans');
    }
};