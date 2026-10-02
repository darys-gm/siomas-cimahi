<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ProdukHukum extends Model
{
    use HasFactory;

    protected $table = 'produk_hukums';

    protected $fillable = [
        'judul',
        'keterangan',
        'file_pdf',
        'user_id',
        'is_active'
    ];

    protected $casts = [
        'is_active' => 'boolean',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    // Scope untuk produk hukum yang aktif
    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }
}