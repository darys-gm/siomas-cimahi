<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\LogAktivitas;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\RateLimiter;

class LoginController extends Controller
{
    public function showLoginForm()
    {
        if (Auth::check()) {
            if (Auth::user()->role === 'admin') {
                return redirect()->route('admin.dashboard');
            }
            Auth::logout();
            return redirect()->route('login')->with('error', 'Hanya admin yang dapat mengakses halaman ini.');
        }
        return view('auth.login');
    }

    public function login(Request $request)
    {
        // ============================================================
        // 🔒 VALIDASI INPUT
        // ============================================================
        $validated = $request->validate([
            'username' => 'required|string|max:100',
            'password' => 'required|string|max:255',
        ]);

        // ============================================================
        // 🔒 ANTI BRUTE-FORCE
        // ============================================================
        $throttleKey = 'login:' . strtolower($validated['username']) . '|' . $request->ip();

        if (RateLimiter::tooManyAttempts($throttleKey, 5)) {
            $seconds = RateLimiter::availableIn($throttleKey);
            Log::warning('Login throttled', [
                'username' => $validated['username'],
                'ip'       => $request->ip(),
            ]);

            return back()
                ->withErrors(['username' => "Terlalu banyak percobaan login. Silakan coba lagi dalam {$seconds} detik."])
                ->onlyInput('username');
        }

        // ============================================================
        // 🔒 ANTI USER ENUMERATION + TIMING ATTACK
        // ============================================================
        // Ambil user, kalau tidak ada pakai dummy untuk konsisten timing.
        $user = User::where('username', $validated['username'])->first();

        // Dummy hash valid (bcrypt) - cache agar tidak dibuat ulang tiap request
        $dummyHash = Cache::rememberForever('dummy_bcrypt_hash', function () {
            return Hash::make('dummy_password_never_used_' . config('app.key'));
        });

        $passwordValid = $user
            ? Hash::check($validated['password'], $user->password)
            : Hash::check($validated['password'], $dummyHash);

        // ============================================================
        // 🔒 JIKA GAGAL: LOG & PESAN GENERIK
        // ============================================================
        if (!$user || !$passwordValid) {
            RateLimiter::hit($throttleKey, 60);

            Log::warning('Failed login attempt', [
                'username'   => $validated['username'],
                'ip'         => $request->ip(),
                'user_agent' => $request->userAgent(),
            ]);

            return back()
                ->withErrors(['username' => 'Username atau password salah.'])
                ->onlyInput('username');
        }

        // ============================================================
        // 🔒 CEK STATUS AKTIF & ROLE
        // ============================================================
        if (!$user->is_active) {
            RateLimiter::hit($throttleKey, 60);

            Log::warning('Login attempt on inactive account', [
                'username' => $validated['username'],
                'ip'       => $request->ip(),
            ]);

            return back()
                ->withErrors(['username' => 'Akun Anda dinonaktifkan. Hubungi admin.'])
                ->onlyInput('username');
        }

        if ($user->role !== 'admin') {
            RateLimiter::hit($throttleKey, 60);

            Log::warning('Non-admin login attempt', [
                'username' => $validated['username'],
                'role'     => $user->role,
                'ip'       => $request->ip(),
            ]);

            return back()
                ->withErrors(['username' => 'Anda tidak memiliki akses ke halaman ini.'])
                ->onlyInput('username');
        }

        // ============================================================
        // 🔒 LOGIN BERHASIL
        // ============================================================
        $request->session()->regenerate();

        Auth::login($user);

        RateLimiter::clear($throttleKey);

        LogAktivitas::create([
            'user_id'    => $user->id,
            'aktivitas'  => 'Login',
            'deskripsi'  => 'Admin login ke sistem dengan username: ' . $user->username,
            'ip_address' => $request->ip(),
            'user_agent' => $request->userAgent(),
        ]);

        return redirect()->route('admin.dashboard');
    }

    public function logout(Request $request)
    {
        if (Auth::check()) {
            LogAktivitas::create([
                'user_id'    => Auth::id(),
                'aktivitas'  => 'Logout',
                'deskripsi'  => 'Admin logout dari sistem',
                'ip_address' => $request->ip(),
                'user_agent' => $request->userAgent(),
            ]);
        }

        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect('/');
    }
}