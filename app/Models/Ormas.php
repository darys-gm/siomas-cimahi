<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Ormas extends Model
{
    use HasFactory;

    protected $table = 'ormas';

    /**
     * Field yang boleh di mass-assign.
     * HATI-HATI: Jangan tambahkan 'status', 'verified_at', 'user_id' di sini
     * karena field tsb harus di-set eksplisit oleh sistem, bukan dari input user.
     */
    protected $fillable = [
        'nama',
        'singkatan',
        'nomor_registrasi',
        'nomor_sk',
        'tahun_berdiri',
        'jenis_ormas_id',
        'bidang_kegiatan_id',
        'alamat',
        'alamat_kesekretariatan',
        'jumlah_anggota',
        'jumlah_anggota_perempuan',
        'anggota_perempuan_rentang_16_30',
        'jumlah_anggota_laki_laki',
        'anggota_laki_laki_rentang_16_30',
        'latitude',
        'longitude',
        'kecamatan_id',
        'kelurahan_id',
        'email',
        'no_telepon',
        'website',
        'logo',
        'deskripsi',
        'catatan_revisi',
        'pelaporan',
    ];

    /**
     * Field yang HANYA boleh di-set oleh sistem internal.
     * Gunakan $ormas->status = 'disetujui'; bukan $ormas->update($request->all()).
     */
    protected $guarded = [
        'id',
        'user_id',
        'status',
        'is_active',
        'verified_at',
    ];

    protected $casts = [
        'tahun_berdiri' => 'integer',
        'verified_at' => 'datetime',
        'is_active' => 'boolean',
        'jumlah_anggota' => 'integer',
        'jumlah_anggota_perempuan' => 'integer',
        'anggota_perempuan_rentang_16_30' => 'integer',
        'jumlah_anggota_laki_laki' => 'integer',
        'anggota_laki_laki_rentang_16_30' => 'integer',
    ];

    /**
     * Field yang TIDAK ditampilkan ketika model di-serialize ke JSON.
     * Berguna untuk endpoint publik.
     */
    protected $hidden = [
        'user_id',
        'catatan_revisi',
        'verified_at',
    ];

    // ===== RELASI =====
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function jenisOrmas()
    {
        return $this->belongsTo(JenisOrmas::class);
    }

    public function bidangKegiatan()
    {
        return $this->belongsTo(BidangKegiatan::class);
    }

    public function kecamatan()
    {
        return $this->belongsTo(Kecamatan::class);
    }

    public function kelurahan()
    {
        return $this->belongsTo(Kelurahan::class);
    }

    public function pengurus()
    {
        return $this->hasMany(Pengurus::class);
    }

    public function riwayatPengajuan()
    {
        return $this->hasMany(RiwayatPengajuan::class);
    }

    // ============================================================
    // SCOPE — UNTUK KEAMANAN QUERY
    // ============================================================

    /**
     * Scope: hanya ORMAS dengan status 'disetujui' (boleh tampil di publik).
     */
    public function scopeDisetujui($query)
    {
        return $query->where('status', 'disetujui');
    }

    /**
     * Scope: hanya ORMAS yang aktif.
     */
    public function scopeAktif($query)
    {
        return $query->where('is_active', true);
    }

    /**
     * Scope: ORMAS yang boleh dilihat publik (status disetujui + aktif).
     * WAJIB digunakan di semua endpoint publik.
     */
    public function scopePublicVisible($query)
    {
        return $query->where('status', 'disetujui')->where('is_active', true);
    }

    // ============================================================
    // ACCESSOR — STATUS BADGE
    // ============================================================
    public function getStatusBadgeAttribute()
    {
        $badges = [
            'draft' => 'bg-gray-500',
            'menunggu_verifikasi' => 'bg-yellow-500',
            'revisi' => 'bg-orange-500',
            'disetujui' => 'bg-green-500',
            'ditolak' => 'bg-red-500',
        ];

        return $badges[$this->status] ?? 'bg-gray-500';
    }

    public function getStatusTextAttribute()
    {
        $texts = [
            'draft' => 'Draft',
            'menunggu_verifikasi' => 'Menunggu Verifikasi',
            'revisi' => 'Revisi',
            'disetujui' => 'Disetujui',
            'ditolak' => 'Ditolak',
        ];

        return $texts[$this->status] ?? $this->status;
    }

    public function getStatusActiveBadgeAttribute()
    {
        return $this->is_active ? 'bg-green-100 text-green-700' : 'bg-red-100 text-red-700';
    }

    public function getStatusActiveTextAttribute()
    {
        return $this->is_active ? 'Aktif' : 'Nonaktif';
    }

    public function getPelaporanTextAttribute()
    {
        $labels = [
            'sudah' => 'Sudah',
            'belum' => 'Belum',
            'tidak_ada' => 'Tidak Ada'
        ];
        return $labels[$this->pelaporan] ?? 'Belum';
    }

    public function getPelaporanBadgeAttribute()
    {
        $colors = [
            'sudah' => 'bg-green-100 text-green-700',
            'belum' => 'bg-yellow-100 text-yellow-700',
            'tidak_ada' => 'bg-gray-100 text-gray-700'
        ];
        return $colors[$this->pelaporan] ?? 'bg-gray-100 text-gray-700';
    }

    // ===== METHOD LAINNYA =====
    public function isComplete()
    {
        return $this->nama && 
               $this->jenis_ormas_id && 
               $this->bidang_kegiatan_id && 
               $this->alamat_kesekretariatan &&
               $this->kecamatan_id &&
               $this->kelurahan_id &&
               $this->pengurus()->count() > 0;
    }
}