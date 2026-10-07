<?php
// app/Http/Controllers/Admin/AdminSettingController.php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Setting;
use App\Models\LogAktivitas;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Validation\ValidationException;

class AdminSettingController extends Controller
{
    /**
     * Default value running text
     */
    private const DEFAULT_RUNNING_TEXT  = 'Selamat datang di SIOMAS Kota Cimahi - Sistem Informasi Organisasi Masyarakat';
    private const DEFAULT_RUNNING_SPEED = '20';

    // ============================================================
    // INDEX — Halaman Manajemen Home
    // ============================================================
    public function index()
    {
        $runningText  = Setting::get('running_text', self::DEFAULT_RUNNING_TEXT);
        $runningSpeed = Setting::get('running_speed', self::DEFAULT_RUNNING_SPEED);

        return view('admin.setting.home', compact('runningText', 'runningSpeed'));
    }

    // ============================================================
    // UPDATE RUNNING TEXT
    // ============================================================
    public function updateRunningText(Request $request)
    {
        try {
            $validated = $request->validate([
                'running_text'  => 'required|string|max:1000',
                'running_speed' => 'required|integer|min:5|max:60',
            ]);

            // Pisahkan per baris, trim, buang yang kosong
            $texts = array_filter(array_map('trim', explode("\n", $validated['running_text'])));

            // Gabungkan dengan separator bullet
            $formattedText = implode(' • ', $texts);

            // Simpan ke database dalam 1 transaksi (atomic)
            DB::transaction(function () use ($formattedText, $validated) {
                Setting::set('running_text', $formattedText);
                Setting::set('running_speed', $validated['running_speed']);
            });

            $this->logAktivitas(
                $request,
                'Update Running Text',
                "Mengupdate running text dan kecepatan menjadi: {$validated['running_speed']}s"
            );

            return redirect()
                ->route('admin.setting.home')
                ->with('success', 'Running text berhasil diperbarui.');

        } catch (ValidationException $e) {
            return back()->withErrors($e->validator)->withInput();

        } catch (\Exception $e) {
            Log::error('Update running text error', [
                'message' => $e->getMessage(),
                'file'    => $e->getFile(),
                'line'    => $e->getLine(),
            ]);

            return back()
                ->with('error', 'Gagal mengupdate running text. Silakan coba lagi.')
                ->withInput();
        }
    }

    // ============================================================
    // PRIVATE HELPERS
    // ============================================================

    /**
     * Helper log aktivitas — konsisten dengan controller lain.
     */
    private function logAktivitas(Request $request, string $aktivitas, string $deskripsi): void
    {
        LogAktivitas::create([
            'user_id'    => (int) Auth::id(),
            'aktivitas'  => $aktivitas,
            'deskripsi'  => $deskripsi,
            'ip_address' => $request->ip(),
            'user_agent' => $request->userAgent(),
        ]);
    }
}