<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class StrukturOrganisasi extends Model
{
    use HasFactory;

    protected $table = 'struktur_organisasi';

    protected $fillable = [
        'gambar',
    ];

    /**
     * Ambil struktur organisasi yang aktif (single).
     * Karena hanya 1 admin dan hanya 1 struktur yang aktif,
     * cukup ambil data pertama.
     */
    public static function getActive(): ?self
    {
        return self::latest('updated_at')->first();
    }
}