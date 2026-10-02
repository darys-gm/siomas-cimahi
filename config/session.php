<?php

use Illuminate\Support\Str;

return [

    'driver' => env('SESSION_DRIVER', 'database'),

    'lifetime' => (int) env('SESSION_LIFETIME', 120),

    'expire_on_close' => env('SESSION_EXPIRE_ON_CLOSE', false),

    /*
    |--------------------------------------------------------------------------
    | 🔒 Session Encryption
    |--------------------------------------------------------------------------
    | Enkripsi data session. Aktifkan untuk keamanan tambahan.
    */
    'encrypt' => env('SESSION_ENCRYPT', true),

    'files' => storage_path('framework/sessions'),

    'connection' => env('SESSION_CONNECTION'),

    'table' => env('SESSION_TABLE', 'sessions'),

    'store' => env('SESSION_STORE'),

    'lottery' => [2, 100],

    'cookie' => env(
        'SESSION_COOKIE',
        Str::slug((string) env('APP_NAME', 'laravel')) . '-session'
    ),

    'path' => env('SESSION_PATH', '/'),

    'domain' => env('SESSION_DOMAIN'),

    /*
    |--------------------------------------------------------------------------
    | 🔒 HTTPS Only Cookies
    |--------------------------------------------------------------------------
    | Cookie hanya dikirim melalui HTTPS. Default TRUE di production.
    | Set ke FALSE di .env kalau development di http://localhost
    */
    'secure' => env('SESSION_SECURE_COOKIE', env('APP_ENV', 'production') === 'production'),

    /*
    |--------------------------------------------------------------------------
    | 🔒 HTTP Access Only
    |--------------------------------------------------------------------------
    | Cookie hanya bisa diakses via HTTP, tidak dari JavaScript (anti XSS).
    */
    'http_only' => env('SESSION_HTTP_ONLY', true),

    /*
    |--------------------------------------------------------------------------
    | 🔒 Same-Site Cookies
    |--------------------------------------------------------------------------
    | "lax" untuk keamanan + UX. Jangan ganti ke "none" kecuali butuh
    | cross-site request.
    */
    'same_site' => env('SESSION_SAME_SITE', 'lax'),

    'partitioned' => env('SESSION_PARTITIONED_COOKIE', false),

];