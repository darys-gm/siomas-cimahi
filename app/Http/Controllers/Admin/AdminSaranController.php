<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Saran;
use App\Models\LogAktivitas;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

class AdminSaranController extends Controller
{
    public function index()
    {
        $saran = Saran::orderBy('created_at', 'desc')->paginate(15);
        $totalBaru = Saran::where('status', 'baru')->count();

        return view('admin.saran.index', compact('saran', 'totalBaru'));
    }

    /**
     * Cek apakah ada saran baru (untuk polling).
     */
    public function checkNew()
    {
        try {
            $total = Saran::count();
            $totalBaru = Saran::where('status', 'baru')->count();

            return response()->json([
                'success'   => true,
                'total'     => $total,
                'totalBaru' => $totalBaru,
            ]);

        } catch (\Exception $e) {
            Log::error('Check new saran error', [
                'message' => $e->getMessage(),
                'file'    => $e->getFile(),
                'line'    => $e->getLine(),
            ]);

            return response()->json([
                'success' => false,
                'message' => 'Gagal memuat data. Silakan coba lagi.',
            ], 500);
        }
    }

    /**
     * Refresh data saran (untuk polling).
     */
    public function refresh()
    {
        try {
            $saran = Saran::orderBy('created_at', 'desc')->paginate(15);
            $totalBaru = Saran::where('status', 'baru')->count();

            $html = view('admin.saran.partials.table_rows', compact('saran'))->render();
            $pagination = $saran->hasPages() ? $saran->links()->render() : '';

            return response()->json([
                'success'    => true,
                'html'       => $html,
                'pagination' => $pagination,
                'total'      => $saran->total(),
                'totalBaru'  => $totalBaru,
            ]);

        } catch (\Exception $e) {
            Log::error('Refresh saran error', [
                'message' => $e->getMessage(),
                'file'    => $e->getFile(),
                'line'    => $e->getLine(),
            ]);

            return response()->json([
                'success' => false,
                'message' => 'Gagal memuat data. Silakan coba lagi.',
            ], 500);
        }
    }

    /**
     * Tandai saran sebagai sudah dibaca.
     */
    public function tandaiDibaca($id)
    {
        try {
            // ============================================================
            // 🔒 VALIDASI ID
            // ============================================================
            if (!is_numeric($id) || (int) $id < 1) {
                return response()->json([
                    'success' => false,
                    'message' => 'ID saran tidak valid.',
                ], 400);
            }

            $saran = Saran::find((int) $id);

            if (!$saran) {
                return response()->json([
                    'success' => false,
                    'message' => 'Saran tidak ditemukan.',
                ], 404);
            }

            // ============================================================
            // 🔒 VALIDASI STATUS TRANSITION
            // ============================================================
            if ($saran->status === 'dibaca') {
                return response()->json([
                    'success' => false,
                    'message' => 'Saran ini sudah ditandai sebagai dibaca sebelumnya.',
                ], 400);
            }

            // ============================================================
            // 🔒 EXPLICIT ASSIGN
            // ============================================================
            $saran->status = 'dibaca';
            $saran->save();

            LogAktivitas::create([
                'user_id'    => Auth::id(),
                'aktivitas'  => 'Tandai Saran Dibaca',
                'deskripsi'  => "Menandai saran dari {$saran->nama} sebagai sudah dibaca",
                'ip_address' => request()->ip(),
                'user_agent' => request()->userAgent(),
            ]);

            return response()->json([
                'success' => true,
                'message' => 'Saran telah ditandai sebagai sudah dibaca.',
            ]);

        } catch (\Exception $e) {
            Log::error('Tandai saran dibaca error', [
                'message' => $e->getMessage(),
                'file'    => $e->getFile(),
                'line'    => $e->getLine(),
                'saran_id' => $id,
            ]);

            return response()->json([
                'success' => false,
                'message' => 'Gagal menandai saran. Silakan coba lagi.',
            ], 500);
        }
    }

    /**
     * Hapus saran.
     */
    public function destroy($id)
    {
        try {
            // ============================================================
            // 🔒 VALIDASI ID
            // ============================================================
            if (!is_numeric($id) || (int) $id < 1) {
                return response()->json([
                    'success' => false,
                    'message' => 'ID saran tidak valid.',
                ], 400);
            }

            $saran = Saran::find((int) $id);

            if (!$saran) {
                return response()->json([
                    'success' => false,
                    'message' => 'Saran tidak ditemukan.',
                ], 404);
            }

            $nama = $saran->nama;
            $saran->delete();

            LogAktivitas::create([
                'user_id'    => Auth::id(),
                'aktivitas'  => 'Hapus Saran',
                'deskripsi'  => "Menghapus saran dari {$nama}",
                'ip_address' => request()->ip(),
                'user_agent' => request()->userAgent(),
            ]);

            return response()->json([
                'success' => true,
                'message' => 'Saran berhasil dihapus.',
            ]);

        } catch (\Exception $e) {
            Log::error('Hapus saran error', [
                'message'  => $e->getMessage(),
                'file'     => $e->getFile(),
                'line'     => $e->getLine(),
                'saran_id' => $id,
            ]);

            return response()->json([
                'success' => false,
                'message' => 'Gagal menghapus saran. Silakan coba lagi.',
            ], 500);
        }
    }
}