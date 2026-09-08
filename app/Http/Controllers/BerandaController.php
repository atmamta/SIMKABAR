<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class BerandaController extends Controller
{
    /**
     * Menampilkan halaman utama SIMKABAR.
     */
    public function index()
    {
        return view('beranda.index');
    }
}