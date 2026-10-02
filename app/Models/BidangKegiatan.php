<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class BidangKegiatan extends Model
{
    use HasFactory;

    protected $table = 'bidang_kegiatan';

    protected $fillable = ['nama'];

    public function ormas()
    {
        return $this->hasMany(Ormas::class);
    }
}