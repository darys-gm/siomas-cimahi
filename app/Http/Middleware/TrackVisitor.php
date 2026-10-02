<?php

namespace App\Http\Middleware;

use App\Models\Visitor;
use Carbon\Carbon;
use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Log;

class TrackVisitor
{
    protected array $bots = [
        'bot', 'crawl', 'spider', 'slurp', 'facebookexternalhit',
        'whatsapp', 'telegram', 'twitterbot', 'linkedinbot',
        'googlebot', 'bingbot', 'yandexbot', 'duckduckbot',
        'baiduspider', 'semrush', 'ahrefs', 'mj12bot',
    ];

    public function handle(Request $request, Closure $next)
    {
        $response = $next($request);

        if ($this->shouldTrack($request)) {
            try {
                $this->track($request);
            } catch (\Throwable $e) {
                // 🔒 JANGAN log $e->getMessage() mentah-mentah
                Log::warning('TrackVisitor failed', [
                    'error_class' => get_class($e),
                    'file'        => basename($e->getFile()),
                    'line'        => $e->getLine(),
                ]);
            }
        }

        return $response;
    }

    protected function shouldTrack(Request $request): bool
    {
        if (!$request->isMethod('GET')) return false;
        if ($request->ajax() || $request->expectsJson()) return false;

        $path = trim($request->path(), '/');
        $skipPrefixes = ['admin', 'login', 'logout', 'api', '_debugbar', 'storage', 'build'];
        foreach ($skipPrefixes as $prefix) {
            if ($path === $prefix || str_starts_with($path, $prefix . '/')) return false;
        }

        $acceptHeader = $request->header('Accept', '');
        if (!str_contains($acceptHeader, 'text/html')) return false;

        $userAgent = strtolower($request->userAgent() ?? '');
        foreach ($this->bots as $bot) {
            if (str_contains($userAgent, $bot)) return false;
        }

        // 🔒 Validasi format IP
        $ip = $request->ip();
        if (!$ip || filter_var($ip, FILTER_VALIDATE_IP) === false) {
            return false;
        }

        return true;
    }

    protected function track(Request $request): void
    {
        // 🔒 Amankan IP: gunakan $request->ip() yang sudah melewati trusted proxy check Laravel
        $ip = $request->ip();

        // Double-check: hanya simpan kalau valid IP
        if (filter_var($ip, FILTER_VALIDATE_IP) === false) {
            return;
        }

        $today    = Carbon::now('Asia/Jakarta')->toDateString();
        $cacheKey = 'visitor_tracked_' . md5($ip . $today);

        if (Cache::has($cacheKey)) {
            Visitor::where('ip_address', $ip)
                ->whereDate('visit_date', $today)
                ->update(['last_visit_at' => Carbon::now('Asia/Jakarta')]);
            return;
        }

        Cache::put($cacheKey, true, now()->addHour());

        $visitor = Visitor::where('ip_address', $ip)
            ->whereDate('visit_date', $today)
            ->first();

        if ($visitor) {
            $visitor->increment('total_visits');
            $visitor->update(['last_visit_at' => Carbon::now('Asia/Jakarta')]);
        } else {
            Visitor::create([
                'ip_address'     => $ip,
                'user_agent'     => substr($request->userAgent() ?? '', 0, 255),
                'visit_date'     => $today,
                'first_visit_at' => Carbon::now('Asia/Jakarta'),
                'last_visit_at'  => Carbon::now('Asia/Jakarta'),
                'total_visits'   => 1,
            ]);
        }
    }
}