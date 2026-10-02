<?php
// app/Models/Agenda.php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;

class Agenda extends Model
{
    use HasFactory;

    protected $table = 'agendas';

    protected $fillable = [
        'judul',
        'tanggal',
        'is_active',
    ];

    // Casting yang benar untuk date
    protected $casts = [
        'tanggal' => 'date:Y-m-d', // Format spesifik Y-m-d
        'is_active' => 'boolean',
    ];

    // Accessor untuk status badge
    public function getStatusBadgeAttribute()
    {
        return $this->is_active ? 'bg-green-100 text-green-700' : 'bg-red-100 text-red-700';
    }

    public function getStatusTextAttribute()
    {
        return $this->is_active ? 'Aktif' : 'Nonaktif';
    }

    // Accessor untuk tanggal format Indonesia
    public function getTanggalFormattedAttribute()
    {
        return $this->tanggal ? Carbon::parse($this->tanggal)->translatedFormat('d F Y') : '-';
    }

    // Accessor untuk hari dalam bahasa Indonesia
    public function getHariAttribute()
    {
        return $this->tanggal ? Carbon::parse($this->tanggal)->translatedFormat('l') : '-';
    }

    // Accessor untuk tanggal dalam format array (untuk JavaScript)
    public function getTanggalArrayAttribute()
    {
        if (!$this->tanggal) return null;
        return [
            'day' => Carbon::parse($this->tanggal)->format('d'),
            'month' => Carbon::parse($this->tanggal)->format('M'),
            'month_full' => Carbon::parse($this->tanggal)->translatedFormat('F'),
            'year' => Carbon::parse($this->tanggal)->format('Y'),
            'day_name' => Carbon::parse($this->tanggal)->translatedFormat('l'),
            'formatted' => Carbon::parse($this->tanggal)->translatedFormat('l, d F Y'),
        ];
    }

    // ============================================
    // SCOPES (Query Builder)
    // ============================================

    // Scope untuk agenda aktif
    public function scopeActive($query)
    {
        return $query->where('is_active', 1);
    }

    // Scope untuk agenda tidak aktif
    public function scopeInactive($query)
    {
        return $query->where('is_active', 0);
    }

    // Scope untuk agenda hari ini (PASTI BERHASIL)
    public function scopeToday($query)
    {
        $today = Carbon::now('Asia/Jakarta')->toDateString(); // Format: Y-m-d
        return $query->whereDate('tanggal', $today);
    }

    // Scope untuk agenda berdasarkan tanggal tertentu
    public function scopeOnDate($query, $date)
    {
        return $query->whereDate('tanggal', $date);
    }

    // Scope untuk agenda yang akan datang
    public function scopeUpcoming($query)
    {
        $today = Carbon::now('Asia/Jakarta')->toDateString();
        return $query->whereDate('tanggal', '>=', $today);
    }

    // Scope untuk agenda yang sudah lewat
    public function scopePast($query)
    {
        $today = Carbon::now('Asia/Jakarta')->toDateString();
        return $query->whereDate('tanggal', '<', $today);
    }

    // Scope untuk agenda dalam rentang tanggal
    public function scopeDateBetween($query, $startDate, $endDate)
    {
        return $query->whereBetween('tanggal', [$startDate, $endDate]);
    }

    // Scope untuk agenda bulan ini
    public function scopeThisMonth($query)
    {
        $startOfMonth = Carbon::now('Asia/Jakarta')->startOfMonth()->toDateString();
        $endOfMonth = Carbon::now('Asia/Jakarta')->endOfMonth()->toDateString();
        return $query->whereBetween('tanggal', [$startOfMonth, $endOfMonth]);
    }

    // Scope untuk agenda berdasarkan bulan dan tahun
    public function scopeMonthYear($query, $month, $year)
    {
        $startDate = Carbon::createFromDate($year, $month, 1)->startOfMonth()->toDateString();
        $endDate = Carbon::createFromDate($year, $month, 1)->endOfMonth()->toDateString();
        return $query->whereBetween('tanggal', [$startDate, $endDate]);
    }
}