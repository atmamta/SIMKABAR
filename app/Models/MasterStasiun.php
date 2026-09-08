<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MasterStasiun extends Model
{
    use HasFactory;

    protected $table = 'master_stasiuns';

    protected $fillable = [
        'kode_stasiun',
        'nama_stasiun',
        'is_active',
    ];

    protected $casts = [
        'is_active' => 'boolean',
    ];
}
