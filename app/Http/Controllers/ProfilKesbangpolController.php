<?php

namespace App\Http\Controllers;

use App\Models\Galeri;
use Illuminate\Http\Request;

class ProfilKesbangpolController extends Controller
{
    public function index()
    {
        // Ambil semua data galeri yang aktif
        $galeris = Galeri::where('is_active', true)
            ->orderBy('created_at', 'desc')
            ->get();

        return view('profil-kesbangpol', compact('galeris'));
    }
}