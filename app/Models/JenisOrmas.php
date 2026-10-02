<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class JenisOrmas extends Model
{
    use HasFactory;

    protected $table = 'jenis_ormas';

    protected $fillable = ['nama'];

    public function ormas()
    {
        return $this->hasMany(Ormas::class);
    }
}