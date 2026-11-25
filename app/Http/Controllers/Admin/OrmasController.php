<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Ormas;
use App\Models\Kecamatan;
use App\Models\Kelurahan;
use App\Models\JenisOrmas;
use App\Models\BidangKegiatan;
use App\Models\User;
use App\Models\LogAktivitas;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Validation\ValidationException;

class OrmasController extends Controller
{
    // ============================================================
    // KONSTANTA STATUS
    // ============================================================
    private const STATUSES = [
        'draft',
        'menunggu_verifikasi',
        'revisi',
        'disetujui',
        'ditolak',
    ];

    // ============================================================
    // INDEX — Daftar ORMAS dengan Filter
    // ============================================================
    public function index(Request $request)
    {
        $validated = $request->validate([
            'search'    => 'nullable|string|max:100',
            'bentuk'    => 'nullable|string|max:100',
            'bidang'    => 'nullable|string|max:255',
            'kecamatan' => 'nullable|integer|exists:kecamatan,id',
            'kelurahan' => 'nullable|integer|exists:kelurahan,id',
            'status'    => 'nullable|in:' . implode(',', self::STATUSES),
        ]);

        $search    = $validated['search'] ?? null;
        $bentuk    = $validated['bentuk'] ?? null;
        $bidang    = $validated['bidang'] ?? null;
        $kecamatan = $validated['kecamatan'] ?? null;
        $kelurahan = $validated['kelurahan'] ?? null;
        $status    = $validated['status'] ?? null;

        $query = Ormas::with(['jenisOrmas', 'bidangKegiatan', 'kecamatan', 'kelurahan', 'pengurus', 'user']);

        if ($search) {
            $searchEscaped = str_replace(['%', '_'], ['\%', '\_'], $search);
            $query->where('nama', 'like', '%' . $searchEscaped . '%');
        }

        if ($status)    $query->where('status', $status);
        if ($kecamatan) $query->where('kecamatan_id', $kecamatan);
        if ($kelurahan) $query->where('kelurahan_id', $kelurahan);

        if ($bentuk) {
            $query->whereHas('jenisOrmas', fn($q) => $q->where('nama', $bentuk));
        }

        if ($bidang) {
            $query->whereHas('bidangKegiatan', fn($q) => $q->where('nama', $bidang));
        }

        $ormas = $query->orderBy('created_at', 'desc')
            ->paginate(10)
            ->withQueryString();

        // Counter status
        $counters = $this->getStatusCounters();

        return view('admin.ormas.index', array_merge(
            compact('ormas', 'search', 'bentuk', 'bidang', 'kecamatan', 'kelurahan', 'status'),
            [
                'totalMenunggu'  => $counters['menunggu_verifikasi'],
                'totalRevisi'    => $counters['revisi'],
                'totalDitolak'   => $counters['ditolak'],
                'totalDisetujui' => $counters['disetujui'],
            ]
        ));
    }

    // ============================================================
    // CREATE — Form Tambah ORMAS
    // ============================================================
    public function create()
    {
        $jenisOrmas     = JenisOrmas::all();
        $bidangKegiatan = BidangKegiatan::all();
        $kecamatan      = Kecamatan::all();
        $kelurahan      = Kelurahan::all();
        $users          = User::where('role', 'user')->get();

        return view('admin.ormas.create', compact(
            'jenisOrmas', 'bidangKegiatan', 'kecamatan', 'kelurahan', 'users'
        ));
    }

    // ============================================================
    // STORE — Simpan ORMAS Baru
    // ============================================================
    public function store(Request $request)
    {
        try {
            // 1. VALIDASI
            // Pastikan jenis_ormas_id dan bidang_kegiatan_id divalidasi sebagai INTEGER (ID)
            $validated = $request->validate([
                'nama'                             => 'required|string|max:255',
                'singkatan'                        => 'nullable|string|max:50',
                'nomor_registrasi'                 => 'nullable|string|max:100',
                'jenis_ormas_id'                   => 'required|integer|exists:jenis_ormas,id', // Wajib ID
                'bidang_kegiatan_id'               => 'required|integer|exists:bidang_kegiatan,id', // Wajib ID
                'alamat_kesekretariatan'           => 'nullable|string|max:2000',
                'jumlah_anggota'                   => 'nullable|integer|min:0|max:1000000',
                'jumlah_anggota_perempuan'         => 'nullable|integer|min:0|max:1000000',
                'anggota_perempuan_rentang_16_30'  => 'nullable|integer|min:0|max:1000000',
                'jumlah_anggota_laki_laki'         => 'nullable|integer|min:0|max:1000000',
                'anggota_laki_laki_rentang_16_30'  => 'nullable|integer|min:0|max:1000000',
                'latitude'                         => 'nullable|numeric|between:-90,90',
                'longitude'                        => 'nullable|numeric|between:-180,180',
                'kecamatan_id'                     => 'nullable|integer|exists:kecamatan,id',
                'kelurahan_id'                     => 'nullable|integer|exists:kelurahan,id',
                'no_telepon'                       => ['nullable', 'string', 'max:20', 'regex:/^[0-9+\-\s()]+$/'],
                'email'                            => 'nullable|email|max:255',
                'status'                           => 'nullable|in:' . implode(',', self::STATUSES),
                'user_id'                          => 'nullable|integer|exists:users,id',
                'ketua_nama'                       => 'required|string|max:255',
                'ketua_alamat'                     => 'nullable|string|max:1000',
                'ketua_no_hp'                      => ['nullable', 'string', 'max:20', 'regex:/^[0-9+\-\s()]+$/'],
                'sekretaris_nama'                  => 'nullable|string|max:255',
                'sekretaris_alamat'                => 'nullable|string|max:1000',
                'sekretaris_no_hp'                 => ['nullable', 'string', 'max:20', 'regex:/^[0-9+\-\s()]+$/'],
                'bendahara_nama'                   => 'nullable|string|max:255',
                'bendahara_alamat'                 => 'nullable|string|max:1000',
                'bendahara_no_hp'                  => ['nullable', 'string', 'max:20', 'regex:/^[0-9+\-\s()]+$/'],
            ]);

            $status = $validated['status'] ?? 'disetujui';
            $email  = $this->sanitizeEmail($validated['email'] ?? null);
            $latitude  = $this->sanitizeCoordinate($validated['latitude'] ?? null);
            $longitude = $this->sanitizeCoordinate($validated['longitude'] ?? null);

            // 2. SIMPAN KE DATABASE
            // HAPUS SEMUA HELPER resolveJenisOrmas dan resolveBidangKegiatan
            // Langsung simpan ID dari request
            $ormas = new Ormas();
            $ormas->nama                    = $validated['nama'];
            $ormas->singkatan               = $validated['singkatan'] ?? null;
            $ormas->nomor_registrasi        = $validated['nomor_registrasi'] ?? null;
            $ormas->jenis_ormas_id          = $validated['jenis_ormas_id']; // ID langsung
            $ormas->bidang_kegiatan_id      = $validated['bidang_kegiatan_id']; // ID langsung
            $ormas->alamat_kesekretariatan  = $validated['alamat_kesekretariatan'] ?? null;
            $ormas->jumlah_anggota          = $validated['jumlah_anggota'] ?? 0;
            $ormas->jumlah_anggota_perempuan = $validated['jumlah_anggota_perempuan'] ?? 0;
            $ormas->anggota_perempuan_rentang_16_30 = $validated['anggota_perempuan_rentang_16_30'] ?? 0;
            $ormas->jumlah_anggota_laki_laki = $validated['jumlah_anggota_laki_laki'] ?? 0;
            $ormas->anggota_laki_laki_rentang_16_30 = $validated['anggota_laki_laki_rentang_16_30'] ?? 0;
            $ormas->latitude                = $latitude;
            $ormas->longitude               = $longitude;
            $ormas->kecamatan_id            = $validated['kecamatan_id'] ?? null;
            $ormas->kelurahan_id            = $validated['kelurahan_id'] ?? null;
            $ormas->no_telepon              = $validated['no_telepon'] ?? null;
            $ormas->email                   = $email;
            $ormas->status                  = $status;
            $ormas->user_id                 = $validated['user_id'] ?? null;
            $ormas->is_active               = ($status === 'disetujui') ? 1 : 0;
            $ormas->save();

            $this->savePengurusFromRequest($ormas, $validated);

            $this->logAktivitas($request, 'Tambah ORMAS', "Menambahkan ORMAS {$ormas->nama} (status: {$status})");

            return redirect()->route('admin.ormas.index')->with('success', 'ORMAS berhasil ditambahkan.');

        } catch (ValidationException $e) {
            return back()->withErrors($e->errors())->withInput();

        } catch (\Exception $e) {
            Log::error('Store ORMAS error', [
                'message' => $e->getMessage(),
                'file'    => $e->getFile(),
                'line'    => $e->getLine(),
            ]);
            return back()->with('error', 'Gagal menyimpan data ORMAS. Silakan coba lagi.')->withInput();
        }
    }

    // ============================================================
    // SHOW — Detail ORMAS
    // ============================================================
    public function show($id)
    {
        if (!$this->isValidId($id)) {
            abort(404);
        }

        $ormas = Ormas::with([
            'jenisOrmas', 'bidangKegiatan', 'kecamatan', 'kelurahan', 'pengurus', 'user',
        ])->findOrFail((int) $id);

        return view('admin.ormas.show', compact('ormas'));
    }

    // ============================================================
    // EDIT — Form Edit ORMAS
    // ============================================================
    public function edit($id)
    {
        if (!$this->isValidId($id)) {
            abort(404);
        }

        $ormas          = Ormas::with('pengurus')->findOrFail((int) $id);
        $jenisOrmas     = JenisOrmas::all();
        $bidangKegiatan = BidangKegiatan::all();
        $kecamatan      = Kecamatan::all();
        $kelurahan      = Kelurahan::all();

        return view('admin.ormas.edit', compact(
            'ormas', 'jenisOrmas', 'bidangKegiatan', 'kecamatan', 'kelurahan'
        ));
    }

    // ============================================================
    // UPDATE — Update ORMAS (form biasa)
    // ============================================================
    public function update(Request $request, $id)
    {
        try {
            $ormas = Ormas::findOrFail($id);

            $validated = $request->validate([
                'nama'                             => 'required|string|max:255',
                'singkatan'                        => 'nullable|string|max:50',
                'nomor_registrasi'                 => 'nullable|string|max:100',
                'jenis_ormas_id'                   => 'required|integer|exists:jenis_ormas,id', // Wajib ID
                'bidang_kegiatan_id'               => 'required|integer|exists:bidang_kegiatan,id', // Wajib ID
                'alamat_kesekretariatan'           => 'nullable|string|max:2000',
                'jumlah_anggota'                   => 'nullable|integer|min:0|max:1000000',
                'jumlah_anggota_perempuan'         => 'nullable|integer|min:0|max:1000000',
                'anggota_perempuan_rentang_16_30'  => 'nullable|integer|min:0|max:1000000',
                'jumlah_anggota_laki_laki'         => 'nullable|integer|min:0|max:1000000',
                'anggota_laki_laki_rentang_16_30'  => 'nullable|integer|min:0|max:1000000',
                'latitude'                         => 'nullable|numeric|between:-90,90',
                'longitude'                        => 'nullable|numeric|between:-180,180',
                'kecamatan_id'                     => 'nullable|integer|exists:kecamatan,id',
                'kelurahan_id'                     => 'nullable|integer|exists:kelurahan,id',
                'no_telepon'                       => ['nullable', 'string', 'max:20', 'regex:/^[0-9+\-\s()]+$/'],
                'email'                            => 'nullable|email|max:255',
                'status'                           => 'nullable|in:' . implode(',', self::STATUSES),
            ]);

            // Panggil helper update (tanpa resolveJenisOrmas)
            $this->applyOrmasUpdate($ormas, $validated);

            $this->logAktivitas($request, 'Update ORMAS', "Mengupdate ORMAS {$ormas->nama}");

            return redirect()->route('admin.ormas.index')->with('success', 'ORMAS berhasil diupdate.');

        } catch (ValidationException $e) {
            return back()->withErrors($e->errors())->withInput();

        } catch (\Exception $e) {
            Log::error('Update ORMAS error', [
                'message'  => $e->getMessage(),
                'ormas_id' => $id,
            ]);
            return back()->with('error', 'Gagal mengupdate data. Silakan coba lagi.');
        }
    }

    // ============================================================
    // DESTROY — Hapus ORMAS
    // ============================================================
    public function destroy($id)
    {
        try {
            if (!$this->isValidId($id)) {
                abort(404);
            }

            $ormas = Ormas::findOrFail((int) $id);
            $nama  = $ormas->nama;

            $ormas->pengurus()->delete();
            $ormas->delete();

            $this->logAktivitas(request(), 'Hapus ORMAS', "Menghapus ORMAS {$nama}");

            return redirect()->route('admin.ormas.index')->with('success', 'ORMAS berhasil dihapus.');

        } catch (\Exception $e) {
            Log::error('Hapus ORMAS error', [
                'message'  => $e->getMessage(),
                'ormas_id' => $id,
            ]);
            return back()->with('error', 'Gagal menghapus data. Silakan coba lagi.');
        }
    }

    // ============================================================
    // AUTO DELETE REJECTED
    // ============================================================
    public function autoDeleteRejected(): JsonResponse
    {
        try {
            $tenDaysAgo = now()->subDays(10);

            $ormasIds = Ormas::where('status', 'ditolak')
                ->where('updated_at', '<=', $tenDaysAgo)
                ->pluck('id');

            $count = $ormasIds->count();

            if ($count > 0) {
                DB::transaction(function () use ($ormasIds) {
                    DB::table('pengurus')->whereIn('ormas_id', $ormasIds)->delete();
                    DB::table('ormas')->whereIn('id', $ormasIds)->delete();
                });

                $this->logAktivitas(
                    request(), 'Auto Delete', "Menghapus {$count} ORMAS yang ditolak secara otomatis (lebih dari 10 hari)"
                );
            }

            return response()->json([
                'success' => true,
                'message' => "Berhasil menghapus {$count} ORMAS yang ditolak.",
                'count'   => $count,
            ]);

        } catch (\Exception $e) {
            Log::error('Auto delete rejected ORMAS error', [
                'message' => $e->getMessage(),
                'file'    => $e->getFile(),
                'line'    => $e->getLine(),
            ]);

            return response()->json([
                'success' => false,
                'message' => 'Gagal menghapus data. Silakan coba lagi.',
            ], 500);
        }
    }

    // ============================================================
    // GET ORMAS DATA — JSON
    // ============================================================
    public function getOrmasData($id): JsonResponse
    {
        if (!$this->isValidId($id)) {
            return response()->json(['success' => false, 'message' => 'Data tidak ditemukan.'], 404);
        }

        $ormas = Ormas::with([
            'jenisOrmas', 'bidangKegiatan', 'kecamatan', 'kelurahan', 'pengurus',
        ])->find((int) $id);

        if (!$ormas) {
            return response()->json(['success' => false, 'message' => 'Data tidak ditemukan.'], 404);
        }

        return response()->json($ormas);
    }

    // ============================================================
    // EDIT JSON
    // ============================================================
    public function editJson($id): JsonResponse
    {
        if (!$this->isValidId($id)) {
            return response()->json(['success' => false, 'message' => 'Data tidak ditemukan.'], 404);
        }

        $ormas = Ormas::with(['jenisOrmas', 'bidangKegiatan'])->find((int) $id);

        if (!$ormas) {
            return response()->json(['success' => false, 'message' => 'Data tidak ditemukan.'], 404);
        }

        return response()->json($ormas);
    }

    // ============================================================
    // UPDATE JSON
    // ============================================================
    public function updateJson(Request $request, $id): JsonResponse
    {
        try {
            $ormas = Ormas::findOrFail($id);

            $validated = $request->validate([
                'nama'                             => 'required|string|max:255',
                'singkatan'                        => 'nullable|string|max:50',
                'nomor_registrasi'                 => 'nullable|string|max:100',
                'jenis_ormas_id'                   => 'required|integer|exists:jenis_ormas,id', // Wajib ID
                'bidang_kegiatan_id'               => 'required|integer|exists:bidang_kegiatan,id', // Wajib ID
                'alamat_kesekretariatan'           => 'nullable|string|max:2000',
                'jumlah_anggota'                   => 'nullable|integer|min:0|max:1000000',
                'jumlah_anggota_perempuan'         => 'nullable|integer|min:0|max:1000000',
                'anggota_perempuan_rentang_16_30'  => 'nullable|integer|min:0|max:1000000',
                'jumlah_anggota_laki_laki'         => 'nullable|integer|min:0|max:1000000',
                'anggota_laki_laki_rentang_16_30'  => 'nullable|integer|min:0|max:1000000',
                'latitude'                         => 'nullable|numeric|between:-90,90',
                'longitude'                        => 'nullable|numeric|between:-180,180',
                'kecamatan_id'                     => 'nullable|integer|exists:kecamatan,id',
                'kelurahan_id'                     => 'nullable|integer|exists:kelurahan,id',
                'no_telepon'                       => ['nullable', 'string', 'max:20', 'regex:/^[0-9+\-\s()]+$/'],
                'email'                            => 'nullable|email|max:255',
            ]);

            $this->applyOrmasUpdate($ormas, $validated);

            $this->logAktivitas($request, 'Update ORMAS (AJAX)', "Mengupdate ORMAS {$ormas->nama}");

            return response()->json([
                'success' => true,
                'message' => 'ORMAS berhasil diupdate.',
                'data'    => $ormas,
            ]);

        } catch (ValidationException $e) {
            return response()->json([
                'success' => false,
                'message' => 'Validasi gagal.',
                'errors'  => $e->errors(),
            ], 422);

        } catch (\Exception $e) {
            Log::error('Update ORMAS AJAX error', [
                'message'  => $e->getMessage(),
                'ormas_id' => $id,
            ]);

            return response()->json([
                'success' => false,
                'message' => 'Gagal mengupdate data. Silakan coba lagi.',
            ], 500);
        }
    }

    // ============================================================
    // PRIVATE HELPERS
    // ============================================================

    private function isValidId($id): bool
    {
        return is_numeric($id) && (int) $id > 0;
    }

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

    private function getStatusCounters(): array
    {
        $result = Ormas::selectRaw('
            SUM(CASE WHEN status = ? THEN 1 ELSE 0 END) AS menunggu_verifikasi,
            SUM(CASE WHEN status = ? THEN 1 ELSE 0 END) AS revisi,
            SUM(CASE WHEN status = ? THEN 1 ELSE 0 END) AS ditolak,
            SUM(CASE WHEN status = ? THEN 1 ELSE 0 END) AS disetujui
        ', ['menunggu_verifikasi', 'revisi', 'ditolak', 'disetujui'])->first();

        return [
            'menunggu_verifikasi' => (int) ($result->menunggu_verifikasi ?? 0),
            'revisi'              => (int) ($result->revisi ?? 0),
            'ditolak'             => (int) ($result->ditolak ?? 0),
            'disetujui'           => (int) ($result->disetujui ?? 0),
        ];
    }

    private function sanitizeEmail(?string $email): ?string
    {
        if (empty($email) || trim($email) === '-') {
            return null;
        }
        return $email;
    }

    private function sanitizeCoordinate($value)
    {
        if ($value === null || $value === '' || $value === '0' || $value === 0) {
            return null;
        }
        return $value;
    }

    private function savePengurusFromRequest(Ormas $ormas, array $data): void
    {
        $pengurusData = [
            ['nama' => $data['ketua_nama'] ?? null, 'alamat' => $data['ketua_alamat'] ?? null, 'no_hp' => $data['ketua_no_hp'] ?? null],
            ['nama' => $data['sekretaris_nama'] ?? null, 'alamat' => $data['sekretaris_alamat'] ?? null, 'no_hp' => $data['sekretaris_no_hp'] ?? null],
            ['nama' => $data['bendahara_nama'] ?? null, 'alamat' => $data['bendahara_alamat'] ?? null, 'no_hp' => $data['bendahara_no_hp'] ?? null],
        ];

        foreach ($pengurusData as $pengurus) {
            if (!empty($pengurus['nama'])) {
                $ormas->pengurus()->create($pengurus);
            }
        }
    }

    private function applyOrmasUpdate(Ormas $ormas, array $validated): void
    {
        $email     = $this->sanitizeEmail($validated['email'] ?? null);
        $latitude  = $this->sanitizeCoordinate($validated['latitude'] ?? null);
        $longitude = $this->sanitizeCoordinate($validated['longitude'] ?? null);

        // UPDATE LANGSUNG DENGAN ID
        $ormas->nama                    = $validated['nama'];
        $ormas->singkatan               = $validated['singkatan'] ?? null;
        $ormas->nomor_registrasi        = $validated['nomor_registrasi'] ?? null;
        $ormas->jenis_ormas_id          = $validated['jenis_ormas_id']; // ID
        $ormas->bidang_kegiatan_id      = $validated['bidang_kegiatan_id']; // ID
        $ormas->alamat_kesekretariatan  = $validated['alamat_kesekretariatan'] ?? null;
        $ormas->jumlah_anggota          = $validated['jumlah_anggota'] ?? 0;
        $ormas->jumlah_anggota_perempuan = $validated['jumlah_anggota_perempuan'] ?? 0;
        $ormas->anggota_perempuan_rentang_16_30 = $validated['anggota_perempuan_rentang_16_30'] ?? 0;
        $ormas->jumlah_anggota_laki_laki = $validated['jumlah_anggota_laki_laki'] ?? 0;
        $ormas->anggota_laki_laki_rentang_16_30 = $validated['anggota_laki_laki_rentang_16_30'] ?? 0;
        $ormas->latitude                = $latitude;
        $ormas->longitude               = $longitude;
        $ormas->kecamatan_id            = $validated['kecamatan_id'] ?? null;
        $ormas->kelurahan_id            = $validated['kelurahan_id'] ?? null;
        $ormas->no_telepon              = $validated['no_telepon'] ?? null;
        $ormas->email                   = $email;

        if (!empty($validated['status'])) {
            $ormas->status    = $validated['status'];
            $ormas->is_active = ($validated['status'] === 'disetujui') ? 1 : 0;
        }

        $ormas->save();
    }
}