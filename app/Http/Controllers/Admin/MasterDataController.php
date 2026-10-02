<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Kecamatan;
use App\Models\Kelurahan;
use App\Models\JenisOrmas;
use App\Models\BidangKegiatan;
use App\Models\LogAktivitas;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

class MasterDataController extends Controller
{
    // ============================================================
    // KECAMATAN
    // ============================================================

    public function kecamatanIndex()
    {
        $data = Kecamatan::withCount('ormas')->get();
        return view('admin.master.kecamatan', compact('data'));
    }

    public function kecamatanStore(Request $request)
    {
        try {
            $validated = $request->validate([
                'nama' => 'required|string|max:255|unique:kecamatan,nama',
            ]);

            // 🔒 Explicit assign
            $kecamatan = new Kecamatan();
            $kecamatan->nama = $validated['nama'];
            $kecamatan->save();

            LogAktivitas::create([
                'user_id'    => Auth::id(),
                'aktivitas'  => 'Tambah Kecamatan',
                'deskripsi'  => "Menambahkan kecamatan '{$kecamatan->nama}'",
                'ip_address' => $request->ip(),
                'user_agent' => $request->userAgent(),
            ]);

            return back()->with('success', 'Kecamatan berhasil ditambahkan.');

        } catch (\Illuminate\Validation\ValidationException $e) {
            return back()->withErrors($e->validator)->withInput();

        } catch (\Exception $e) {
            Log::error('Store kecamatan error', [
                'message' => $e->getMessage(),
                'file'    => $e->getFile(),
                'line'    => $e->getLine(),
            ]);
            return back()->with('error', 'Gagal menambahkan kecamatan. Silakan coba lagi.')->withInput();
        }
    }

    public function kecamatanUpdate(Request $request, $id)
    {
        try {
            // 🔒 Validasi ID
            if (!is_numeric($id) || (int) $id < 1) {
                return back()->with('error', 'ID kecamatan tidak valid.');
            }

            $validated = $request->validate([
                'nama' => 'required|string|max:255|unique:kecamatan,nama,' . $id,
            ]);

            $kecamatan = Kecamatan::find((int) $id);
            if (!$kecamatan) {
                return back()->with('error', 'Kecamatan tidak ditemukan.');
            }

            $oldNama = $kecamatan->nama;
            $kecamatan->nama = $validated['nama'];
            $kecamatan->save();

            LogAktivitas::create([
                'user_id'    => Auth::id(),
                'aktivitas'  => 'Update Kecamatan',
                'deskripsi'  => "Mengupdate kecamatan '{$oldNama}' menjadi '{$kecamatan->nama}'",
                'ip_address' => $request->ip(),
                'user_agent' => $request->userAgent(),
            ]);

            return back()->with('success', 'Kecamatan berhasil diupdate.');

        } catch (\Illuminate\Validation\ValidationException $e) {
            return back()->withErrors($e->validator)->withInput();

        } catch (\Exception $e) {
            Log::error('Update kecamatan error', [
                'message' => $e->getMessage(),
                'id'      => $id,
            ]);
            return back()->with('error', 'Gagal mengupdate kecamatan. Silakan coba lagi.')->withInput();
        }
    }

    public function kecamatanDestroy($id)
    {
        try {
            if (!is_numeric($id) || (int) $id < 1) {
                return back()->with('error', 'ID kecamatan tidak valid.');
            }

            $kecamatan = Kecamatan::find((int) $id);
            if (!$kecamatan) {
                return back()->with('error', 'Kecamatan tidak ditemukan.');
            }

            // ============================================================
            // 🔒 PROTEKSI: Cek apakah masih ada ORMAS yang pakai
            // ============================================================
            $ormasCount = $kecamatan->ormas()->count();
            if ($ormasCount > 0) {
                return back()->with('error',
                    "Kecamatan '{$kecamatan->nama}' tidak dapat dihapus karena masih digunakan oleh {$ormasCount} ORMAS."
                );
            }

            // Cek apakah masih ada kelurahan di bawahnya
            $kelurahanCount = Kelurahan::where('kecamatan_id', $kecamatan->id)->count();
            if ($kelurahanCount > 0) {
                return back()->with('error',
                    "Kecamatan '{$kecamatan->nama}' tidak dapat dihapus karena masih memiliki {$kelurahanCount} kelurahan."
                );
            }

            $nama = $kecamatan->nama;
            $kecamatan->delete();

            LogAktivitas::create([
                'user_id'    => Auth::id(),
                'aktivitas'  => 'Hapus Kecamatan',
                'deskripsi'  => "Menghapus kecamatan '{$nama}'",
                'ip_address' => request()->ip(),
                'user_agent' => request()->userAgent(),
            ]);

            return back()->with('success', 'Kecamatan berhasil dihapus.');

        } catch (\Exception $e) {
            Log::error('Delete kecamatan error', [
                'message' => $e->getMessage(),
                'id'      => $id,
            ]);
            return back()->with('error', 'Gagal menghapus kecamatan. Silakan coba lagi.');
        }
    }

    // ============================================================
    // KELURAHAN
    // ============================================================

    public function kelurahanIndex()
    {
        $data = Kelurahan::with('kecamatan')->get();
        $kecamatan = Kecamatan::all();
        return view('admin.master.kelurahan', compact('data', 'kecamatan'));
    }

    public function kelurahanStore(Request $request)
    {
        try {
            $validated = $request->validate([
                'nama'         => 'required|string|max:255',
                'kecamatan_id' => 'required|integer|exists:kecamatan,id',
            ]);

            // 🔒 Explicit assign
            $kelurahan = new Kelurahan();
            $kelurahan->nama         = $validated['nama'];
            $kelurahan->kecamatan_id = (int) $validated['kecamatan_id'];
            $kelurahan->save();

            LogAktivitas::create([
                'user_id'    => Auth::id(),
                'aktivitas'  => 'Tambah Kelurahan',
                'deskripsi'  => "Menambahkan kelurahan '{$kelurahan->nama}'",
                'ip_address' => $request->ip(),
                'user_agent' => $request->userAgent(),
            ]);

            return back()->with('success', 'Kelurahan berhasil ditambahkan.');

        } catch (\Illuminate\Validation\ValidationException $e) {
            return back()->withErrors($e->validator)->withInput();

        } catch (\Exception $e) {
            Log::error('Store kelurahan error', [
                'message' => $e->getMessage(),
            ]);
            return back()->with('error', 'Gagal menambahkan kelurahan. Silakan coba lagi.')->withInput();
        }
    }

    public function kelurahanUpdate(Request $request, $id)
    {
        try {
            if (!is_numeric($id) || (int) $id < 1) {
                return back()->with('error', 'ID kelurahan tidak valid.');
            }

            $validated = $request->validate([
                'nama'         => 'required|string|max:255',
                'kecamatan_id' => 'required|integer|exists:kecamatan,id',
            ]);

            $kelurahan = Kelurahan::find((int) $id);
            if (!$kelurahan) {
                return back()->with('error', 'Kelurahan tidak ditemukan.');
            }

            $oldNama = $kelurahan->nama;
            $kelurahan->nama         = $validated['nama'];
            $kelurahan->kecamatan_id = (int) $validated['kecamatan_id'];
            $kelurahan->save();

            LogAktivitas::create([
                'user_id'    => Auth::id(),
                'aktivitas'  => 'Update Kelurahan',
                'deskripsi'  => "Mengupdate kelurahan '{$oldNama}' menjadi '{$kelurahan->nama}'",
                'ip_address' => $request->ip(),
                'user_agent' => $request->userAgent(),
            ]);

            return back()->with('success', 'Kelurahan berhasil diupdate.');

        } catch (\Illuminate\Validation\ValidationException $e) {
            return back()->withErrors($e->validator)->withInput();

        } catch (\Exception $e) {
            Log::error('Update kelurahan error', [
                'message' => $e->getMessage(),
                'id'      => $id,
            ]);
            return back()->with('error', 'Gagal mengupdate kelurahan. Silakan coba lagi.')->withInput();
        }
    }

    public function kelurahanDestroy($id)
    {
        try {
            if (!is_numeric($id) || (int) $id < 1) {
                return back()->with('error', 'ID kelurahan tidak valid.');
            }

            $kelurahan = Kelurahan::find((int) $id);
            if (!$kelurahan) {
                return back()->with('error', 'Kelurahan tidak ditemukan.');
            }

            // 🔒 Cek apakah masih ada ORMAS yang pakai
            $ormasCount = $kelurahan->ormas()->count();
            if ($ormasCount > 0) {
                return back()->with('error',
                    "Kelurahan '{$kelurahan->nama}' tidak dapat dihapus karena masih digunakan oleh {$ormasCount} ORMAS."
                );
            }

            $nama = $kelurahan->nama;
            $kelurahan->delete();

            LogAktivitas::create([
                'user_id'    => Auth::id(),
                'aktivitas'  => 'Hapus Kelurahan',
                'deskripsi'  => "Menghapus kelurahan '{$nama}'",
                'ip_address' => request()->ip(),
                'user_agent' => request()->userAgent(),
            ]);

            return back()->with('success', 'Kelurahan berhasil dihapus.');

        } catch (\Exception $e) {
            Log::error('Delete kelurahan error', [
                'message' => $e->getMessage(),
                'id'      => $id,
            ]);
            return back()->with('error', 'Gagal menghapus kelurahan. Silakan coba lagi.');
        }
    }

    // ============================================================
    // JENIS ORMAS
    // ============================================================

    public function jenisOrmasIndex()
    {
        $data = JenisOrmas::withCount('ormas')->get();
        return view('admin.master.jenis-ormas', compact('data'));
    }

    public function jenisOrmasStore(Request $request)
    {
        try {
            $validated = $request->validate([
                'nama' => 'required|string|max:255|unique:jenis_ormas,nama',
            ]);

            $jenis = new JenisOrmas();
            $jenis->nama = $validated['nama'];
            $jenis->save();

            LogAktivitas::create([
                'user_id'    => Auth::id(),
                'aktivitas'  => 'Tambah Jenis ORMAS',
                'deskripsi'  => "Menambahkan jenis ORMAS '{$jenis->nama}'",
                'ip_address' => $request->ip(),
                'user_agent' => $request->userAgent(),
            ]);

            return back()->with('success', 'Jenis ORMAS berhasil ditambahkan.');

        } catch (\Illuminate\Validation\ValidationException $e) {
            return back()->withErrors($e->validator)->withInput();

        } catch (\Exception $e) {
            Log::error('Store jenis ormas error', ['message' => $e->getMessage()]);
            return back()->with('error', 'Gagal menambahkan jenis ORMAS. Silakan coba lagi.')->withInput();
        }
    }

    public function jenisOrmasUpdate(Request $request, $id)
    {
        try {
            if (!is_numeric($id) || (int) $id < 1) {
                return back()->with('error', 'ID jenis ORMAS tidak valid.');
            }

            $validated = $request->validate([
                'nama' => 'required|string|max:255|unique:jenis_ormas,nama,' . $id,
            ]);

            $jenis = JenisOrmas::find((int) $id);
            if (!$jenis) {
                return back()->with('error', 'Jenis ORMAS tidak ditemukan.');
            }

            $oldNama = $jenis->nama;
            $jenis->nama = $validated['nama'];
            $jenis->save();

            LogAktivitas::create([
                'user_id'    => Auth::id(),
                'aktivitas'  => 'Update Jenis ORMAS',
                'deskripsi'  => "Mengupdate jenis ORMAS '{$oldNama}' menjadi '{$jenis->nama}'",
                'ip_address' => $request->ip(),
                'user_agent' => $request->userAgent(),
            ]);

            return back()->with('success', 'Jenis ORMAS berhasil diupdate.');

        } catch (\Illuminate\Validation\ValidationException $e) {
            return back()->withErrors($e->validator)->withInput();

        } catch (\Exception $e) {
            Log::error('Update jenis ormas error', [
                'message' => $e->getMessage(),
                'id'      => $id,
            ]);
            return back()->with('error', 'Gagal mengupdate jenis ORMAS. Silakan coba lagi.')->withInput();
        }
    }

    public function jenisOrmasDestroy($id)
    {
        try {
            if (!is_numeric($id) || (int) $id < 1) {
                return back()->with('error', 'ID jenis ORMAS tidak valid.');
            }

            $jenis = JenisOrmas::find((int) $id);
            if (!$jenis) {
                return back()->with('error', 'Jenis ORMAS tidak ditemukan.');
            }

            // 🔒 Cek apakah masih ada ORMAS yang pakai
            $ormasCount = $jenis->ormas()->count();
            if ($ormasCount > 0) {
                return back()->with('error',
                    "Jenis ORMAS '{$jenis->nama}' tidak dapat dihapus karena masih digunakan oleh {$ormasCount} ORMAS."
                );
            }

            $nama = $jenis->nama;
            $jenis->delete();

            LogAktivitas::create([
                'user_id'    => Auth::id(),
                'aktivitas'  => 'Hapus Jenis ORMAS',
                'deskripsi'  => "Menghapus jenis ORMAS '{$nama}'",
                'ip_address' => request()->ip(),
                'user_agent' => request()->userAgent(),
            ]);

            return back()->with('success', 'Jenis ORMAS berhasil dihapus.');

        } catch (\Exception $e) {
            Log::error('Delete jenis ormas error', [
                'message' => $e->getMessage(),
                'id'      => $id,
            ]);
            return back()->with('error', 'Gagal menghapus jenis ORMAS. Silakan coba lagi.');
        }
    }

    // ============================================================
    // BIDANG KEGIATAN
    // ============================================================

    public function bidangKegiatanIndex()
    {
        $data = BidangKegiatan::withCount('ormas')->get();
        return view('admin.master.bidang-kegiatan', compact('data'));
    }

    public function bidangKegiatanStore(Request $request)
    {
        try {
            $validated = $request->validate([
                'nama' => 'required|string|max:255|unique:bidang_kegiatan,nama',
            ]);

            $bidang = new BidangKegiatan();
            $bidang->nama = $validated['nama'];
            $bidang->save();

            LogAktivitas::create([
                'user_id'    => Auth::id(),
                'aktivitas'  => 'Tambah Bidang Kegiatan',
                'deskripsi'  => "Menambahkan bidang kegiatan '{$bidang->nama}'",
                'ip_address' => $request->ip(),
                'user_agent' => $request->userAgent(),
            ]);

            return back()->with('success', 'Bidang Kegiatan berhasil ditambahkan.');

        } catch (\Illuminate\Validation\ValidationException $e) {
            return back()->withErrors($e->validator)->withInput();

        } catch (\Exception $e) {
            Log::error('Store bidang kegiatan error', ['message' => $e->getMessage()]);
            return back()->with('error', 'Gagal menambahkan bidang kegiatan. Silakan coba lagi.')->withInput();
        }
    }

    public function bidangKegiatanUpdate(Request $request, $id)
    {
        try {
            if (!is_numeric($id) || (int) $id < 1) {
                return back()->with('error', 'ID bidang kegiatan tidak valid.');
            }

            $validated = $request->validate([
                'nama' => 'required|string|max:255|unique:bidang_kegiatan,nama,' . $id,
            ]);

            $bidang = BidangKegiatan::find((int) $id);
            if (!$bidang) {
                return back()->with('error', 'Bidang Kegiatan tidak ditemukan.');
            }

            $oldNama = $bidang->nama;
            $bidang->nama = $validated['nama'];
            $bidang->save();

            LogAktivitas::create([
                'user_id'    => Auth::id(),
                'aktivitas'  => 'Update Bidang Kegiatan',
                'deskripsi'  => "Mengupdate bidang kegiatan '{$oldNama}' menjadi '{$bidang->nama}'",
                'ip_address' => $request->ip(),
                'user_agent' => $request->userAgent(),
            ]);

            return back()->with('success', 'Bidang Kegiatan berhasil diupdate.');

        } catch (\Illuminate\Validation\ValidationException $e) {
            return back()->withErrors($e->validator)->withInput();

        } catch (\Exception $e) {
            Log::error('Update bidang kegiatan error', [
                'message' => $e->getMessage(),
                'id'      => $id,
            ]);
            return back()->with('error', 'Gagal mengupdate bidang kegiatan. Silakan coba lagi.')->withInput();
        }
    }

    public function bidangKegiatanDestroy($id)
    {
        try {
            if (!is_numeric($id) || (int) $id < 1) {
                return back()->with('error', 'ID bidang kegiatan tidak valid.');
            }

            $bidang = BidangKegiatan::find((int) $id);
            if (!$bidang) {
                return back()->with('error', 'Bidang Kegiatan tidak ditemukan.');
            }

            // 🔒 Cek apakah masih ada ORMAS yang pakai
            $ormasCount = $bidang->ormas()->count();
            if ($ormasCount > 0) {
                return back()->with('error',
                    "Bidang Kegiatan '{$bidang->nama}' tidak dapat dihapus karena masih digunakan oleh {$ormasCount} ORMAS."
                );
            }

            $nama = $bidang->nama;
            $bidang->delete();

            LogAktivitas::create([
                'user_id'    => Auth::id(),
                'aktivitas'  => 'Hapus Bidang Kegiatan',
                'deskripsi'  => "Menghapus bidang kegiatan '{$nama}'",
                'ip_address' => request()->ip(),
                'user_agent' => request()->userAgent(),
            ]);

            return back()->with('success', 'Bidang Kegiatan berhasil dihapus.');

        } catch (\Exception $e) {
            Log::error('Delete bidang kegiatan error', [
                'message' => $e->getMessage(),
                'id'      => $id,
            ]);
            return back()->with('error', 'Gagal menghapus bidang kegiatan. Silakan coba lagi.');
        }
    }
}