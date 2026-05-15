<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class KonfirmasiPesananController extends Controller
{
    public function index()
    {
        return view('konfirmasi'); 
    }
}