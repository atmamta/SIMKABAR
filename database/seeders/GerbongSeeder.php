<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Gerbong;

class GerbongSeeder extends Seeder
{
    /**
     * Mengisi master data gerbong B1 sampai B11 untuk operasional penimbangan PT KAI.
     */
    public function run(): void
    {
        for ($i = 1; $i <= 11; $i++) {
            Gerbong::updateOrCreate(
                [
                    'kode_gerbong' => 'B' . $i,
                ],
                [
                    'nama_gerbong'  => 'Gerbong Retail B' . $i,
                    'kapasitas_ton' => 20.00,
                    'status'        => 'Aktif',
                ]
            );
        }
    }
}