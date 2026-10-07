<?php

namespace App\Http\Controllers;

use App\Models\StrukturOrganisasi;
use App\Models\Galeri;
use Illuminate\Http\Request;

class ProfilKesbangpolController extends Controller
{
    public function index()
    {
        // Ambil galeri kategori bakesbangpol saja
        $galeris = Galeri::where('is_active', true)
            ->where('kategori', 'bakesbangpol')
            ->orderBy('created_at', 'desc')
            ->take(9)
            ->get();

        // Ambil struktur organisasi yang aktif
        $struktur = StrukturOrganisasi::getActive();

        return view('profil-kesbangpol', compact('galeris', 'struktur'));
    }
}