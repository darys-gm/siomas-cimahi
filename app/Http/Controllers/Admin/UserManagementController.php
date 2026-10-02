<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Ormas;
use App\Models\Pengurus;
use App\Models\JenisOrmas;
use App\Models\BidangKegiatan;
use App\Models\Kecamatan;
use App\Models\Kelurahan;
use App\Models\LogAktivitas;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;

class UserManagementController extends Controller
{
    public function index(Request $request)
    {
        // 🔒 Validasi filter query
        $validated = $request->validate([
            'search'           => 'nullable|string|max:100',
            'bentuk'           => 'nullable|string|max:100',
            'bidang'           => 'nullable|string|max:255',
            'kecamatan'        => 'nullable|integer|exists:kecamatans,id',
            'kelurahan'        => 'nullable|integer|exists:kelurahans,id',
            'status_aktif'     => 'nullable|in:0,1',
            'status_pelaporan' => 'nullable|in:sudah,belum,tidak_ada',
        ]);

        $search           = $validated['search'] ?? null;
        $bentuk           = $validated['bentuk'] ?? null;
        $bidang           = $validated['bidang'] ?? null;
        $kecamatan        = $validated['kecamatan'] ?? null;
        $kelurahan        = $validated['kelurahan'] ?? null;
        $status_aktif     = $validated['status_aktif'] ?? null;
        $status_pelaporan = $validated['status_pelaporan'] ?? null;

        $query = Ormas::with(['pengurus', 'user', 'jenisOrmas', 'bidangKegiatan', 'kecamatan', 'kelurahan'])
            ->where('status', 'disetujui');

        if ($search) {
            // 🔒 Escape wildcard LIKE
            $searchEscaped = str_replace(['%', '_'], ['\%', '\_'], $search);
            $query->where('nama', 'like', '%' . $searchEscaped . '%');
        }

        if ($kecamatan) {
            $query->where('kecamatan_id', $kecamatan);
        }

        if ($kelurahan) {
            $query->where('kelurahan_id', $kelurahan);
        }

        if ($bentuk) {
            $query->whereHas('jenisOrmas', function ($q) use ($bentuk) {
                $q->where('nama', $bentuk);
            });
        }

        if ($bidang) {
            $query->whereHas('bidangKegiatan', function ($q) use ($bidang) {
                $q->where('nama', $bidang);
            });
        }

        if ($status_aktif !== null && $status_aktif !== '') {
            $query->where('is_active', $status_aktif);
        }

        if ($status_pelaporan) {
            $query->where('pelaporan', $status_pelaporan);
        }

        $ormas = $query->orderBy('nama')->paginate(10);

        $ormas->appends([
            'search'           => $search,
            'bentuk'           => $bentuk,
            'bidang'           => $bidang,
            'kecamatan'        => $kecamatan,
            'kelurahan'        => $kelurahan,
            'status_aktif'     => $status_aktif,
            'status_pelaporan' => $status_pelaporan,
        ]);

        return view('admin.users.index', compact(
            'ormas',
            'search',
            'bentuk',
            'bidang',
            'kecamatan',
            'kelurahan',
            'status_aktif',
            'status_pelaporan'
        ));
    }

    public function bulkUpdatePelaporan(Request $request)
    {
        try {
            $validated = $request->validate([
                'pelaporan' => 'required|in:sudah,belum,tidak_ada',
            ]);

            $pelaporanLabels = [
                'sudah'     => 'Sudah',
                'belum'     => 'Belum',
                'tidak_ada' => 'Tidak Ada',
            ];

            $count = Ormas::where('status', 'disetujui')->update([
                'pelaporan' => $validated['pelaporan'],
            ]);

            LogAktivitas::create([
                'user_id'    => auth()->id(),
                'aktivitas'  => 'Bulk Update Pelaporan',
                'deskripsi'  => "Mengupdate status pelaporan {$count} ORMAS menjadi {$pelaporanLabels[$validated['pelaporan']]}",
                'ip_address' => $request->ip(),
                'user_agent' => $request->userAgent(),
            ]);

            return response()->json([
                'success' => true,
                'message' => "{$count} ORMAS berhasil diupdate pelaporannya menjadi {$pelaporanLabels[$validated['pelaporan']]}",
                'count'   => $count,
            ]);

        } catch (\Illuminate\Validation\ValidationException $e) {
            return response()->json([
                'success' => false,
                'message' => 'Validasi gagal.',
                'errors'  => $e->errors(),
            ], 422);

        } catch (\Exception $e) {
            Log::error('Bulk update pelaporan error', [
                'message' => $e->getMessage(),
                'file'    => $e->getFile(),
                'line'    => $e->getLine(),
            ]);

            return response()->json([
                'success' => false,
                'message' => 'Gagal mengupdate status pelaporan. Silakan coba lagi.',
            ], 500);
        }
    }

    public function bulkUpdateStatus(Request $request)
    {
        try {
            $validated = $request->validate([
                'status' => 'required|in:aktif,nonaktif',
            ]);

            $isActive   = $validated['status'] === 'aktif' ? 1 : 0;
            $statusText = $validated['status'] === 'aktif' ? 'diaktifkan' : 'dinonaktifkan';

            $count = Ormas::where('status', 'disetujui')->update([
                'is_active' => $isActive,
            ]);

            LogAktivitas::create([
                'user_id'    => auth()->id(),
                'aktivitas'  => 'Bulk Update Status',
                'deskripsi'  => "Mengupdate status {$count} ORMAS menjadi {$statusText}",
                'ip_address' => $request->ip(),
                'user_agent' => $request->userAgent(),
            ]);

            return response()->json([
                'success' => true,
                'message' => "{$count} ORMAS berhasil {$statusText}",
                'count'   => $count,
            ]);

        } catch (\Illuminate\Validation\ValidationException $e) {
            return response()->json([
                'success' => false,
                'message' => 'Validasi gagal.',
                'errors'  => $e->errors(),
            ], 422);

        } catch (\Exception $e) {
            Log::error('Bulk update status error', [
                'message' => $e->getMessage(),
                'file'    => $e->getFile(),
                'line'    => $e->getLine(),
            ]);

            return response()->json([
                'success' => false,
                'message' => 'Gagal mengupdate status. Silakan coba lagi.',
            ], 500);
        }
    }

    public function show($id)
    {
        // 🔒 Validasi ID
        if (!is_numeric($id) || (int) $id < 1) {
            return response()->json([
                'success' => false,
                'message' => 'Data tidak ditemukan.',
            ], 404);
        }

        $ormas = Ormas::with(['pengurus', 'user', 'jenisOrmas', 'bidangKegiatan', 'kecamatan', 'kelurahan'])
            ->find($id);

        if (!$ormas) {
            return response()->json([
                'success' => false,
                'message' => 'Data tidak ditemukan.',
            ], 404);
        }

        return response()->json([
            'success' => true,
            'data'    => $ormas,
        ]);
    }

    public function editOrmas($id)
    {
        if (!is_numeric($id) || (int) $id < 1) {
            return response()->json(['message' => 'Data tidak ditemukan.'], 404);
        }

        $ormas = Ormas::with(['jenisOrmas', 'bidangKegiatan', 'kecamatan', 'kelurahan'])
            ->find($id);

        if (!$ormas) {
            return response()->json(['message' => 'Data tidak ditemukan.'], 404);
        }

        return response()->json($ormas);
    }

    public function updateOrmas(Request $request, $id)
    {
        try {
            $ormas = Ormas::findOrFail($id);

            $validated = $request->validate([
                'nama'                             => 'required|string|max:255',
                'singkatan'                        => 'nullable|string|max:50',
                'nomor_registrasi'                 => 'nullable|string|max:100',
                'jenis_ormas_id'                   => 'nullable|string|max:255',
                'bidang_kegiatan_id'               => 'nullable|string|max:255',
                'alamat_kesekretariatan'           => 'nullable|string|max:2000',
                'jumlah_anggota'                   => 'nullable|integer|min:0|max:1000000',
                'jumlah_anggota_perempuan'         => 'nullable|integer|min:0|max:1000000',
                'anggota_perempuan_rentang_16_30'  => 'nullable|integer|min:0|max:1000000',
                'jumlah_anggota_laki_laki'         => 'nullable|integer|min:0|max:1000000',
                'anggota_laki_laki_rentang_16_30'  => 'nullable|integer|min:0|max:1000000',
                'latitude'                         => 'nullable|numeric|between:-90,90',
                'longitude'                        => 'nullable|numeric|between:-180,180',
                'kecamatan_id'                     => 'nullable|exists:kecamatans,id',
                'kelurahan_id'                     => 'nullable|string|max:255',
                'no_telepon'                       => ['nullable', 'string', 'max:20', 'regex:/^[0-9+\-\s()]+$/'],
                'email'                            => 'nullable|email|max:255',
                'pelaporan'                        => 'nullable|in:sudah,belum,tidak_ada',
            ]);

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

            // Proses Kelurahan
            $kelurahanId = null;
            if (!empty($validated['kelurahan_id'])) {
                $kelurahan = Kelurahan::find($validated['kelurahan_id']);
                if ($kelurahan) {
                    $kelurahanId = $kelurahan->id;
                }
            }

            $ormas->update([
                'nama'                             => $validated['nama'],
                'singkatan'                        => $validated['singkatan'] ?? null,
                'nomor_registrasi'                 => $validated['nomor_registrasi'] ?? null,
                'jenis_ormas_id'                   => $jenisOrmasId,
                'bidang_kegiatan_id'               => $bidangKegiatanId,
                'alamat_kesekretariatan'           => $validated['alamat_kesekretariatan'] ?? null,
                'jumlah_anggota'                   => $validated['jumlah_anggota'] ?? 0,
                'jumlah_anggota_perempuan'         => $validated['jumlah_anggota_perempuan'] ?? 0,
                'anggota_perempuan_rentang_16_30'  => $validated['anggota_perempuan_rentang_16_30'] ?? 0,
                'jumlah_anggota_laki_laki'         => $validated['jumlah_anggota_laki_laki'] ?? 0,
                'anggota_laki_laki_rentang_16_30'  => $validated['anggota_laki_laki_rentang_16_30'] ?? 0,
                'latitude'                         => $validated['latitude'] ?? null,
                'longitude'                        => $validated['longitude'] ?? null,
                'kecamatan_id'                     => $validated['kecamatan_id'] ?? null,
                'kelurahan_id'                     => $kelurahanId,
                'no_telepon'                       => $validated['no_telepon'] ?? null,
                'email'                            => $validated['email'] ?? null,
                'pelaporan'                        => $validated['pelaporan'] ?? 'belum',
            ]);

            LogAktivitas::create([
                'user_id'    => auth()->id(),
                'aktivitas'  => 'Update ORMAS',
                'deskripsi'  => "Mengupdate data ORMAS {$ormas->nama}",
                'ip_address' => $request->ip(),
                'user_agent' => $request->userAgent(),
            ]);

            return response()->json([
                'success' => true,
                'message' => 'Data ORMAS berhasil diupdate.',
                'data'    => $ormas->fresh(),
            ]);

        } catch (\Illuminate\Validation\ValidationException $e) {
            return response()->json([
                'success' => false,
                'message' => 'Validasi gagal.',
                'errors'  => $e->errors(),
            ], 422);

        } catch (\Exception $e) {
            Log::error('Update ORMAS error', [
                'message' => $e->getMessage(),
                'file'    => $e->getFile(),
                'line'    => $e->getLine(),
                'ormas_id' => $id,
            ]);

            return response()->json([
                'success' => false,
                'message' => 'Gagal mengupdate data. Silakan coba lagi.',
            ], 500);
        }
    }

    public function updatePelaporan(Request $request, $id)
    {
        try {
            $ormas = Ormas::findOrFail($id);

            $validated = $request->validate([
                'pelaporan' => 'required|in:sudah,belum,tidak_ada',
            ]);

            $ormas->pelaporan = $validated['pelaporan'];
            $ormas->save();

            LogAktivitas::create([
                'user_id'    => auth()->id(),
                'aktivitas'  => 'Update Pelaporan ORMAS',
                'deskripsi'  => "Mengupdate status pelaporan ORMAS {$ormas->nama} menjadi {$ormas->pelaporan_text}",
                'ip_address' => $request->ip(),
                'user_agent' => $request->userAgent(),
            ]);

            return response()->json([
                'success' => true,
                'message' => 'Status pelaporan berhasil diupdate.',
                'data'    => [
                    'pelaporan'       => $ormas->pelaporan,
                    'pelaporan_text'  => $ormas->pelaporan_text,
                    'pelaporan_badge' => $ormas->pelaporan_badge,
                ],
            ]);

        } catch (\Illuminate\Validation\ValidationException $e) {
            return response()->json([
                'success' => false,
                'message' => 'Validasi gagal.',
                'errors'  => $e->errors(),
            ], 422);

        } catch (\Exception $e) {
            Log::error('Update pelaporan error', [
                'message'  => $e->getMessage(),
                'ormas_id' => $id,
            ]);

            return response()->json([
                'success' => false,
                'message' => 'Gagal mengupdate status pelaporan.',
            ], 500);
        }
    }

    public function editPengurus($id)
    {
        if (!is_numeric($id) || (int) $id < 1) {
            return response()->json(['message' => 'Data tidak ditemukan.'], 404);
        }

        $pengurus = Pengurus::find($id);

        if (!$pengurus) {
            return response()->json(['message' => 'Data tidak ditemukan.'], 404);
        }

        return response()->json($pengurus);
    }

    public function updatePengurus(Request $request, $id)
    {
        try {
            $pengurus = Pengurus::findOrFail($id);

            $validated = $request->validate([
                'nama'   => 'required|string|max:255',
                'alamat' => 'nullable|string|max:1000',
                'no_hp'  => ['nullable', 'string', 'max:20', 'regex:/^[0-9+\-\s()]+$/'],
            ]);

            $pengurus->update($validated);

            LogAktivitas::create([
                'user_id'    => auth()->id(),
                'aktivitas'  => 'Update Pengurus',
                'deskripsi'  => "Mengupdate data pengurus {$pengurus->nama}",
                'ip_address' => $request->ip(),
                'user_agent' => $request->userAgent(),
            ]);

            return response()->json([
                'success' => true,
                'message' => 'Data pengurus berhasil diupdate.',
                'data'    => $pengurus,
            ]);

        } catch (\Illuminate\Validation\ValidationException $e) {
            return response()->json([
                'success' => false,
                'message' => 'Validasi gagal.',
                'errors'  => $e->errors(),
            ], 422);

        } catch (\Exception $e) {
            Log::error('Update pengurus error', [
                'message'     => $e->getMessage(),
                'pengurus_id' => $id,
            ]);

            return response()->json([
                'success' => false,
                'message' => 'Gagal mengupdate data pengurus.',
            ], 500);
        }
    }

    public function tambahPengurus(Request $request)
    {
        try {
            $validated = $request->validate([
                'ormas_id' => 'required|exists:ormas,id',
                'nama'     => 'required|string|max:255',
                'alamat'   => 'nullable|string|max:1000',
                'no_hp'    => ['nullable', 'string', 'max:20', 'regex:/^[0-9+\-\s()]+$/'],
            ]);

            $pengurus = Pengurus::create([
                'ormas_id' => $validated['ormas_id'],
                'nama'     => $validated['nama'],
                'alamat'   => $validated['alamat'] ?? null,
                'no_hp'    => $validated['no_hp'] ?? null,
            ]);

            LogAktivitas::create([
                'user_id'    => auth()->id(),
                'aktivitas'  => 'Tambah Pengurus',
                'deskripsi'  => "Menambahkan pengurus {$pengurus->nama}",
                'ip_address' => $request->ip(),
                'user_agent' => $request->userAgent(),
            ]);

            return response()->json([
                'success' => true,
                'message' => 'Pengurus berhasil ditambahkan.',
                'data'    => $pengurus,
            ]);

        } catch (\Illuminate\Validation\ValidationException $e) {
            return response()->json([
                'success' => false,
                'message' => 'Validasi gagal.',
                'errors'  => $e->errors(),
            ], 422);

        } catch (\Exception $e) {
            Log::error('Tambah pengurus error', [
                'message' => $e->getMessage(),
            ]);

            return response()->json([
                'success' => false,
                'message' => 'Gagal menambahkan pengurus.',
            ], 500);
        }
    }

    /**
     * ============================================================
     * HAPUS PENGURUS (METHOD BARU)
     * ============================================================
     */
    public function destroyPengurus($id)
    {
        try {
            // 🔒 Validasi ID
            if (!is_numeric($id) || (int) $id < 1) {
                return response()->json([
                    'success' => false,
                    'message' => 'ID pengurus tidak valid.',
                ], 400);
            }

            $pengurus = Pengurus::find((int) $id);

            if (!$pengurus) {
                return response()->json([
                    'success' => false,
                    'message' => 'Pengurus tidak ditemukan.',
                ], 404);
            }

            $nama    = $pengurus->nama;
            $ormasId = $pengurus->ormas_id;

            $pengurus->delete();

            // Log aktivitas
            LogAktivitas::create([
                'user_id'    => auth()->id(),
                'aktivitas'  => 'Hapus Pengurus',
                'deskripsi'  => "Menghapus pengurus '{$nama}' dari ORMAS ID {$ormasId}",
                'ip_address' => request()->ip(),
                'user_agent' => request()->userAgent(),
            ]);

            return response()->json([
                'success' => true,
                'message' => "Pengurus '{$nama}' berhasil dihapus.",
            ]);

        } catch (\Exception $e) {
            Log::error('Hapus pengurus error', [
                'message'     => $e->getMessage(),
                'file'        => $e->getFile(),
                'line'        => $e->getLine(),
                'pengurus_id' => $id,
            ]);

            return response()->json([
                'success' => false,
                'message' => 'Gagal menghapus pengurus. Silakan coba lagi.',
            ], 500);
        }
    }

    public function destroyOrmas($id)
    {
        try {
            $ormas = Ormas::findOrFail($id);
            $namaOrmas      = $ormas->nama;
            $pengurusCount  = $ormas->pengurus()->count();

            $ormas->pengurus()->delete();
            $ormas->delete();

            LogAktivitas::create([
                'user_id'    => auth()->id(),
                'aktivitas'  => 'Hapus ORMAS',
                'deskripsi'  => "Menghapus ORMAS {$namaOrmas} beserta {$pengurusCount} pengurus",
                'ip_address' => request()->ip(),
                'user_agent' => request()->userAgent(),
            ]);

            return response()->json([
                'success' => true,
                'message' => "ORMAS '{$namaOrmas}' berhasil dihapus!",
            ]);

        } catch (\Exception $e) {
            Log::error('Hapus ORMAS error', [
                'message'  => $e->getMessage(),
                'ormas_id' => $id,
            ]);

            return response()->json([
                'success' => false,
                'message' => 'Terjadi kesalahan saat menghapus data.',
            ], 500);
        }
    }

    public function toggleActive($id)
    {
        try {
            $ormas = Ormas::findOrFail($id);

            $ormas->is_active = !$ormas->is_active;
            $ormas->save();

            $status = $ormas->is_active ? 'diaktifkan' : 'dinonaktifkan';

            LogAktivitas::create([
                'user_id'    => auth()->id(),
                'aktivitas'  => 'Toggle Status ORMAS',
                'deskripsi'  => "ORMAS {$ormas->nama} {$status} oleh admin",
                'ip_address' => request()->ip(),
                'user_agent' => request()->userAgent(),
            ]);

            return response()->json([
                'success'   => true,
                'is_active' => $ormas->is_active,
                'message'   => "ORMAS berhasil {$status}",
            ]);

        } catch (\Exception $e) {
            Log::error('Toggle ORMAS error', [
                'message'  => $e->getMessage(),
                'ormas_id' => $id,
            ]);

            return response()->json([
                'success' => false,
                'message' => 'Terjadi kesalahan. Silakan coba lagi.',
            ], 500);
        }
    }
}