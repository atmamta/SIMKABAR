<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Gerbong extends Model
{
    use HasFactory;

    protected $table = 'master_gerbongs';

    protected $fillable = [
        'kode_gerbong',
        'nama_gerbong',
        'kapasitas_ton',
        'status',
    ];

    protected $casts = [
        'kapasitas_ton' => 'float',
    ];
}