<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class MasterDataSeeder extends Seeder
{
    /**
     * Seed daftar kode mitra expeditur operasional penimbangan.
     */
    public function run(): void
    {
        $mitraList = [
            'LNP', 'KIB', 'SBE', 'JS', 'KALOG', 'MA', 'UJM', 'SMN', 'BJE', 'NBC',
            'INDEX', 'BMV', 'SBL', 'BBX', 'KI8', 'MJR', 'MJ1', 'MJ2', 'MJ3', 'MJ4',
            'MPE', 'IFI', 'LZC', 'DJE', 'SAJ', 'TITIAN', 'TE', 'ST', 'HRP', 'RKS',
            'MNL', 'HE', 'ANGKUNAS', 'EKA', 'UDN', 'BENI', 'LIMAS', 'TPL', 'TOTO',
            'CMP', 'CTX', '99', 'RB', 'GC', 'AMQ', 'TJE', 'DP', 'PE', 'VL', 'JLL',
            'MJG', 'NIKI', 'HLOG', 'TOTO1', 'TOTO2', 'TOTO3', 'REL',
        ];

        // Mass-insert dengan menghilangkan data duplikat jika ada
        $uniqueMitra = array_values(array_unique($mitraList));

        foreach ($uniqueMitra as $index => $namaMitra) {
            DB::table('master_mitras')->updateOrInsert(
                ['kode_mitra' => 'SBI-' . sprintf('%03d', $index + 1)],
                [
                    'nama_mitra' => $namaMitra,
                    'is_active'  => true,
                    'created_at' => now(),
                    'updated_at' => now(),
                ]
            );
        }
    }
}
