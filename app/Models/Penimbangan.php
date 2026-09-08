<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Penimbangan extends Model
{
    use HasFactory;

    protected $table = 'penimbangan';

    protected $fillable = [
        'loket',
        'nomor_gerbong',
        'nama_mitra',
        'stasiun_tujuan',
        'berat_kotor',
        'berat_handpallet',
        'list_kayu',
        'total_berat_kayu',
        'berat_bersih',
        'tanggal_timbang',
    ];

    protected $casts = [
        'list_kayu'        => 'array',
        'berat_kotor'      => 'float',
        'berat_handpallet' => 'float',
        'total_berat_kayu' => 'float',
        'berat_bersih'     => 'float',
        'tanggal_timbang'  => 'datetime',
    ];
}