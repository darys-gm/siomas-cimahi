<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\OrmasController;
use App\Http\Controllers\BeritaController;
use App\Http\Controllers\AlurPelaporanController;
use App\Http\Controllers\ProdukHukumController;
use App\Http\Controllers\ProfilKesbangpolController;
use App\Http\Controllers\PelaporanOrmasController;
use App\Http\Controllers\SaranController;
use App\Http\Controllers\PosterController;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Admin\DashboardController as AdminDashboardController;
use App\Http\Controllers\Admin\DashboardDataController;
use App\Http\Controllers\Admin\OrmasController as AdminOrmasController;
use App\Http\Controllers\Admin\VerifikasiController;
use App\Http\Controllers\Admin\MasterDataController;
use App\Http\Controllers\Admin\UserManagementController;
use App\Http\Controllers\Admin\MonitoringController;
use App\Http\Controllers\Admin\LaporanController;
use App\Http\Controllers\Admin\BeritaController as AdminBeritaController;
use App\Http\Controllers\Admin\GaleriController;
use App\Http\Controllers\Admin\ProdukHukumController as AdminProdukHukumController;
use App\Http\Controllers\Admin\PasswordController;
use App\Http\Controllers\Admin\LogController;
use App\Http\Controllers\Admin\AdminSaranController;
use App\Http\Controllers\Admin\AdminSettingController;
use App\Http\Controllers\Admin\AdminPosterController;
use App\Http\Controllers\Admin\AgendaController;
use App\Http\Controllers\Admin\AdminStrukturController;
use App\Models\Kelurahan;
use App\Models\Ormas;
use App\Models\Saran;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
*/

// ============================================
// PUBLIC ROUTES — Dengan Rate Limiting
// ============================================

Route::get('/', [HomeController::class, 'index'])
    ->middleware('throttle:120,1')
    ->name('home');

Route::get('/get-agenda-data', [HomeController::class, 'getAgendaData'])
    ->middleware('throttle:60,1')
    ->name('get-agenda-data');

Route::get('/alur-pelaporan', [AlurPelaporanController::class, 'index'])
    ->middleware('throttle:60,1')
    ->name('alur-pelaporan');

Route::get('/download/formulir-ormas', function () {
    $filePath = public_path('formulir/formulir-ormas.pdf');
    if (!file_exists($filePath)) {
        abort(404);
    }
    return response()->download($filePath, 'FORMULIR_PENDAFTARAN_ORMAS.pdf');
})->middleware('throttle:10,1')->name('download.formulir-ormas');

// ============================================
// ROUTES BERITA (PUBLIK)
// ============================================
Route::get('/berita', [BeritaController::class, 'index'])
    ->middleware('throttle:60,1')
    ->name('berita.semua');

Route::get('/berita/{slug}', [BeritaController::class, 'show'])
    ->where('slug', '[A-Za-z0-9\-_]+')
    ->middleware('throttle:120,1')
    ->name('berita.show');

// ============================================
// ROUTES PRODUK HUKUM (PUBLIK)
// ============================================
Route::get('/produk-hukum', [ProdukHukumController::class, 'index'])
    ->middleware('throttle:60,1')
    ->name('produk-hukum.index');

Route::get('/produk-hukum/{id}', [ProdukHukumController::class, 'show'])
    ->where('id', '[0-9]+')
    ->middleware('throttle:120,1')
    ->name('produk-hukum.show');

Route::get('/produk-hukum/{id}/pdf', [ProdukHukumController::class, 'viewPdf'])
    ->where('id', '[0-9]+')
    ->middleware('throttle:30,1')
    ->name('produk-hukum.pdf');

// ============================================
// ROUTES ORMAS (PUBLIK)
// ============================================
Route::get('/ormas', [OrmasController::class, 'index'])
    ->middleware('throttle:60,1')
    ->name('ormas');

Route::get('/ormas/detail/{id}', [OrmasController::class, 'detail'])
    ->where('id', '[0-9]+')
    ->middleware('throttle:60,1')
    ->name('ormas.detail');

// ============================================
// ROUTES PROFIL KESBANGPOL (PUBLIK)
// ============================================
Route::get('/profil-kesbangpol', [ProfilKesbangpolController::class, 'index'])
    ->middleware('throttle:60,1')
    ->name('profil-kesbangpol');

// ============================================
// ROUTES PELAPORAN ORMAS (PUBLIK)
// ============================================
Route::get('/pelaporan-ormas', [PelaporanOrmasController::class, 'create'])
    ->middleware('throttle:30,1')
    ->name('pelaporan-ormas.create');

Route::post('/pelaporan-ormas', [PelaporanOrmasController::class, 'store'])
    ->middleware('throttle:3,60')
    ->name('pelaporan-ormas.store');

// ============================================
// ROUTES KOTAK SARAN (PUBLIK)
// ============================================
Route::get('/saran', [SaranController::class, 'index'])
    ->middleware('throttle:30,1')
    ->name('saran');

Route::post('/saran', [SaranController::class, 'store'])
    ->middleware('throttle:5,60')
    ->name('saran.store');

// ============================================
// ROUTES POSTER (PUBLIK)
// ============================================
Route::get('/poster', [PosterController::class, 'index'])
    ->middleware('throttle:60,1')
    ->name('poster');

// ============================================
// GUEST ROUTES (HANYA LOGIN)
// ============================================
Route::middleware('guest')->group(function () {
    Route::get('login', [LoginController::class, 'showLoginForm'])
        ->middleware('throttle:30,1')
        ->name('login');

    Route::post('login', [LoginController::class, 'login'])
        ->middleware('throttle:5,1');
});

// ============================================
// PERSYARATAN PENDIRIAN ORMAS
// ============================================
Route::get('/persyaratan-berbadan-hukum', function () {
    return view('persyaratan-berbadan-hukum');
})->middleware('throttle:60,1')->name('persyaratan-berbadan-hukum');

Route::get('/persyaratan-tidak-berbadan-hukum', function () {
    return view('persyaratan-tidak-berbadan-hukum');
})->middleware('throttle:60,1')->name('persyaratan-tidak-berbadan-hukum');

// ============================================
// API ROUTES (Public)
// ============================================
Route::get('/get-kelurahan/{kecamatanId}', function ($kecamatanId) {
    if (!is_numeric($kecamatanId) || (int) $kecamatanId < 1) {
        return response()->json([], 400);
    }

    $kelurahan = Kelurahan::where('kecamatan_id', (int) $kecamatanId)
        ->get(['id', 'nama']);

    return response()->json($kelurahan);
})->where('kecamatanId', '[0-9]+')
  ->middleware('throttle:60,1')
  ->name('get-kelurahan');

// ============================================
// AUTHENTICATED ROUTES (HANYA ADMIN)
// ============================================
Route::middleware('auth')->group(function () {

    Route::post('logout', [LoginController::class, 'logout'])->name('logout');

    // ============================================
    // ADMIN ROUTES
    // ============================================
    Route::middleware('admin')->prefix('admin')->name('admin.')->group(function () {

        // ---------- DASHBOARD ----------
        Route::get('dashboard', [AdminDashboardController::class, 'index'])->name('dashboard');
        Route::get('dashboard-data', [DashboardDataController::class, 'getStats'])->name('dashboard.data');

        // ---------- DATA ORMAS (CRUD Lengkap) ----------
        Route::resource('ormas', AdminOrmasController::class);
        Route::get('ormas/auto-delete-rejected', [AdminOrmasController::class, 'autoDeleteRejected'])->name('ormas.auto-delete');

        // ORMAS (AJAX)
        Route::get('ormas/{id}/edit-json', [UserManagementController::class, 'editOrmas'])->name('ormas.edit-json');
        Route::put('ormas/{id}/update-json', [UserManagementController::class, 'updateOrmas'])->name('ormas.update-json');
        Route::delete('ormas/{id}/delete', [UserManagementController::class, 'destroyOrmas'])->name('ormas.destroy');
        Route::post('ormas/{id}/toggle-active', [UserManagementController::class, 'toggleActive'])->name('ormas.toggle-active');
        Route::post('ormas/{id}/update-pelaporan', [UserManagementController::class, 'updatePelaporan'])->name('ormas.update-pelaporan');

        // BULK ACTION
        Route::post('users/bulk-update-pelaporan', [UserManagementController::class, 'bulkUpdatePelaporan'])->name('users.bulk-update-pelaporan');
        Route::post('users/bulk-update-status', [UserManagementController::class, 'bulkUpdateStatus'])->name('users.bulk-update-status');

        // PENGURUS (AJAX)
        Route::get('pengurus/{id}/edit-json', [UserManagementController::class, 'editPengurus'])->name('pengurus.edit-json');
        Route::put('pengurus/{id}/update-json', [UserManagementController::class, 'updatePengurus'])->name('pengurus.update-json');
        Route::post('pengurus/tambah', [UserManagementController::class, 'tambahPengurus'])->name('pengurus.tambah');
        Route::delete('pengurus/{id}/delete', [UserManagementController::class, 'destroyPengurus'])->name('pengurus.destroy');

        // ---------- VERIFIKASI ----------
        Route::get('verifikasi', [VerifikasiController::class, 'index'])->name('verifikasi.index');
        Route::get('verifikasi/{id}', [VerifikasiController::class, 'show'])->name('verifikasi.show');
        Route::post('verifikasi/{id}/approve', [VerifikasiController::class, 'approve'])->name('verifikasi.approve');
        Route::post('verifikasi/{id}/reject', [VerifikasiController::class, 'reject'])->name('verifikasi.reject');
        Route::post('verifikasi/{id}/revisi', [VerifikasiController::class, 'requestRevisi'])->name('verifikasi.revisi');

        // ---------- KELOLA BERITA ----------
        Route::resource('berita', AdminBeritaController::class);
        Route::patch('berita/{id}/toggle-publish', [AdminBeritaController::class, 'togglePublish'])->name('berita.toggle-publish');

        // ---------- KELOLA GALERI ----------
        Route::resource('galeri', GaleriController::class);
        Route::patch('galeri/{id}/toggle-active', [GaleriController::class, 'toggleActive'])->name('galeri.toggle-active');

        // ---------- KELOLA PRODUK HUKUM ----------
        Route::resource('produk-hukum', AdminProdukHukumController::class);
        Route::patch('produk-hukum/{id}/toggle-active', [AdminProdukHukumController::class, 'toggleActive'])->name('produk-hukum.toggle-active');

        // ---------- MASTER DATA ----------
        Route::prefix('master')->name('master.')->group(function () {
            Route::get('kecamatan', [MasterDataController::class, 'kecamatanIndex'])->name('kecamatan');
            Route::post('kecamatan', [MasterDataController::class, 'kecamatanStore']);
            Route::put('kecamatan/{id}', [MasterDataController::class, 'kecamatanUpdate']);
            Route::delete('kecamatan/{id}', [MasterDataController::class, 'kecamatanDestroy']);

            Route::get('kelurahan', [MasterDataController::class, 'kelurahanIndex'])->name('kelurahan');
            Route::post('kelurahan', [MasterDataController::class, 'kelurahanStore']);
            Route::put('kelurahan/{id}', [MasterDataController::class, 'kelurahanUpdate']);
            Route::delete('kelurahan/{id}', [MasterDataController::class, 'kelurahanDestroy']);

            Route::get('jenis-ormas', [MasterDataController::class, 'jenisOrmasIndex'])->name('jenis-ormas');
            Route::post('jenis-ormas', [MasterDataController::class, 'jenisOrmasStore']);
            Route::put('jenis-ormas/{id}', [MasterDataController::class, 'jenisOrmasUpdate']);
            Route::delete('jenis-ormas/{id}', [MasterDataController::class, 'jenisOrmasDestroy']);

            Route::get('bidang-kegiatan', [MasterDataController::class, 'bidangKegiatanIndex'])->name('bidang-kegiatan');
            Route::post('bidang-kegiatan', [MasterDataController::class, 'bidangKegiatanStore']);
            Route::put('bidang-kegiatan/{id}', [MasterDataController::class, 'bidangKegiatanUpdate']);
            Route::delete('bidang-kegiatan/{id}', [MasterDataController::class, 'bidangKegiatanDestroy']);
        });

        // ---------- MANAJEMEN USER ----------
        Route::get('users', [UserManagementController::class, 'index'])->name('users.index');
        Route::get('users/{id}', [UserManagementController::class, 'show'])->name('users.show');
        Route::post('users/{id}/activate', [UserManagementController::class, 'activate'])->name('users.activate');
        Route::post('users/{id}/deactivate', [UserManagementController::class, 'deactivate'])->name('users.deactivate');
        Route::post('users/{id}/reset-password', [UserManagementController::class, 'resetPassword'])->name('users.reset-password');

        // ---------- MONITORING ----------
        Route::get('monitoring', [MonitoringController::class, 'index'])->name('monitoring');

        // ---------- LAPORAN ----------
        Route::get('laporan', [LaporanController::class, 'index'])->name('laporan.index');
        Route::post('laporan/print', [LaporanController::class, 'print'])->name('laporan.print');

        Route::get('laporan/export-excel', [LaporanController::class, 'exportExcel'])->name('laporan.export-excel');
        Route::get('laporan/export-detail-excel/{id}', [LaporanController::class, 'exportDetailExcel'])->name('laporan.export-detail-excel');

        Route::get('laporan/download-all', [LaporanController::class, 'downloadAllOrmas'])->name('laporan.download-all');
        Route::get('laporan/download-detail/{id}', [LaporanController::class, 'downloadDetailOrmas'])->name('laporan.download-detail');

        Route::get('laporan/grafik-data', [LaporanController::class, 'getDataGrafik'])->name('laporan.grafik-data');
        Route::get('laporan/stats', [LaporanController::class, 'getStats'])->name('laporan.stats');

        // ---------- UBAH PASSWORD ----------
        Route::get('change-password', [PasswordController::class, 'index'])->name('change-password');
        Route::put('change-password', [PasswordController::class, 'update'])->name('change-password.update');

        // ============================================
        // LOG AKTIVITAS
        // ============================================
        Route::prefix('logs')->name('logs.')->group(function () {
            Route::get('/', [LogController::class, 'index'])->name('index');
            Route::delete('clear', [LogController::class, 'clear'])->name('clear');
            Route::delete('clear-old', [LogController::class, 'clearOld'])->name('clear-old');
            Route::delete('{id}', [LogController::class, 'destroy'])
                ->where('id', '[0-9]+')
                ->name('destroy');
        });

        // ============================================
        // KOTAK SARAN (Admin)
        // ============================================
        Route::get('saran', [AdminSaranController::class, 'index'])->name('saran');

        Route::get('saran/check-new', [AdminSaranController::class, 'checkNew'])
            ->middleware('throttle:60,1')
            ->name('saran.check-new');

        Route::get('saran/refresh', [AdminSaranController::class, 'refresh'])
            ->middleware('throttle:60,1')
            ->name('saran.refresh');

        Route::post('saran/{id}/tandai-dibaca', [AdminSaranController::class, 'tandaiDibaca'])
            ->where('id', '[0-9]+')
            ->name('saran.tandai-dibaca');

        Route::delete('saran/{id}', [AdminSaranController::class, 'destroy'])
            ->where('id', '[0-9]+')
            ->name('saran.destroy');

        // ---------- BADGE COUNT ----------
        Route::get('badges', function () {
            $menungguVerifikasi = Ormas::where('status', 'menunggu_verifikasi')->count();
            $saranBaru          = Saran::where('status', 'baru')->count();

            return response()->json([
                'success'            => true,
                'menungguVerifikasi' => $menungguVerifikasi,
                'saranBaru'          => $saranBaru,
            ]);
        })->name('badges');

        // ---------- MANAJEMEN HOME (SETTING) ----------
        Route::get('setting/home', [AdminSettingController::class, 'index'])->name('setting.home');
        Route::put('setting/home/running-text', [AdminSettingController::class, 'updateRunningText'])->name('setting.update-running-text');

        // ---------- MANAJEMEN POSTER ----------
        Route::resource('poster', AdminPosterController::class);
        Route::post('poster/{id}/toggle-active', [AdminPosterController::class, 'toggleActive'])->name('poster.toggle-active');
        Route::post('poster/update-order', [AdminPosterController::class, 'updateOrder'])->name('poster.update-order');

        // ---------- MANAJEMEN AGENDA ----------
        Route::resource('agenda', AgendaController::class);
        Route::post('agenda/{id}/toggle-active', [AgendaController::class, 'toggleActive'])->name('agenda.toggle-active');

        // ============================================
        // ===== MANAJEMEN STRUKTUR ORGANISASI =====
        // ============================================
        Route::get('struktur', [AdminStrukturController::class, 'index'])->name('struktur.index');
        Route::post('struktur', [AdminStrukturController::class, 'store'])->name('struktur.store');
        Route::delete('struktur/{id}', [AdminStrukturController::class, 'destroy'])
            ->where('id', '[0-9]+')
            ->name('struktur.destroy');
    });
});