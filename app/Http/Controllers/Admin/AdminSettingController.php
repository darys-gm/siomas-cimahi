<?php
// app/Http/Controllers/Admin/AdminSettingController.php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Setting;
use App\Models\LogAktivitas;
use Illuminate\Http\Request;

class AdminSettingController extends Controller
{
    /**
     * Menampilkan halaman manajemen home
     */
    public function index()
    {
        $runningText = Setting::get('running_text', 'Selamat datang di SIOMAS Kota Cimahi - Sistem Informasi Organisasi Masyarakat');
        $runningSpeed = Setting::get('running_speed', '20');
        
        return view('admin.setting.home', compact('runningText', 'runningSpeed'));
    }

    /**
     * Update running text
     */
    public function updateRunningText(Request $request)
    {
        try {
            $request->validate([
                'running_text' => 'required|string|max:1000',
                'running_speed' => 'required|integer|min:5|max:60',
            ]);

            // Pisahkan kalimat dengan newline atau titik
            $texts = array_filter(array_map('trim', explode("\n", $request->running_text)));
            
            // Gabungkan dengan pemisah bullet atau separator
            $formattedText = implode(' • ', $texts);
            
            Setting::set('running_text', $formattedText);
            Setting::set('running_speed', $request->running_speed);

            LogAktivitas::create([
                'user_id' => auth()->id(),
                'aktivitas' => 'Update Running Text',
                'deskripsi' => "Mengupdate running text dan kecepatan menjadi: {$request->running_speed}s",
                'ip_address' => $request->ip(),
                'user_agent' => $request->userAgent()
            ]);

            return redirect()->route('admin.setting.home')
                ->with('success', 'Running text berhasil diperbarui.');

        } catch (\Exception $e) {
            return back()->with('error', 'Gagal mengupdate running text: ' . $e->getMessage());
        }
    }
}