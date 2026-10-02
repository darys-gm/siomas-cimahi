<?php

namespace App\Services;

use App\Models\Visitor;
use Carbon\Carbon;
use Illuminate\Support\Facades\Cache;

class VisitorStatisticService
{
    protected int $cacheTtl = 300;

    public function getTodayCount(): int
    {
        return Cache::remember('visitor_today', $this->cacheTtl, function () {
            return Visitor::today()->count();
        });
    }

    public function getMonthCount(): int
    {
        return Cache::remember('visitor_month_' . Carbon::now('Asia/Jakarta')->format('Y_m'), $this->cacheTtl, function () {
            return Visitor::thisMonth()->count();
        });
    }

    public function getYearCount(): int
    {
        return Cache::remember('visitor_year_' . Carbon::now('Asia/Jakarta')->year, $this->cacheTtl, function () {
            return Visitor::thisYear()->count();
        });
    }

    public function getAllStats(): array
    {
        return [
            'today' => $this->getTodayCount(),
            'month' => $this->getMonthCount(),
            'year'  => $this->getYearCount(),
        ];
    }

    public function formatNumber(int $number): string
    {
        if ($number >= 1000000) {
            return number_format($number / 1000000, 1, ',', '.') . 'M';
        }
        if ($number >= 1000) {
            return number_format($number / 1000, 1, ',', '.') . 'K';
        }
        return number_format($number, 0, ',', '.');
    }
}