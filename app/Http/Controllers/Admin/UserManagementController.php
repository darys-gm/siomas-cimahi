<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Ormas;
use App\Models\Pengurus;
use App\Models\JenisOrmas;
use App\Models\BidangKegiatan;
use App\Models\Kelurahan;
use App\Models\LogAktivitas;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Illuminate\Validation\ValidationException;

class UserManagementController extends Controller
{
    // ============================================================
    // INDEX — Daftar ORMAS dengan Filter
    // ============================================================
    public function index(Request $request)
    {
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

        $query = Ormas::with([
            'pengurus',
            'user',
            'jenisOrmas',
            'bidangKegiatan',
            'kecamatan',
            'kelurahan',
        ])->where('status', 'disetujui');

        if ($search) {
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

    // ============================================================
    // BULK UPDATE PELAPORAN
    // ============================================================
    public function bulkUpdatePelaporan(Request $request): JsonResponse
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

            $count = Ormas::where('status', 'disetujui')
                ->update(['pelaporan' => $validated['pelaporan']]);

            $this->logAktivitas(
                $request,
                'Bulk Update Pelaporan',
                "Mengupdate status pelaporan {$count} ORMAS menjadi {$pelaporanLabels[$validated['pelaporan']]}"
            );

            return response()->json([
                'success' => true,
                'message' => "{$count} ORMAS berhasil diupdate pelaporannya menjadi {$pelaporanLabels[$validated['pelaporan']]}",
                'count'   => $count,
            ]);

        } catch (ValidationException $e) {
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

    // ============================================================
    // BULK UPDATE STATUS AKTIF/NONAKTIF
    // ============================================================
    public function bulkUpdateStatus(Request $request): JsonResponse
    {
        try {
            $validated = $request->validate([
                'status' => 'required|in:aktif,nonaktif',
            ]);

            $isActive   = $validated['status'] === 'aktif' ? 1 : 0;
            $statusText = $validated['status'] === 'aktif' ? 'diaktifkan' : 'dinonaktifkan';

            $count = Ormas::where('status', 'disetujui')
                ->update(['is_active' => $isActive]);

            $this->logAktivitas(
                $request,
                'Bulk Update Status',
                "Mengupdate status {$count} ORMAS menjadi {$statusText}"
            );

            return response()->json([
                'success' => true,
                'message' => "{$count} ORMAS berhasil {$statusText}",
                'count'   => $count,
            ]);

        } catch (ValidationException $e) {
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

    // ============================================================
    // SHOW — Detail ORMAS via AJAX
    // ============================================================
    public function show($id): JsonResponse
    {
        if (!$this->isValidId($id)) {
            return response()->json([
                'success' => false,
                'message' => 'Data tidak ditemukan.',
            ], 404);
        }

        $ormas = Ormas::with([
            'pengurus',
            'user',
            'jenisOrmas',
            'bidangKegiatan',
            'kecamatan',
            'kelurahan',
        ])->find((int) $id);

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

    // ============================================================
    // EDIT ORMAS — Ambil data untuk form edit
    // ============================================================
    public function editOrmas($id): JsonResponse
    {
        if (!$this->isValidId($id)) {
            return response()->json(['message' => 'Data tidak ditemukan.'], 404);
        }

        $ormas = Ormas::with(['jenisOrmas', 'bidangKegiatan', 'kecamatan', 'kelurahan'])
            ->find((int) $id);

        if (!$ormas) {
            return response()->json(['message' => 'Data tidak ditemukan.'], 404);
        }

        return response()->json($ormas);
    }

    // ============================================================
    // UPDATE ORMAS
    // ============================================================
    public function updateOrmas(Request $request, $id): JsonResponse
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

            $jenisOrmasId     = $this->resolveJenisOrmasId($validated['jenis_ormas_id'] ?? null);
            $bidangKegiatanId = $this->resolveBidangKegiatanId($validated['bidang_kegiatan_id'] ?? null);
            $kelurahanId      = $this->resolveKelurahanId($validated['kelurahan_id'] ?? null);

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

            $this->logAktivitas($request, 'Update ORMAS', "Mengupdate data ORMAS {$ormas->nama}");

            return response()->json([
                'success' => true,
                'message' => 'Data ORMAS berhasil diupdate.',
                'data'    => $ormas,
            ]);

        } catch (ValidationException $e) {
            return response()->json([
                'success' => false,
                'message' => 'Validasi gagal.',
                'errors'  => $e->errors(),
            ], 422);

        } catch (\Exception $e) {
            Log::error('Update ORMAS error', [
                'message'  => $e->getMessage(),
                'file'     => $e->getFile(),
                'line'     => $e->getLine(),
                'ormas_id' => $id,
            ]);

            return response()->json([
                'success' => false,
                'message' => 'Gagal mengupdate data. Silakan coba lagi.',
            ], 500);
        }
    }

    // ============================================================
    // UPDATE PELAPORAN
    // ============================================================
    public function updatePelaporan(Request $request, $id): JsonResponse
    {
        try {
            $ormas = Ormas::findOrFail($id);

            $validated = $request->validate([
                'pelaporan' => 'required|in:sudah,belum,tidak_ada',
            ]);

            $ormas->pelaporan = $validated['pelaporan'];
            $ormas->save();

            $this->logAktivitas(
                $request,
                'Update Pelaporan ORMAS',
                "Mengupdate status pelaporan ORMAS {$ormas->nama} menjadi {$ormas->pelaporan_text}"
            );

            return response()->json([
                'success' => true,
                'message' => 'Status pelaporan berhasil diupdate.',
                'data'    => [
                    'pelaporan'       => $ormas->pelaporan,
                    'pelaporan_text'  => $ormas->pelaporan_text,
                    'pelaporan_badge' => $ormas->pelaporan_badge,
                ],
            ]);

        } catch (ValidationException $e) {
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

    // ============================================================
    // EDIT PENGURUS
    // ============================================================
    public function editPengurus($id): JsonResponse
    {
        if (!$this->isValidId($id)) {
            return response()->json(['message' => 'Data tidak ditemukan.'], 404);
        }

        $pengurus = Pengurus::find((int) $id);

        if (!$pengurus) {
            return response()->json(['message' => 'Data tidak ditemukan.'], 404);
        }

        return response()->json($pengurus);
    }

    // ============================================================
    // UPDATE PENGURUS
    // ============================================================
    public function updatePengurus(Request $request, $id): JsonResponse
    {
        try {
            $pengurus = Pengurus::findOrFail($id);

            $validated = $request->validate([
                'nama'   => 'required|string|max:255',
                'alamat' => 'nullable|string|max:1000',
                'no_hp'  => ['nullable', 'string', 'max:20', 'regex:/^[0-9+\-\s()]+$/'],
            ]);

            $pengurus->update($validated);

            $this->logAktivitas($request, 'Update Pengurus', "Mengupdate data pengurus {$pengurus->nama}");

            return response()->json([
                'success' => true,
                'message' => 'Data pengurus berhasil diupdate.',
                'data'    => $pengurus,
            ]);

        } catch (ValidationException $e) {
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

    // ============================================================
    // TAMBAH PENGURUS
    // ============================================================
    public function tambahPengurus(Request $request): JsonResponse
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

            $this->logAktivitas($request, 'Tambah Pengurus', "Menambahkan pengurus {$pengurus->nama}");

            return response()->json([
                'success' => true,
                'message' => 'Pengurus berhasil ditambahkan.',
                'data'    => $pengurus,
            ]);

        } catch (ValidationException $e) {
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

    // ============================================================
    // HAPUS PENGURUS
    // ============================================================
    public function destroyPengurus($id): JsonResponse
    {
        try {
            if (!$this->isValidId($id)) {
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

            $this->logAktivitas(
                request(),
                'Hapus Pengurus',
                "Menghapus pengurus '{$nama}' dari ORMAS ID {$ormasId}"
            );

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

    // ============================================================
    // HAPUS ORMAS (beserta pengurus)
    // ============================================================
    public function destroyOrmas($id): JsonResponse
    {
        try {
            $ormas = Ormas::findOrFail($id);
            $namaOrmas     = $ormas->nama;
            $pengurusCount = $ormas->pengurus()->count();

            $ormas->pengurus()->delete();
            $ormas->delete();

            $this->logAktivitas(
                request(),
                'Hapus ORMAS',
                "Menghapus ORMAS {$namaOrmas} beserta {$pengurusCount} pengurus"
            );

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

    // ============================================================
    // TOGGLE ACTIVE/NONACTIVE
    // ============================================================
    public function toggleActive($id): JsonResponse
    {
        try {
            $ormas = Ormas::findOrFail($id);

            $ormas->is_active = !$ormas->is_active;
            $ormas->save();

            $status = $ormas->is_active ? 'diaktifkan' : 'dinonaktifkan';

            $this->logAktivitas(
                request(),
                'Toggle Status ORMAS',
                "ORMAS {$ormas->nama} {$status} oleh admin"
            );

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

    // ============================================================
    // PRIVATE HELPERS
    // ============================================================

    /**
     * Validasi format ID (angka positif).
     */
    private function isValidId($id): bool
    {
        return is_numeric($id) && (int) $id > 0;
    }

    /**
     * Helper log aktivitas — supaya tidak duplikasi di setiap method.
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

    /**
     * Resolve Jenis ORMAS (firstOrCreate).
     */
    private function resolveJenisOrmasId(?string $nama): ?int
    {
        if (empty($nama)) {
            return null;
        }

        return JenisOrmas::firstOrCreate(['nama' => $nama], ['nama' => $nama])->id;
    }

    /**
     * Resolve Bidang Kegiatan (firstOrCreate).
     */
    private function resolveBidangKegiatanId(?string $nama): ?int
    {
        if (empty($nama)) {
            return null;
        }

        return BidangKegiatan::firstOrCreate(['nama' => $nama], ['nama' => $nama])->id;
    }

    /**
     * Resolve Kelurahan ID (find).
     */
    private function resolveKelurahanId(?string $id): ?int
    {
        if (empty($id)) {
            return null;
        }

        return Kelurahan::find($id)?->id;
    }
}