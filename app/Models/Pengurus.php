<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Pengurus extends Model
{
    use HasFactory;

    protected $table = 'pengurus';

    protected $fillable = [
        'ormas_id',
        'nama',
        'alamat',
        'nik',
        'no_hp',
        // HAPUS: 'jabatan', 'masa_jabatan_mulai', 'masa_jabatan_selesai'
    ];

    protected $casts = [
        // HAPUS: 'masa_jabatan_mulai' => 'date',
        // HAPUS: 'masa_jabatan_selesai' => 'date',
    ];

    // ===== RELASI =====
    public function ormas()
    {
        return $this->belongsTo(Ormas::class);
    }

    // ===== ACCESSOR =====
    // Tidak ada accessor untuk jabatan karena sudah dihapus
}