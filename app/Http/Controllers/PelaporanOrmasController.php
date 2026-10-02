<?php

namespace App\Http\Controllers;

use App\Models\Ormas;
use App\Models\JenisOrmas;
use App\Models\BidangKegiatan;
use App\Models\Kecamatan;
use App\Models\Kelurahan;
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
            // Form di view harus punya input hidden name="website_url" yang
            // seharusnya KOSONG. Kalau bot mengisi, langsung tolak.
            // ============================================================
            if ($request->filled('website_url')) {
                Log::warning('Honeypot triggered (bot detected)', [
                    'ip'         => $request->ip(),
                    'user_agent' => $request->userAgent(),
                ]);
                // Kembalikan response sukses palsu agar bot tidak tahu
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
                'jenis_ormas_id'                   => 'nullable|string|max:100',
                'bidang_kegiatan_id'               => 'nullable|string|max:255',
                'alamat_kesekretariatan'           => 'nullable|string|max:1000',
                'jumlah_anggota'                   => 'nullable|integer|min:0|max:1000000',
                'jumlah_anggota_perempuan'         => 'nullable|integer|min:0|max:1000000',
                'anggota_perempuan_rentang_16_30'  => 'nullable|integer|min:0|max:1000000',
                'jumlah_anggota_laki_laki'         => 'nullable|integer|min:0|max:1000000',
                'anggota_laki_laki_rentang_16_30'  => 'nullable|integer|min:0|max:1000000',
                'latitude'                         => 'nullable|numeric|between:-90,90',
                'longitude'                        => 'nullable|numeric|between:-180,180',
                'kecamatan_id'                     => 'nullable|string|max:50',
                'kelurahan_id'                     => 'nullable|string|max:50',
                'no_telepon'                       => ['nullable', 'string', 'max:20', 'regex:/^[0-9+\-\s()]+$/'],
                'no_ahu_skt'                       => 'nullable|string|max:100',
                'email'                            => 'nullable|email|max:255',

                // Data Ketua (WAJIB)
                'ketua_nama'       => 'required|string|max:255',
                'ketua_alamat'     => 'nullable|string|max:1000',
                'ketua_no_hp'      => ['required', 'string', 'max:20', 'regex:/^[0-9+\-\s()]+$/'],

                // Data Sekretaris (OPSIONAL)
                'sekretaris_nama'   => 'nullable|string|max:255',
                'sekretaris_alamat' => 'nullable|string|max:1000',
                'sekretaris_no_hp'  => ['nullable', 'string', 'max:20', 'regex:/^[0-9+\-\s()]+$/'],

                // Data Bendahara (OPSIONAL)
                'bendahara_nama'   => 'nullable|string|max:255',
                'bendahara_alamat' => 'nullable|string|max:1000',
                'bendahara_no_hp'  => ['nullable', 'string', 'max:20', 'regex:/^[0-9+\-\s()]+$/'],
            ]);

            DB::beginTransaction();

            // ===== PROSES EMAIL =====
            $email = $validated['email'] ?? null;
            if (empty($email) || trim($email) === '-') {
                $email = null;
            }

            // ===== PROSES KOORDINAT =====
            $latitude  = $validated['latitude'] ?? null;
            $longitude = $validated['longitude'] ?? null;

            if ($latitude === null || $latitude === '' || $latitude === '0' || $latitude === 0) {
                $latitude = null;
            }
            if ($longitude === null || $longitude === '' || $longitude === '0' || $longitude === 0) {
                $longitude = null;
            }

            // Proses Jenis ORMAS
            $jenisOrmasId = null;
            if (!empty($validated['jenis_ormas_id'])) {
                $jenis = JenisOrmas::firstOrCreate(
                    ['nama' => $validated['jenis_ormas_id']],
                    ['nama' => $validated['jenis_ormas_id']]
                );
                $jenisOrmasId = $jenis->id;
            }

            // Proses Bidang Kegiatan
            $bidangKegiatanId = null;
            if (!empty($validated['bidang_kegiatan_id'])) {
                $bidang = BidangKegiatan::firstOrCreate(
                    ['nama' => $validated['bidang_kegiatan_id']],
                    ['nama' => $validated['bidang_kegiatan_id']]
                );
                $bidangKegiatanId = $bidang->id;
            }

            // Proses Kecamatan
            $kecamatanId = null;
            if (!empty($validated['kecamatan_id'])) {
                $kecamatan = Kecamatan::where('nama', $validated['kecamatan_id'])->first();
                if ($kecamatan) {
                    $kecamatanId = $kecamatan->id;
                }
            }

            // Proses Kelurahan
            $kelurahanId = null;
            if (!empty($validated['kelurahan_id']) && $kecamatanId) {
                $kelurahan = Kelurahan::where('nama', $validated['kelurahan_id'])
                    ->where('kecamatan_id', $kecamatanId)
                    ->first();

                if ($kelurahan) {
                    $kelurahanId = $kelurahan->id;
                } else {
                    $kelurahan = Kelurahan::create([
                        'nama'         => $validated['kelurahan_id'],
                        'kecamatan_id' => $kecamatanId
                    ]);
                    $kelurahanId = $kelurahan->id;
                }
            }

            // ============================================================
            // 🔒 BUAT ORMAS — GUNAKAN ASSIGN EKSPLISIT UNTUK FIELD SENSITIF
            // ============================================================
            $ormas = new Ormas();
            $ormas->nama                     = $validated['nama_ormas'];
            $ormas->singkatan                = $validated['singkatan'] ?? null;
            $ormas->jenis_ormas_id           = $jenisOrmasId;
            $ormas->bidang_kegiatan_id       = $bidangKegiatanId;
            $ormas->alamat_kesekretariatan   = $validated['alamat_kesekretariatan'] ?? null;
            $ormas->jumlah_anggota           = $validated['jumlah_anggota'] ?? 0;
            $ormas->jumlah_anggota_perempuan = $validated['jumlah_anggota_perempuan'] ?? 0;
            $ormas->anggota_perempuan_rentang_16_30 = $validated['anggota_perempuan_rentang_16_30'] ?? 0;
            $ormas->jumlah_anggota_laki_laki = $validated['jumlah_anggota_laki_laki'] ?? 0;
            $ormas->anggota_laki_laki_rentang_16_30 = $validated['anggota_laki_laki_rentang_16_30'] ?? 0;
            $ormas->latitude                 = $latitude;
            $ormas->longitude                = $longitude;
            $ormas->kecamatan_id             = $kecamatanId;
            $ormas->kelurahan_id             = $kelurahanId;
            $ormas->no_telepon               = $validated['no_telepon'] ?? null;
            $ormas->nomor_registrasi         = $validated['no_ahu_skt'] ?? null;
            $ormas->email                    = $email;

            // Field di bawah ini WAJIB di-set eksplisit (bukan dari input user)
            $ormas->status    = 'menunggu_verifikasi';
            $ormas->user_id   = null;
            $ormas->is_active = true;

            $ormas->save();

            // Buat data Ketua (WAJIB)
            if (!empty($validated['ketua_nama'])) {
                $ormas->pengurus()->create([
                    'nama'    => $validated['ketua_nama'],
                    'jabatan' => 'Ketua',
                    'alamat'  => $validated['ketua_alamat'] ?? null,
                    'no_hp'   => $validated['ketua_no_hp'],
                ]);
            }

            // Buat data Sekretaris (OPSIONAL)
            if (!empty($validated['sekretaris_nama'])) {
                $ormas->pengurus()->create([
                    'nama'    => $validated['sekretaris_nama'],
                    'jabatan' => 'Sekretaris',
                    'alamat'  => $validated['sekretaris_alamat'] ?? null,
                    'no_hp'   => $validated['sekretaris_no_hp'] ?? null,
                ]);
            }

            // Buat data Bendahara (OPSIONAL)
            if (!empty($validated['bendahara_nama'])) {
                $ormas->pengurus()->create([
                    'nama'    => $validated['bendahara_nama'],
                    'jabatan' => 'Bendahara',
                    'alamat'  => $validated['bendahara_alamat'] ?? null,
                    'no_hp'   => $validated['bendahara_no_hp'] ?? null,
                ]);
            }

            DB::commit();

            return redirect()->route('pelaporan-ormas.create')
                ->with('success', 'Data ORMAS berhasil dikirim! Silakan tunggu proses verifikasi dari admin.');

        } catch (\Illuminate\Validation\ValidationException $e) {
            return back()->withErrors($e->validator)->withInput();

        } catch (\Exception $e) {
            DB::rollBack();

            // ============================================================
            // 🔒 LOG DETAIL ERROR DI SERVER, JANGAN TAMPILKAN KE USER
            // ============================================================
            Log::error('Error saat menyimpan pelaporan ORMAS: ' . $e->getMessage(), [
                'file'       => $e->getFile(),
                'line'       => $e->getLine(),
                'ip'         => $request->ip(),
                'user_agent' => $request->userAgent(),
                // JANGAN log data sensitif seperti password, KTP, dll.
                'input_keys' => array_keys($request->except(['_token', 'password'])),
            ]);

            // Pesan generik untuk user (tanpa detail internal)
            return back()
                ->with('error', 'Terjadi kesalahan saat menyimpan data. Silakan coba beberapa saat lagi atau hubungi admin.')
                ->withInput();
        }
    }
}