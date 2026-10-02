<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\LogAktivitas;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class LogController extends Controller
{
    /**
     * Minimal usia log (hari) yang boleh dihapus per-item.
     */
    private const MIN_LOG_AGE_DAYS = 30;

    public function index()
    {
        $logs = LogAktivitas::with('user')
            ->orderBy('created_at', 'desc')
            ->paginate(20);

        return view('admin.logs.index', compact('logs'));
    }

    /**
     * Hapus satu log per-item.
     * Hanya boleh hapus log yang usianya > 30 hari.
     */
    public function destroy($id)
    {
        try {
            Log::info('🔍 Delete log dipanggil', ['id' => $id, 'user_id' => Auth::id()]);

            if (!is_numeric($id) || (int) $id < 1) {
                return redirect()->back()->with('error', 'ID log tidak valid.');
            }

            $log = LogAktivitas::find($id);

            if (!$log) {
                return redirect()->back()->with('error', 'Log tidak ditemukan.');
            }

            // 🔒 PROTEKSI AUDIT TRAIL
            $logAgeInDays = $log->created_at->diffInDays(now());
            if ($logAgeInDays < self::MIN_LOG_AGE_DAYS) {
                return redirect()->back()->with(
                    'error',
                    'Log yang berusia kurang dari ' . self::MIN_LOG_AGE_DAYS . ' hari tidak dapat dihapus.'
                );
            }

            $log->delete();

            LogAktivitas::create([
                'user_id'    => Auth::id(),
                'aktivitas'  => 'Hapus Log',
                'deskripsi'  => 'Menghapus log ID ' . $id,
                'ip_address' => request()->ip(),
                'user_agent' => request()->userAgent(),
            ]);

            return redirect()->back()->with('success', 'Log berhasil dihapus.');

        } catch (\Exception $e) {
            Log::error('❌ Delete log error', [
                'message' => $e->getMessage(),
                'file'    => $e->getFile(),
                'line'    => $e->getLine(),
                'log_id'  => $id,
            ]);

            return redirect()->back()->with('error', 'Gagal menghapus log. Silakan coba lagi.');
        }
    }

    /**
     * Hapus SEMUA log.
     * 
     * 🔧 Tidak pakai password — karena hanya ada 1 admin.
     * 🔧 Pakai delete() bukan truncate() untuk kompatibilitas.
     */
    public function clear(Request $request)
    {
        try {
            Log::info('🔍 Clear all logs dipanggil', ['user_id' => Auth::id()]);

            // ============================================================
            // HITUNG TOTAL LOG SEBELUM HAPUS
            // ============================================================
            $totalLogs = LogAktivitas::count();

            Log::warning('⚠️ SEMUA LOG AKTIVITAS AKAN DIHAPUS', [
                'total_logs' => $totalLogs,
                'user_id'    => Auth::id(),
                'ip_address' => $request->ip(),
            ]);

            // ============================================================
            // HAPUS SEMUA LOG
            // 
            // Menggunakan DB::table()->delete() karena:
            // - Lebih universal dari truncate()
            // - Tidak butuh privilege DROP
            // - Aman untuk semua konfigurasi MySQL
            // ============================================================
            $deleted = DB::table('log_aktivitas')->delete();

            // ============================================================
            // VERIFIKASI
            // ============================================================
            $remaining = LogAktivitas::count();

            Log::info('✅ Log dihapus', [
                'deleted'      => $deleted,
                'total_before' => $totalLogs,
                'remaining'    => $remaining,
            ]);

            // ============================================================
            // CATAT AKSI INI SEBAGAI LOG BARU (JEJAK)
            // ============================================================
            LogAktivitas::create([
                'user_id'    => Auth::id(),
                'aktivitas'  => 'Clear All Logs',
                'deskripsi'  => 'Menghapus SEMUA log aktivitas (' . $deleted . ' log)',
                'ip_address' => $request->ip(),
                'user_agent' => $request->userAgent(),
            ]);

            return redirect()->back()->with(
                'success',
                'Semua log berhasil dihapus (' . $deleted . ' log). Aksi ini tercatat di sistem.'
            );

        } catch (\Exception $e) {
            Log::error('❌ Clear all logs error', [
                'message' => $e->getMessage(),
                'file'    => $e->getFile(),
                'line'    => $e->getLine(),
                'user_id' => Auth::id(),
            ]);

            return redirect()->back()->with(
                'error',
                'Gagal menghapus semua log. Detail: ' . $e->getMessage()
            );
        }
    }

    /**
     * Hapus log yang lebih tua dari 30 hari.
     */
    public function clearOld()
    {
        try {
            Log::info('🔍 Clear old logs dipanggil', ['user_id' => Auth::id()]);

            $deleted = LogAktivitas::where('created_at', '<', now()->subDays(self::MIN_LOG_AGE_DAYS))
                ->delete();

            $message = $deleted > 0
                ? "Berhasil menghapus {$deleted} log yang berusia lebih dari " . self::MIN_LOG_AGE_DAYS . " hari."
                : "Tidak ada log yang berusia lebih dari " . self::MIN_LOG_AGE_DAYS . " hari untuk dihapus.";

            if ($deleted > 0) {
                LogAktivitas::create([
                    'user_id'    => Auth::id(),
                    'aktivitas'  => 'Clear Old Logs',
                    'deskripsi'  => 'Menghapus ' . $deleted . ' log lama (>' . self::MIN_LOG_AGE_DAYS . ' hari)',
                    'ip_address' => request()->ip(),
                    'user_agent' => request()->userAgent(),
                ]);
            }

            return redirect()->back()->with('success', $message);

        } catch (\Exception $e) {
            Log::error('❌ Clear old logs error', [
                'message' => $e->getMessage(),
                'file'    => $e->getFile(),
                'line'    => $e->getLine(),
            ]);

            return redirect()->back()->with('error', 'Gagal menghapus log lama. Silakan coba lagi.');
        }
    }
}