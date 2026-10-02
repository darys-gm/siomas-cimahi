<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;

class Visitor extends Model
{
    protected $fillable = [
        'ip_address',
        'user_agent',
        'visit_date',
        'first_visit_at',
        'last_visit_at',
        'total_visits',
    ];

    protected $casts = [
        'visit_date' => 'date',
        'first_visit_at' => 'datetime',
        'last_visit_at' => 'datetime',
    ];

    public function scopeToday($query)
    {
        return $query->whereDate('visit_date', Carbon::now('Asia/Jakarta')->toDateString());
    }

    public function scopeThisMonth($query)
    {
        return $query->whereYear('visit_date', Carbon::now('Asia/Jakarta')->year)
                     ->whereMonth('visit_date', Carbon::now('Asia/Jakarta')->month);
    }

    public function scopeThisYear($query)
    {
        return $query->whereYear('visit_date', Carbon::now('Asia/Jakarta')->year);
    }
}