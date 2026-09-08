<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class RekapPenimbangan extends Model
{
    use HasFactory;

    protected $table = 'rekap_penimbangans';

    protected $fillable = [
        'nama_file',
        'kode_gerbong',
        'tanggal_rekap',
        'status',
        'file_path',
    ];

    protected $casts = [
        'tanggal_rekap' => 'datetime',
    ];
}
