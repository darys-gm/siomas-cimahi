<?php

namespace App\Http\Controllers;

use App\Models\Ormas;
use App\Models\JenisOrmas;
use App\Models\BidangKegiatan;
use App\Models\Kecamatan;
use App\Models\Kelurahan;
use App\Models\Pengurus;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\DB;

class PelaporanOrmasController extends Controller
{
    public function create()
    {
        $jenisOrmas     = JenisOrmas::all();
        $bidangKegiatan = BidangKegiatan::all();
        $kecamatan      = Kecamatan::all();
        $kelurahan      = Kelurahan::all();

        return view('pelaporan-ormas', compact('jenisOrmas', 'bidangKegiatan', 'kecamatan', 'kelurahan'));
    }

    public function store(Request $request)
    {
        try {
            // ============================================================
            // 🔒 ANTI-BOT: Honeypot check
            // ============================================================
            if ($request->filled('website_url')) {
                Log::warning('Honeypot triggered (bot detected)', [
                    'ip'         => $request->ip(),
                    'user_agent' => $request->userAgent(),
                ]);
                return redirect()->route('pelaporan-ormas.create')
                    ->with('success', 'Data ORMAS berhasil dikirim! Silakan tunggu proses verifikasi dari admin.');
            }

            // ============================================================
            // 🔒 VALIDASI INPUT
            // ============================================================
            $validated = $request->validate([
                // Data ORMAS
                'nama_ormas'                       => 'required|string|max:255',
                'singkatan'                        => 'nullable|string|max:50',
                'jenis_ormas_id'                   => 'required|integer|exists:jenis_ormas,id',
                'bidang_kegiatan_id'               => 'required|integer|exists:bidang_kegiatan,id',
                'alamat_kesekretariatan'           => 'nullable|string|max:1000',
                'jumlah_anggota'                   => 'nullable|integer|min:0|max:1000000',
                'jumlah_anggota_perempuan'         => 'nullable|integer|min:0|max:1000000',
                'anggota_perempuan_rentang_16_30'  => 'nullable|integer|min:0|max:1000000',
                'jumlah_anggota_laki_laki'         => 'nullable|integer|min:0|max:1000000',
                'anggota_laki_laki_rentang_16_30'  => 'nullable|integer|min:0|max:1000000',
                'no_telepon'                       => 'required|string|max:20',
                'email'                            => 'nullable|string|max:100',
                'kecamatan_id'                     => 'required|string|max:100',
                'kelurahan_id'                     => 'required|string|max:100',
                'latitude'                         => 'required|string',
                'longitude'                        => 'required|string',

                // Data Ketua
                'ketua_nama'                       => 'required|string|max:255',
                'ketua_alamat'                     => 'nullable|string|max:255',
                'ketua_no_hp'                      => 'required|string|max:20',

                // Data Sekretaris
                'sekretaris_nama'                  => 'nullable|string|max:255',
                'sekretaris_alamat'                => 'nullable|string|max:255',
                'sekretaris_no_hp'                 => 'nullable|string|max:20',

                // Data Bendahara
                'bendahara_nama'                   => 'nullable|string|max:255',
                'bendahara_alamat'                 => 'nullable|string|max:255',
                'bendahara_no_hp'                  => 'nullable|string|max:20',
            ]);

            // ============================================================
            // 🔒 SIMPAN KE DATABASE
            // ============================================================
            DB::beginTransaction();

            $ormas = Ormas::create([
                'user_id'                          => null, // Karena ini pelaporan publik
                'nama'                             => $validated['nama_ormas'],
                'singkatan'                        => $validated['singkatan'],
                'jenis_ormas_id'                   => $validated['jenis_ormas_id'],
                'bidang_kegiatan_id'               => $validated['bidang_kegiatan_id'],
                'alamat_kesekretariatan'           => $validated['alamat_kesekretariatan'],
                'kecamatan_id'                     => $validated['kecamatan_id'],
                'kelurahan_id'                     => $validated['kelurahan_id'],
                'no_telepon'                       => $validated['no_telepon'],
                'email'                            => $validated['email'],
                'latitude'                         => $validated['latitude'],
                'longitude'                        => $validated['longitude'],
                'jumlah_anggota'                   => $validated['jumlah_anggota'] ?? 0,
                'jumlah_anggota_perempuan'         => $validated['jumlah_anggota_perempuan'] ?? 0,
                'anggota_perempuan_rentang_16_30'  => $validated['anggota_perempuan_rentang_16_30'] ?? 0,
                'jumlah_anggota_laki_laki'         => $validated['jumlah_anggota_laki_laki'] ?? 0,
                'anggota_laki_laki_rentang_16_30'  => $validated['anggota_laki_laki_rentang_16_30'] ?? 0,
                'status'                           => 'menunggu_verifikasi',
                'pelaporan'                        => 'sudah',
                'is_active'                        => true,
            ]);

            // Simpan Pengurus (Ketua)
            Pengurus::create([
                'ormas_id' => $ormas->id,
                'nama'     => $validated['ketua_nama'],
                'jabatan'  => 'Ketua',
                'alamat'   => $validated['ketua_alamat'],
                'no_hp'    => $validated['ketua_no_hp'],
            ]);

            // Simpan Sekretaris (Jika diisi)
            if (!empty($validated['sekretaris_nama'])) {
                Pengurus::create([
                    'ormas_id' => $ormas->id,
                    'nama'     => $validated['sekretaris_nama'],
                    'jabatan'  => 'Sekretaris',
                    'alamat'   => $validated['sekretaris_alamat'],
                    'no_hp'    => $validated['sekretaris_no_hp'],
                ]);
            }

            // Simpan Bendahara (Jika diisi)
            if (!empty($validated['bendahara_nama'])) {
                Pengurus::create([
                    'ormas_id' => $ormas->id,
                    'nama'     => $validated['bendahara_nama'],
                    'jabatan'  => 'Bendahara',
                    'alamat'   => $validated['bendahara_alamat'],
                    'no_hp'    => $validated['bendahara_no_hp'],
                ]);
            }

            DB::commit();

            return redirect()->route('pelaporan-ormas.create')
                ->with('success', 'Data ORMAS berhasil dikirim! Silakan tunggu proses verifikasi dari admin.');

        } catch (\Illuminate\Validation\ValidationException $e) {
            DB::rollBack();
            return back()->withErrors($e->validator)->withInput();
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Store pelaporan ormas error', [
                'message' => $e->getMessage(),
                'file'    => $e->getFile(),
                'line'    => $e->getLine(),
            ]);
            return back()->with('error', 'Terjadi kesalahan sistem. Silakan coba lagi.')->withInput();
        }
    }
}