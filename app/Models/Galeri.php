<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Galeri extends Model
{
    use HasFactory;

    protected $table = 'galeris';

    // ============================================
    // KONSTANTA KATEGORI
    // ============================================
    const KATEGORI_ORMAS = 'ormas';
    const KATEGORI_BAKESBANGPOL = 'bakesbangpol';

    protected $fillable = [
        'judul',
        'kategori',
        'deskripsi',
        'gambar',
        'user_id',
        'is_active'
    ];

    protected $casts = [
        'is_active' => 'boolean',
    ];

    // ============================================
    // HELPER KATEGORI
    // ============================================

    /**
     * Daftar kategori yang tersedia (untuk dropdown)
     */
    public static function listKategori(): array
    {
        return [
            self::KATEGORI_BAKESBANGPOL => 'Galeri Bakesbangpol',
            self::KATEGORI_ORMAS => 'Galeri Ormas',
        ];
    }

    /**
     * Label kategori (untuk ditampilkan di view)
     */
    public function getKategoriLabelAttribute(): string
    {
        return self::listKategori()[$this->kategori] ?? 'Tidak Diketahui';
    }

    /**
     * Badge color untuk kategori (Tailwind CSS class)
     */
    public function getKategoriColorAttribute(): string
    {
        return match ($this->kategori) {
            self::KATEGORI_ORMAS => 'bg-blue-100 text-blue-800',
            self::KATEGORI_BAKESBANGPOL => 'bg-purple-100 text-purple-800',
            default => 'bg-gray-100 text-gray-800',
        };
    }

    // ============================================
    // RELASI
    // ============================================
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    // ============================================
    // SCOPE
    // ============================================

    /**
     * Scope untuk galeri yang aktif
     */
    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    /**
     * Scope untuk galeri kategori Ormas
     */
    public function scopeOrmas($query)
    {
        return $query->where('kategori', self::KATEGORI_ORMAS);
    }

    /**
     * Scope untuk galeri kategori Bakesbangpol
     */
    public function scopeBakesbangpol($query)
    {
        return $query->where('kategori', self::KATEGORI_BAKESBANGPOL);
    }
}