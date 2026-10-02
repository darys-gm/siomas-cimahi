<?php
// app/Http/Controllers/PosterController.php

namespace App\Http\Controllers;

use App\Models\Poster;
use Illuminate\Http\Request;

class PosterController extends Controller
{
    public function index()
    {
        $posters = Poster::active()->ordered()->get();
        return view('posters.index', compact('posters'));
    }
}