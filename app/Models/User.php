<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    use HasFactory, Notifiable;

    /**
     * Field yang boleh di mass-assign.
     * 
     * ⚠️ PENTING: 'role' dan 'is_active' TIDAK dimasukkan ke sini
     * untuk mencegah privilege escalation.
     * 
     * Gunakan:
     *   $user->role = 'admin';
     *   $user->is_active = true;
     *   $user->save();
     * 
     * Bukan:
     *   User::create($request->all());
     */
    protected $fillable = [
        'username',
        'name',
        'email',
        'password',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
            'is_active' => 'boolean',
        ];
    }

    // ============================================================
    // RELASI
    // ============================================================
    public function ormas()
    {
        return $this->hasMany(Ormas::class, 'user_id');
    }

    public function logAktivitas()
    {
        return $this->hasMany(LogAktivitas::class);
    }

    // ============================================================
    // SCOPE — UNTUK KEAMANAN QUERY
    // ============================================================

    /**
     * Scope: hanya user dengan role admin.
     */
    public function scopeAdmin($query)
    {
        return $query->where('role', 'admin');
    }

    /**
     * Scope: hanya user yang aktif.
     */
    public function scopeAktif($query)
    {
        return $query->where('is_active', true);
    }

    // ============================================================
    // HELPER METHOD
    // ============================================================

    /**
     * Cek apakah user ini admin.
     */
    public function isAdmin(): bool
    {
        return $this->role === 'admin';
    }

    /**
     * Cek apakah user ini aktif.
     */
    public function isActive(): bool
    {
        return (bool) $this->is_active;
    }
}