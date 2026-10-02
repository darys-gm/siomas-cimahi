<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class RiwayatPengajuan extends Model
{
    use HasFactory;

    protected $table = 'riwayat_pengajuan';

    protected $fillable = ['ormas_id', 'status', 'catatan'];

    public function ormas()
    {
        return $this->belongsTo(Ormas::class);
    }
}