<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Kecamatan extends Model
{
    use HasFactory;

    protected $table = 'kecamatan';

    protected $fillable = ['nama'];

    public function kelurahan()
    {
        return $this->hasMany(Kelurahan::class);
    }

    public function ormas()
    {
        return $this->hasMany(Ormas::class);
    }
}