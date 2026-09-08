<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TransaksiPenimbangan extends Model
{
    use HasFactory;

    protected $table = 'transaksi_penimbangans';

    protected $fillable = [
        'loket',
        'tanggal_timbang',
        'berat_kotor',
        'handpallet',
        'kayu_list',   // Menyimpan data array JSON potongan kayu
        'total_kayu',
        'berat_bersih',
        'nama_mitra',
        'tujuan',
        'kode_gerbong',
    ];

    /**
     * Casting tipe data otomatis agar presisi secara matematis
     * dan array kayu otomatis dikonversi dari/ke JSON.
     */
    protected $casts = [
        'tanggal_timbang' => 'date',
        'berat_kotor'     => 'float',
        'handpallet'      => 'float',
        'total_kayu'      => 'float',
        'berat_bersih'    => 'float',
        'kayu_list'       => 'array',
    ];
}
