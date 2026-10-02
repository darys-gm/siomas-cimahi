<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Kelurahan extends Model
{
    use HasFactory;

    protected $table = 'kelurahan';

    protected $fillable = ['kecamatan_id', 'nama'];

    public function kecamatan()
    {
        return $this->belongsTo(Kecamatan::class);
    }

    public function ormas()
    {
        return $this->hasMany(Ormas::class);
    }
}