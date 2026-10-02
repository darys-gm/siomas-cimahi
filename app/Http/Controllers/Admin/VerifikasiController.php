<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Ormas;
use App\Models\RiwayatPengajuan;
use App\Models\LogAktivitas;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

class VerifikasiController extends Controller
{
    public function index()
    {
        $ormas = Ormas::whereIn('status', ['menunggu_verifikasi', 'revisi'])
            ->with(['user', 'jenisOrmas', 'kecamatan', 'kelurahan'])
            ->orderBy('created_at', 'asc')
            ->paginate(10);

        return view('admin.verifikasi.index', compact('ormas'));
    }

    public function show($id)
    {
        if (!is_numeric($id) || (int) $id < 1) {
            abort(404);
        }

        $ormas = Ormas::with(['user', 'jenisOrmas', 'bidangKegiatan', 'kecamatan', 'kelurahan', 'pengurus'])
            ->find($id);

        if (!$ormas) {
            abort(404);
        }

        return view('admin.verifikasi.detail', compact('ormas'));
    }

    public function approve($id)
    {
        try {
            $ormas = Ormas::find($id);

            if (!$ormas) {
                return redirect()->route('admin.verifikasi.index')
                    ->with('error', 'Data ORMAS tidak ditemukan.');
            }

            // 🔒 Validasi: hanya boleh approve ORMAS dengan status menunggu/revisi
            if (!in_array($ormas->status, ['menunggu_verifikasi', 'revisi'])) {
                return redirect()->route('admin.verifikasi.index')
                    ->with('error', 'ORMAS ini tidak dapat disetujui karena statusnya sudah ' . $ormas->status_text . '.');
            }

            $ormas->status = 'disetujui';
            $ormas->verified_at = now();
            $ormas->catatan_revisi = null;
            $ormas->save();

            RiwayatPengajuan::create([
                'ormas_id' => $ormas->id,
                'status'   => 'disetujui',
                'catatan'  => 'Pengajuan disetujui',
            ]);

            LogAktivitas::create([
                'user_id'    => Auth::id(),
                'aktivitas'  => 'Verifikasi',
                'deskripsi'  => "Menyetujui ORMAS {$ormas->nama}",
                'ip_address' => request()->ip(),
                'user_agent' => request()->userAgent(),
            ]);

            return redirect()->route('admin.verifikasi.index')
                ->with('success', 'ORMAS berhasil disetujui.');

        } catch (\Exception $e) {
            Log::error('Approve ORMAS error', [
                'message'  => $e->getMessage(),
                'ormas_id' => $id,
            ]);

            return redirect()->route('admin.verifikasi.index')
                ->with('error', 'Terjadi kesalahan saat menyetujui. Silakan coba lagi.');
        }
    }

    public function reject(Request $request, $id)
    {
        try {
            $validated = $request->validate([
                'catatan' => 'required|string|min:5|max:2000',
            ], [
                'catatan.min' => 'Catatan penolakan minimal 5 karakter.',
                'catatan.max' => 'Catatan penolakan maksimal 2000 karakter.',
            ]);

            $ormas = Ormas::find($id);

            if (!$ormas) {
                return redirect()->route('admin.verifikasi.index')
                    ->with('error', 'Data ORMAS tidak ditemukan.');
            }

            if (!in_array($ormas->status, ['menunggu_verifikasi', 'revisi'])) {
                return redirect()->route('admin.verifikasi.index')
                    ->with('error', 'ORMAS ini tidak dapat ditolak karena statusnya sudah ' . $ormas->status_text . '.');
            }

            $ormas->status = 'ditolak';
            $ormas->catatan_revisi = $validated['catatan'];
            $ormas->save();

            RiwayatPengajuan::create([
                'ormas_id' => $ormas->id,
                'status'   => 'ditolak',
                'catatan'  => $validated['catatan'],
            ]);

            LogAktivitas::create([
                'user_id'    => Auth::id(),
                'aktivitas'  => 'Verifikasi',
                'deskripsi'  => "Menolak ORMAS {$ormas->nama}",
                'ip_address' => request()->ip(),
                'user_agent' => request()->userAgent(),
            ]);

            return redirect()->route('admin.verifikasi.index')
                ->with('success', 'ORMAS berhasil ditolak.');

        } catch (\Illuminate\Validation\ValidationException $e) {
            return back()->withErrors($e->validator)->withInput();

        } catch (\Exception $e) {
            Log::error('Reject ORMAS error', [
                'message'  => $e->getMessage(),
                'ormas_id' => $id,
            ]);

            return redirect()->route('admin.verifikasi.index')
                ->with('error', 'Terjadi kesalahan saat menolak. Silakan coba lagi.');
        }
    }

    public function requestRevisi(Request $request, $id)
    {
        try {
            $validated = $request->validate([
                'catatan' => 'required|string|min:5|max:2000',
            ], [
                'catatan.min' => 'Catatan revisi minimal 5 karakter.',
                'catatan.max' => 'Catatan revisi maksimal 2000 karakter.',
            ]);

            $ormas = Ormas::find($id);

            if (!$ormas) {
                return redirect()->route('admin.verifikasi.index')
                    ->with('error', 'Data ORMAS tidak ditemukan.');
            }

            if (!in_array($ormas->status, ['menunggu_verifikasi', 'revisi'])) {
                return redirect()->route('admin.verifikasi.index')
                    ->with('error', 'ORMAS ini tidak dapat direvisi karena statusnya sudah ' . $ormas->status_text . '.');
            }

            $ormas->status = 'revisi';
            $ormas->catatan_revisi = $validated['catatan'];
            $ormas->save();

            RiwayatPengajuan::create([
                'ormas_id' => $ormas->id,
                'status'   => 'revisi',
                'catatan'  => $validated['catatan'],
            ]);

            LogAktivitas::create([
                'user_id'    => Auth::id(),
                'aktivitas'  => 'Verifikasi',
                'deskripsi'  => "Meminta revisi ORMAS {$ormas->nama}",
                'ip_address' => request()->ip(),
                'user_agent' => request()->userAgent(),
            ]);

            return redirect()->route('admin.verifikasi.index')
                ->with('success', 'Revisi berhasil diminta.');

        } catch (\Illuminate\Validation\ValidationException $e) {
            return back()->withErrors($e->validator)->withInput();

        } catch (\Exception $e) {
            Log::error('Request revisi error', [
                'message'  => $e->getMessage(),
                'ormas_id' => $id,
            ]);

            return redirect()->route('admin.verifikasi.index')
                ->with('error', 'Terjadi kesalahan saat meminta revisi. Silakan coba lagi.');
        }
    }
}