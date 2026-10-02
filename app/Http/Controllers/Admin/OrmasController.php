<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Ormas;
use App\Models\Kecamatan;
use App\Models\Kelurahan;
use App\Models\JenisOrmas;
use App\Models\BidangKegiatan;
use App\Models\User;
use App\Models\Pengurus;
use App\Models\LogAktivitas;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class OrmasController extends Controller
{
    /**
     * Menampilkan daftar ORMAS untuk halaman Organisasi
     */
    public function index(Request $request)
    {
        $search = $request->search;
        $bentuk = $request->bentuk;
        $bidang = $request->bidang;
        $kecamatan = $request->kecamatan;
        $kelurahan = $request->kelurahan;
        $status = $request->status;

        $query = Ormas::with(['jenisOrmas', 'bidangKegiatan', 'kecamatan', 'kelurahan', 'pengurus', 'user']);

        // Filter search berdasarkan nama
        if ($search) {
            $query->where('nama', 'like', '%' . $search . '%');
        }

        // Filter berdasarkan Status
        if ($status) {
            $query->where('status', $status);
        }

        // Filter berdasarkan Kecamatan
        if ($kecamatan) {
            $query->where('kecamatan_id', $kecamatan);
        }

        // Filter berdasarkan Kelurahan
        if ($kelurahan) {
            $query->where('kelurahan_id', $kelurahan);
        }

        // Filter berdasarkan Bentuk Ormas (Jenis ORMAS)
        if ($bentuk) {
            $query->whereHas('jenisOrmas', function($q) use ($bentuk) {
                $q->where('nama', $bentuk);
            });
        }

        // Filter berdasarkan Bidang Kegiatan
        if ($bidang) {
            $query->whereHas('bidangKegiatan', function($q) use ($bidang) {
                $q->where('nama', $bidang);
            });
        }

        $ormas = $query->orderBy('created_at', 'desc')->paginate(10);
        
        // Pertahankan parameter filter di pagination
        $ormas->appends([
            'search' => $search,
            'bentuk' => $bentuk,
            'bidang' => $bidang,
            'kecamatan' => $kecamatan,
            'kelurahan' => $kelurahan,
            'status' => $status,
        ]);

        // Hitung jumlah ORMAS berdasarkan status
        $totalMenunggu = Ormas::where('status', 'menunggu_verifikasi')->count();
        $totalRevisi = Ormas::where('status', 'revisi')->count();
        $totalDitolak = Ormas::where('status', 'ditolak')->count();
        $totalDisetujui = Ormas::where('status', 'disetujui')->count();

        return view('admin.ormas.index', compact(
            'ormas', 
            'search', 
            'bentuk', 
            'bidang', 
            'kecamatan', 
            'kelurahan', 
            'status',
            'totalMenunggu',
            'totalRevisi',
            'totalDitolak',
            'totalDisetujui'
        ));
    }

    /**
     * Menampilkan form tambah ORMAS
     */
    public function create()
    {
        $jenisOrmas = JenisOrmas::all();
        $bidangKegiatan = BidangKegiatan::all();
        $kecamatan = Kecamatan::all();
        $kelurahan = Kelurahan::all();
        $users = User::where('role', 'user')->get();
        
        return view('admin.ormas.create', compact('jenisOrmas', 'bidangKegiatan', 'kecamatan', 'kelurahan', 'users'));
    }

    /**
     * Menyimpan data ORMAS baru
     */
    public function store(Request $request)
    {
        try {
            $validated = $request->validate([
                'nama' => 'required|string|max:255',
                'singkatan' => 'nullable|string|max:50',
                'nomor_registrasi' => 'nullable|string|max:100',
                'jenis_ormas_id' => 'nullable|string|max:255',
                'bidang_kegiatan_id' => 'nullable|string|max:255',
                'alamat_kesekretariatan' => 'nullable|string',
                'jumlah_anggota' => 'nullable|integer|min:0',
                'jumlah_anggota_perempuan' => 'nullable|integer|min:0',
                'anggota_perempuan_rentang_16_30' => 'nullable|integer|min:0',
                'jumlah_anggota_laki_laki' => 'nullable|integer|min:0',
                'anggota_laki_laki_rentang_16_30' => 'nullable|integer|min:0',
                'latitude' => 'nullable|numeric|between:-90,90',
                'longitude' => 'nullable|numeric|between:-180,180',
                'kecamatan_id' => 'nullable|exists:kecamatan,id',
                'kelurahan_id' => 'nullable|exists:kelurahan,id', // PERBAIKAN: validasi exists
                'no_telepon' => 'nullable|string|max:20',
                'email' => 'nullable|string|max:255',
                'status' => 'nullable|in:draft,menunggu_verifikasi,revisi,disetujui,ditolak',
                'user_id' => 'nullable|exists:users,id',
                'ketua_nama' => 'required|string|max:255',
                'ketua_alamat' => 'nullable|string',
                'ketua_no_hp' => 'nullable|string|max:20',
                'sekretaris_nama' => 'nullable|string|max:255',
                'sekretaris_alamat' => 'nullable|string',
                'sekretaris_no_hp' => 'nullable|string|max:20',
                'bendahara_nama' => 'nullable|string|max:255',
                'bendahara_alamat' => 'nullable|string',
                'bendahara_no_hp' => 'nullable|string|max:20',
            ]);

            // Proses email: jika kosong atau '-' maka set null
            $email = $validated['email'] ?? null;
            if (empty($email) || trim($email) === '-') {
                $email = null;
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
                $kecamatan = Kecamatan::find($validated['kecamatan_id']);
                if ($kecamatan) {
                    $kecamatanId = $kecamatan->id;
                }
            }

            // ===== PERBAIKAN: PROSES KELURAHAN - LANGSUNG MENGGUNAKAN ID =====
            $kelurahanId = null;
            if (!empty($validated['kelurahan_id'])) {
                // Langsung cari berdasarkan ID, bukan nama
                $kelurahan = Kelurahan::find($validated['kelurahan_id']);
                if ($kelurahan) {
                    $kelurahanId = $kelurahan->id;
                }
            }

            $status = $validated['status'] ?? 'disetujui';

            // Proses koordinat: jika 0 atau kosong maka set null
            $latitude = $validated['latitude'] ?? null;
            $longitude = $validated['longitude'] ?? null;
            if ($latitude === null || $latitude === '' || $latitude === '0' || $latitude === 0) {
                $latitude = null;
            }
            if ($longitude === null || $longitude === '' || $longitude === '0' || $longitude === 0) {
                $longitude = null;
            }

            // Simpan data ORMAS
            $ormas = Ormas::create([
                'nama' => $validated['nama'],
                'singkatan' => $validated['singkatan'] ?? null,
                'nomor_registrasi' => $validated['nomor_registrasi'] ?? null,
                'jenis_ormas_id' => $jenisOrmasId,
                'bidang_kegiatan_id' => $bidangKegiatanId,
                'alamat_kesekretariatan' => $validated['alamat_kesekretariatan'] ?? null,
                'jumlah_anggota' => $validated['jumlah_anggota'] ?? 0,
                'jumlah_anggota_perempuan' => $validated['jumlah_anggota_perempuan'] ?? 0,
                'anggota_perempuan_rentang_16_30' => $validated['anggota_perempuan_rentang_16_30'] ?? 0,
                'jumlah_anggota_laki_laki' => $validated['jumlah_anggota_laki_laki'] ?? 0,
                'anggota_laki_laki_rentang_16_30' => $validated['anggota_laki_laki_rentang_16_30'] ?? 0,
                'latitude' => $latitude,
                'longitude' => $longitude,
                'kecamatan_id' => $kecamatanId,
                'kelurahan_id' => $kelurahanId,
                'no_telepon' => $validated['no_telepon'] ?? null,
                'email' => $email,
                'status' => $status,
                'user_id' => $validated['user_id'] ?? null,
                'is_active' => $status === 'disetujui' ? 1 : 0,
            ]);

            // Simpan data pengurus
            $pengurusData = [
                ['jabatan' => 'Ketua', 'nama' => $validated['ketua_nama'] ?? null, 'alamat' => $validated['ketua_alamat'] ?? null, 'no_hp' => $validated['ketua_no_hp'] ?? null],
                ['jabatan' => 'Sekretaris', 'nama' => $validated['sekretaris_nama'] ?? null, 'alamat' => $validated['sekretaris_alamat'] ?? null, 'no_hp' => $validated['sekretaris_no_hp'] ?? null],
                ['jabatan' => 'Bendahara', 'nama' => $validated['bendahara_nama'] ?? null, 'alamat' => $validated['bendahara_alamat'] ?? null, 'no_hp' => $validated['bendahara_no_hp'] ?? null],
            ];

            foreach ($pengurusData as $data) {
                if (!empty($data['nama'])) {
                    $ormas->pengurus()->create($data);
                }
            }

            LogAktivitas::create([
                'user_id' => auth()->id(),
                'aktivitas' => 'Tambah ORMAS',
                'deskripsi' => "Menambahkan ORMAS {$ormas->nama} (status: {$status})",
                'ip_address' => $request->ip(),
                'user_agent' => $request->userAgent()
            ]);

            return redirect()->route('admin.ormas.index')
                ->with('success', 'ORMAS berhasil ditambahkan.');

        } catch (\Illuminate\Validation\ValidationException $e) {
            return back()->withErrors($e->errors())->withInput();
        } catch (\Exception $e) {
            \Log::error('Error saving ORMAS: ' . $e->getMessage());
            return back()->with('error', 'Gagal menyimpan data ORMAS: ' . $e->getMessage())->withInput();
        }
    }

    /**
     * Menampilkan detail ORMAS
     */
    public function show($id)
    {
        $ormas = Ormas::with([
            'jenisOrmas', 
            'bidangKegiatan', 
            'kecamatan', 
            'kelurahan', 
            'pengurus', 
            'user'
        ])->findOrFail($id);
            
        return view('admin.ormas.show', compact('ormas'));
    }

    /**
     * Menampilkan form edit ORMAS
     */
    public function edit($id)
    {
        $ormas = Ormas::with('pengurus')->findOrFail($id);
        $jenisOrmas = JenisOrmas::all();
        $bidangKegiatan = BidangKegiatan::all();
        $kecamatan = Kecamatan::all();
        $kelurahan = Kelurahan::all();
        
        return view('admin.ormas.edit', compact('ormas', 'jenisOrmas', 'bidangKegiatan', 'kecamatan', 'kelurahan'));
    }

    /**
     * Update data ORMAS
     */
    public function update(Request $request, $id)
    {
        try {
            $ormas = Ormas::findOrFail($id);
            
            $validated = $request->validate([
                'nama' => 'required|string|max:255',
                'singkatan' => 'nullable|string|max:50',
                'nomor_registrasi' => 'nullable|string|max:100',
                'jenis_ormas_id' => 'nullable|string|max:255',
                'bidang_kegiatan_id' => 'nullable|string|max:255',
                'alamat_kesekretariatan' => 'nullable|string',
                'jumlah_anggota' => 'nullable|integer|min:0',
                'jumlah_anggota_perempuan' => 'nullable|integer|min:0',
                'anggota_perempuan_rentang_16_30' => 'nullable|integer|min:0',
                'jumlah_anggota_laki_laki' => 'nullable|integer|min:0',
                'anggota_laki_laki_rentang_16_30' => 'nullable|integer|min:0',
                'latitude' => 'nullable|numeric|between:-90,90',
                'longitude' => 'nullable|numeric|between:-180,180',
                'kecamatan_id' => 'nullable|exists:kecamatan,id',
                'kelurahan_id' => 'nullable|exists:kelurahan,id', // PERBAIKAN: validasi exists
                'no_telepon' => 'nullable|string|max:20',
                'email' => 'nullable|string|max:255',
                'status' => 'nullable|in:draft,menunggu_verifikasi,revisi,disetujui,ditolak'
            ]);

            // Proses email: jika kosong atau '-' maka set null
            $email = $validated['email'] ?? null;
            if (empty($email) || trim($email) === '-') {
                $email = null;
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

            // Proses koordinat
            $latitude = $validated['latitude'] ?? null;
            $longitude = $validated['longitude'] ?? null;
            if ($latitude === null || $latitude === '' || $latitude === '0' || $latitude === 0) {
                $latitude = null;
            }
            if ($longitude === null || $longitude === '' || $longitude === '0' || $longitude === 0) {
                $longitude = null;
            }

            // ===== PERBAIKAN: PROSES KELURAHAN - LANGSUNG MENGGUNAKAN ID =====
            $kelurahanId = $validated['kelurahan_id'] ?? null;

            $ormas->update([
                'nama' => $validated['nama'],
                'singkatan' => $validated['singkatan'] ?? null,
                'nomor_registrasi' => $validated['nomor_registrasi'] ?? null,
                'jenis_ormas_id' => $jenisOrmasId,
                'bidang_kegiatan_id' => $bidangKegiatanId,
                'alamat_kesekretariatan' => $validated['alamat_kesekretariatan'] ?? null,
                'jumlah_anggota' => $validated['jumlah_anggota'] ?? 0,
                'jumlah_anggota_perempuan' => $validated['jumlah_anggota_perempuan'] ?? 0,
                'anggota_perempuan_rentang_16_30' => $validated['anggota_perempuan_rentang_16_30'] ?? 0,
                'jumlah_anggota_laki_laki' => $validated['jumlah_anggota_laki_laki'] ?? 0,
                'anggota_laki_laki_rentang_16_30' => $validated['anggota_laki_laki_rentang_16_30'] ?? 0,
                'latitude' => $latitude,
                'longitude' => $longitude,
                'kecamatan_id' => $validated['kecamatan_id'] ?? null,
                'kelurahan_id' => $kelurahanId,
                'no_telepon' => $validated['no_telepon'] ?? null,
                'email' => $email,
                'status' => $validated['status'] ?? $ormas->status,
                'is_active' => ($validated['status'] ?? $ormas->status) === 'disetujui' ? 1 : 0,
            ]);

            LogAktivitas::create([
                'user_id' => auth()->id(),
                'aktivitas' => 'Update ORMAS',
                'deskripsi' => "Mengupdate ORMAS {$ormas->nama}",
                'ip_address' => $request->ip(),
                'user_agent' => $request->userAgent()
            ]);

            return redirect()->route('admin.ormas.index')
                ->with('success', 'ORMAS berhasil diupdate.');

        } catch (\Illuminate\Validation\ValidationException $e) {
            return back()->withErrors($e->errors())->withInput();
        } catch (\Exception $e) {
            return back()->with('error', 'Gagal mengupdate data: ' . $e->getMessage());
        }
    }

    /**
     * Hapus data ORMAS
     */
    public function destroy($id)
    {
        try {
            $ormas = Ormas::findOrFail($id);
            $nama = $ormas->nama;
            
            $ormas->pengurus()->delete();
            $ormas->delete();

            LogAktivitas::create([
                'user_id' => auth()->id(),
                'aktivitas' => 'Hapus ORMAS',
                'deskripsi' => "Menghapus ORMAS {$nama}",
                'ip_address' => request()->ip(),
                'user_agent' => request()->userAgent()
            ]);

            return redirect()->route('admin.ormas.index')
                ->with('success', 'ORMAS berhasil dihapus.');

        } catch (\Exception $e) {
            return back()->with('error', 'Gagal menghapus data: ' . $e->getMessage());
        }
    }

    /**
     * Hapus ORMAS yang ditolak secara otomatis setelah 10 hari
     * (Dijalankan oleh scheduler atau cron job)
     */
    public function autoDeleteRejected()
    {
        try {
            $tenDaysAgo = now()->subDays(10);
            
            $rejectedOrmas = Ormas::where('status', 'ditolak')
                ->where('updated_at', '<=', $tenDaysAgo)
                ->get();

            $count = 0;
            foreach ($rejectedOrmas as $ormas) {
                $ormas->pengurus()->delete();
                $ormas->delete();
                $count++;
            }

            if ($count > 0) {
                LogAktivitas::create([
                    'user_id' => auth()->id() ?? 1,
                    'aktivitas' => 'Auto Delete',
                    'deskripsi' => "Menghapus {$count} ORMAS yang ditolak secara otomatis (lebih dari 10 hari)",
                    'ip_address' => request()->ip(),
                    'user_agent' => request()->userAgent()
                ]);
            }

            return response()->json([
                'success' => true,
                'message' => "Berhasil menghapus {$count} ORMAS yang ditolak.",
                'count' => $count
            ]);

        } catch (\Exception $e) {
            Log::error('Error auto deleting rejected ORMAS: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Gagal menghapus data: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Mendapatkan data ORMAS untuk AJAX (AJAX untuk edit)
     */
    public function getOrmasData($id)
    {
        $ormas = Ormas::with([
            'jenisOrmas', 
            'bidangKegiatan', 
            'kecamatan', 
            'kelurahan', 
            'pengurus'
        ])->findOrFail($id);
            
        return response()->json($ormas);
    }

    /**
     * Mendapatkan data ORMAS untuk edit JSON (AJAX)
     */
    public function editJson($id)
    {
        try {
            $ormas = Ormas::with(['jenisOrmas', 'bidangKegiatan'])->findOrFail($id);
            return response()->json($ormas);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Data tidak ditemukan'
            ], 404);
        }
    }

    /**
     * Update ORMAS via AJAX JSON
     */
    public function updateJson(Request $request, $id)
    {
        try {
            $ormas = Ormas::findOrFail($id);

            $validated = $request->validate([
                'nama' => 'required|string|max:255',
                'singkatan' => 'nullable|string|max:50',
                'nomor_registrasi' => 'nullable|string|max:100',
                'jenis_ormas_id' => 'nullable|string|max:255',
                'bidang_kegiatan_id' => 'nullable|string|max:255',
                'alamat_kesekretariatan' => 'nullable|string',
                'jumlah_anggota' => 'nullable|integer|min:0',
                'jumlah_anggota_perempuan' => 'nullable|integer|min:0',
                'anggota_perempuan_rentang_16_30' => 'nullable|integer|min:0',
                'jumlah_anggota_laki_laki' => 'nullable|integer|min:0',
                'anggota_laki_laki_rentang_16_30' => 'nullable|integer|min:0',
                'latitude' => 'nullable|numeric|between:-90,90',
                'longitude' => 'nullable|numeric|between:-180,180',
                'kecamatan_id' => 'nullable|exists:kecamatan,id',
                'kelurahan_id' => 'nullable|exists:kelurahan,id', // PERBAIKAN: validasi exists
                'no_telepon' => 'nullable|string|max:20',
                'email' => 'nullable|string|max:255',
            ]);

            // Proses email
            $email = $validated['email'] ?? null;
            if (empty($email) || trim($email) === '-') {
                $email = null;
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

            // Proses koordinat
            $latitude = $validated['latitude'] ?? null;
            $longitude = $validated['longitude'] ?? null;
            if ($latitude === null || $latitude === '' || $latitude === '0' || $latitude === 0) {
                $latitude = null;
            }
            if ($longitude === null || $longitude === '' || $longitude === '0' || $longitude === 0) {
                $longitude = null;
            }

            // ===== PERBAIKAN: PROSES KELURAHAN - LANGSUNG MENGGUNAKAN ID =====
            $kelurahanId = $validated['kelurahan_id'] ?? null;

            $ormas->update([
                'nama' => $validated['nama'],
                'singkatan' => $validated['singkatan'] ?? null,
                'nomor_registrasi' => $validated['nomor_registrasi'] ?? null,
                'jenis_ormas_id' => $jenisOrmasId,
                'bidang_kegiatan_id' => $bidangKegiatanId,
                'alamat_kesekretariatan' => $validated['alamat_kesekretariatan'] ?? null,
                'jumlah_anggota' => $validated['jumlah_anggota'] ?? 0,
                'jumlah_anggota_perempuan' => $validated['jumlah_anggota_perempuan'] ?? 0,
                'anggota_perempuan_rentang_16_30' => $validated['anggota_perempuan_rentang_16_30'] ?? 0,
                'jumlah_anggota_laki_laki' => $validated['jumlah_anggota_laki_laki'] ?? 0,
                'anggota_laki_laki_rentang_16_30' => $validated['anggota_laki_laki_rentang_16_30'] ?? 0,
                'latitude' => $latitude,
                'longitude' => $longitude,
                'kecamatan_id' => $validated['kecamatan_id'] ?? null,
                'kelurahan_id' => $kelurahanId,
                'no_telepon' => $validated['no_telepon'] ?? null,
                'email' => $email,
            ]);

            LogAktivitas::create([
                'user_id' => auth()->id(),
                'aktivitas' => 'Update ORMAS (AJAX)',
                'deskripsi' => "Mengupdate ORMAS {$ormas->nama}",
                'ip_address' => $request->ip(),
                'user_agent' => $request->userAgent()
            ]);

            return response()->json([
                'success' => true,
                'message' => 'ORMAS berhasil diupdate.',
                'data' => $ormas->fresh()
            ]);

        } catch (\Exception $e) {
            \Log::error('Error updating ORMAS AJAX: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Gagal mengupdate data: ' . $e->getMessage()
            ], 500);
        }
    }
}