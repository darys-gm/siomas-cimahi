<?php

namespace App\Http\Controllers;

use App\Models\Saran;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class SaranController extends Controller
{
    public function index()
    {
        return view('saran');
    }

    public function store(Request $request)
    {
        try {
            // ============================================================
            // 🔒 ANTI-BOT: Honeypot
            // Form di view harus punya input hidden name="website_url" yang
            // seharusnya KOSONG. Kalau bot mengisi, langsung tolak.
            // ============================================================
            if ($request->filled('website_url')) {
                Log::warning('Honeypot triggered on saran form', [
                    'ip'         => $request->ip(),
                    'user_agent' => $request->userAgent(),
                ]);

                // Response sukses palsu agar bot tidak sadar ketahuan
                return response()->json([
                    'success' => true,
                    'message' => 'Saran Anda berhasil dikirim. Terima kasih!'
                ]);
            }

            // ============================================================
            // 🔒 VALIDASI INPUT
            // ============================================================
            $validated = $request->validate([
                'nama'  => 'required|string|min:2|max:255',
                'email' => 'nullable|email|max:255',
                'pesan' => 'required|string|min:10|max:5000',
            ], [
                'pesan.min' => 'Pesan minimal 10 karakter.',
                'pesan.max' => 'Pesan maksimal 5000 karakter.',
            ]);

            $saran = Saran::create([
                'nama'       => $validated['nama'],
                'email'      => $validated['email'] ?? null,
                'pesan'      => $validated['pesan'],
                'status'     => 'baru',
                'ip_address' => $request->ip(),
            ]);

            return response()->json([
                'success' => true,
                'message' => 'Saran Anda berhasil dikirim. Terima kasih!'
            ]);

        } catch (\Illuminate\Validation\ValidationException $e) {
            return response()->json([
                'success' => false,
                'message' => 'Mohon periksa kembali input Anda.',
                'errors'  => $e->errors()
            ], 422);

        } catch (\Exception $e) {
            // ============================================================
            // 🔒 LOG DI SERVER, JANGAN BOCOR KE USER
            // ============================================================
            Log::error('Error saat menyimpan saran', [
                'message'    => $e->getMessage(),
                'file'       => $e->getFile(),
                'line'       => $e->getLine(),
                'ip'         => $request->ip(),
                'user_agent' => $request->userAgent(),
            ]);

            return response()->json([
                'success' => false,
                'message' => 'Terjadi kesalahan. Silakan coba lagi beberapa saat.'
            ], 500);
        }
    }
}