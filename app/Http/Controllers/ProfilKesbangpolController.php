<?php

namespace App\Http\Controllers;

use App\Models\StrukturOrganisasi;
use Illuminate\Http\Request;

class ProfilKesbangpolController extends Controller
{
    public function index()
    {
        // Ambil struktur organisasi yang aktif
        $struktur = StrukturOrganisasi::getActive();

        // Hanya mengirim data struktur (galeri dihapus)
        return view('profil-kesbangpol', compact('struktur'));
    }
}