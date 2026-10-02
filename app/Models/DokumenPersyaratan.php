<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DokumenPersyaratan extends Model
{
    use HasFactory;

    protected $table = 'dokumen_persyaratan';

    protected $fillable = ['nama', 'keterangan'];
}