<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class StasiunSeeder extends Seeder
{
    /**
     * Seed daftar kode stasiun tujuan operasional penimbangan.
     */
    public function run(): void
    {
        $stasiunList = [
            'JAKG', 'CKP', 'PGB', 'JTB', 'CNP', 'TG',
            'PK', 'SMT', 'NBO', 'CU', 'BJ', 'BBT',
        ];

        foreach ($stasiunList as $kode) {
            DB::table('master_stasiuns')->updateOrInsert(
                ['kode_stasiun' => $kode],
                [
                    'nama_stasiun' => null,
                    'is_active'    => true,
                    'created_at'   => now(),
                    'updated_at'   => now(),
                ]
            );
        }
    }
}
