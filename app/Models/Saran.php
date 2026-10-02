<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Saran extends Model
{
    use HasFactory;

    protected $table = 'saran';

    protected $fillable = [
        'nama',
        'email',
        'pesan',
        'status',
        'ip_address'
    ];

    protected $casts = [
        'created_at' => 'datetime',
    ];

    // Mutator untuk status badge
    public function getStatusBadgeAttribute()
    {
        return $this->status === 'baru' ? 'bg-yellow-500' : 'bg-green-500';
    }

    public function getStatusTextAttribute()
    {
        return $this->status === 'baru' ? 'Belum Dibaca' : 'Sudah Dibaca';
    }
}