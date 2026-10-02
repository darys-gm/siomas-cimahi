<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class AlurPelaporanController extends Controller
{
    public function index()
    {
        return view('alur-pelaporan');
    }
}