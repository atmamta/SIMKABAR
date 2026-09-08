<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MasterMitra extends Model
{
    use HasFactory;

    protected $table = 'master_mitras';

    protected $fillable = [
        'kode_mitra',
        'nama_mitra',
        'is_active',
    ];

    protected $casts = [
        'is_active' => 'boolean',
    ];
}