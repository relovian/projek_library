<?php
use App\Http\Controllers\AktivitasController;
use App\Http\Controllers\ArsipController;
use App\Http\Controllers\ArsipKeluarController;
use App\Http\Controllers\ArsipMasukController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\PengaturanController;
use App\Http\Controllers\PersetujuanController;
use Illuminate\Support\Facades\Route;
// ── Redirect root ke dashboard ──────────────────────────
Route::get('/', fn() => redirect()->route('dashboard'));
// ── Auth routes (login/register/password/etc) ─────────
require __DIR__ . '/auth.php';




// ── Protected routes ─────────────────────────────────────
Route::middleware(['auth', \App\Http\Middleware\IsActiveMiddleware::class])->group(function () {

    // Pastikan user aktif untuk seluruh route protected
    
    


    // Dashboard
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

    // Arsip
    Route::prefix('arsip')->name('arsip.')->group(function () {
        Route::get('/',                    [ArsipController::class, 'index'])->name('index');
        
        // ← taruh semua route statis DI SINI, sebelum {arsip}
        Route::get('/trash', [ArsipController::class, 'trash'])->name('trash');
        Route::delete('/empty-trash', [ArsipController::class, 'emptyTrash'])->name('empty-trash');
        Route::patch('/{id}/restore', [ArsipController::class, 'restore'])->name('restore');
        Route::delete('/{id}/force-delete',[ArsipController::class, 'forceDelete'])->name('force-delete');

        // Route Edit Arsip
        Route::get('/{arsip}', [ArsipController::class, 'show'])->name('show');
        Route::get('/{arsip}/edit', [ArsipController::class, 'edit'])->name('edit');
        Route::put('/{arsip}', [ArsipController::class, 'update'])->name('update');
        Route::delete('/{arsip}', [ArsipController::class, 'destroy'])->name('destroy');
        Route::get('/{arsip}/unduh', [ArsipController::class, 'download'])->name('download');
    });

    // Aktivitas
    Route::get('/aktivitas', [AktivitasController::class, 'index'])->name('aktivitas.index');

    // Persetujuan (admin & pimpinan)
    Route::prefix('persetujuan')->name('persetujuan.')->middleware('role:admin,pimpinan')->group(function () {
        Route::get('/', [PersetujuanController::class, 'index'])->name('index');
        Route::post('/{arsip}/setujui', [PersetujuanController::class, 'setujui'])->name('setujui');
        Route::post('/{arsip}/tolak', [PersetujuanController::class, 'tolak'])->name('tolak');
        Route::post('/bulk-setujui', [PersetujuanController::class, 'bulkSetujui'])->name('bulk-setujui');
    });

    // Pengaturan
    Route::prefix('pengaturan')->name('pengaturan.')->group(function () {
        Route::get('/', [PengaturanController::class, 'index'])->name('index');
        Route::get('/profil', [PengaturanController::class, 'profil'])->name('profil');
        Route::put('/profil', [PengaturanController::class, 'updateProfil'])->name('profil.update');
        Route::put('/password', [PengaturanController::class, 'updatePassword'])->name('password.update');

        // Admin only
        Route::get('/kategori', [PengaturanController::class, 'kategori'])->name('kategori')->middleware('role:admin');

        Route::post('/kategori', [PengaturanController::class, 'storeKategori'])->name('kategori.store');
        Route::put('/kategori/{kategori}', [PengaturanController::class, 'updateKategori'])->name('kategori.update');
        Route::delete('/kategori/{kategori}', [PengaturanController::class, 'destroyKategori'])->name('kategori.destroy');

        Route::get('/users', [PengaturanController::class, 'users'])->name('users')->middleware('role:admin');
        Route::get('/users/create', [PengaturanController::class, 'createUser'])->name('users.create')->middleware('role:admin');
        Route::get('/users/{user}/edit', [PengaturanController::class, 'editUser'])->name('users.edit')->middleware('role:admin');
        Route::get('/divisi', [PengaturanController::class, 'divisi'])->name('divisi')->middleware('role:admin');

        // Users
        Route::post('/users', [PengaturanController::class, 'storeUser'])->name('users.store');
        Route::put('/users/{user}', [PengaturanController::class, 'updateUser'])->name('users.update');
        Route::delete('/users/{user}', [PengaturanController::class, 'destroyUser'])->name('users.destroy');

        // Divisi
        Route::post('/divisi', [PengaturanController::class, 'storeDivisi'])->name('divisi.store');
        Route::put('/divisi/{divisi}', [PengaturanController::class, 'updateDivisi'])->name('divisi.update');
        Route::delete('/divisi/{divisi}', [PengaturanController::class, 'destroyDivisi'])->name('divisi.destroy');

        // Sub Bagian
        Route::get('/sub_bagian', [PengaturanController::class, 'subBagians'])->name('sub_bagian')->middleware('role:admin');
        Route::post('/sub_bagian', [PengaturanController::class, 'storeSubBagian'])->name('sub_bagian.store')->middleware('role:admin');
        Route::put('/sub_bagian/{sub_bagian}', [PengaturanController::class, 'updateSubBagian'])->name('sub_bagian.update')->middleware('role:admin');
        Route::delete('/sub_bagian/{sub_bagian}', [PengaturanController::class, 'destroySubBagian'])->name('sub_bagian.destroy')->middleware('role:admin');

        // Kode Klasifikasi
        Route::get('/klasifikasi', [PengaturanController::class, 'klasifikasi'])->name('klasifikasi')->middleware('role:admin');
        Route::post('/klasifikasi', [PengaturanController::class, 'storeKlasifikasi'])->name('klasifikasi.store')->middleware('role:admin');
        Route::put('/klasifikasi/{klasifikasi}', [PengaturanController::class, 'updateKlasifikasi'])->name('klasifikasi.update')->middleware('role:admin');
        Route::delete('/klasifikasi/{klasifikasi}', [PengaturanController::class, 'destroyKlasifikasi'])->name('klasifikasi.destroy')->middleware('role:admin');

        // Sifat Surat
        Route::get('/sifat_surat', [PengaturanController::class, 'sifatSurat'])->name('sifat_surat')->middleware('role:admin');
        Route::post('/sifat_surat', [PengaturanController::class, 'storeSifatSurat'])->name('sifat_surat.store')->middleware('role:admin');
        Route::put('/sifat_surat/{sifat_surat}', [PengaturanController::class, 'updateSifatSurat'])->name('sifat_surat.update')->middleware('role:admin');
        Route::delete('/sifat_surat/{sifat_surat}', [PengaturanController::class, 'destroySifatSurat'])->name('sifat_surat.destroy')->middleware('role:admin');

        // notifikasi
        Route::get('/notifikasi', [PengaturanController::class, 'notifikasi'])->name('notifikasi');
        Route::get('/notifikasi', [PengaturanController::class, 'notifikasi'])->name('notifikasi');
        Route::put('/notifikasi', [PengaturanController::class, 'updateNotifikasi'])->name('notifikasi.update');
 
        // backup
        Route::get('/backup', [PengaturanController::class, 'backup'])->name('backup')->middleware('role:admin');
        Route::post('/backup/database', [PengaturanController::class, 'backupDatabase'])->name('backup.database')->middleware('role:admin');
        Route::post('/backup/files', [PengaturanController::class, 'backupFiles'])->name('backup.files')->middleware('role:admin');
        Route::get('/backup/download/{filename}', [PengaturanController::class, 'downloadBackup'])->name('backup.download')->middleware('role:admin');
        Route::delete('/backup/{filename}', [PengaturanController::class, 'destroyBackup'])->name('backup.destroy')->middleware('role:admin');
        Route::post('/maintenance/cache', [PengaturanController::class, 'clearCache'])->name('maintenance.cache')->middleware('role:admin');
        Route::post('/maintenance/log', [PengaturanController::class, 'clearLog'])->name('maintenance.log')->middleware('role:admin');
        Route::post('/maintenance/draft', [PengaturanController::class, 'clearDraft'])->name('maintenance.draft')->middleware('role:admin');

        // Verifikator
        Route::get('/verifikator', [PengaturanController::class, 'verifikator'])->name('verifikator')->middleware('role:admin');
        Route::post('/verifikator', [PengaturanController::class, 'storeVerifikator'])->name('verifikator.store')->middleware('role:admin');
        Route::put('/verifikator/{verifikator}', [PengaturanController::class, 'updateVerifikator'])->name('verifikator.update')->middleware('role:admin');
        Route::delete('/verifikator/{verifikator}', [PengaturanController::class, 'destroyVerifikator'])->name('verifikator.destroy')->middleware('role:admin');
        
        // Tujuan
        Route::get('/tujuan', [PengaturanController::class, 'tujuan'])->name('tujuan')->middleware('role:admin');
        Route::post('/tujuan', [PengaturanController::class, 'storeTujuan'])->name('tujuan.store')->middleware('role:admin');
        Route::put('/tujuan/{tujuan}', [PengaturanController::class, 'updateTujuan'])->name('tujuan.update')->middleware('role:admin');
        Route::delete('/tujuan/{tujuan}', [PengaturanController::class, 'destroyTujuan'])->name('tujuan.destroy')->middleware('role:admin');
    });

    // Arsip Masuk
    Route::prefix('arsip-masuk')->name('arsip-masuk.')->group(function () {
        Route::get('/create', [ArsipMasukController::class, 'create'])->name('create');
        Route::post('/', [ArsipMasukController::class, 'store'])->name('store');
        Route::get('/upload', [ArsipMasukController::class, 'upload']);

        // Trash
        Route::get('/trash', [ArsipMasukController::class, 'trash'])->name('trash');
        Route::patch('/{id}/restore', [ArsipMasukController::class, 'restore'])->name('restore');
        Route::delete('/{id}/force-delete', [ArsipMasukController::class, 'forceDelete'])->name('force-delete');

        // Edit & Hapus
        Route::get('/{arsipMasuk}/edit', [ArsipMasukController::class, 'edit'])->name('edit');
        Route::put('/{arsipMasuk}', [ArsipMasukController::class, 'update'])->name('update');
        Route::delete('/{arsipMasuk}', [ArsipMasukController::class, 'destroy'])->name('destroy');
    });

    // Arsip Keluar
    Route::prefix('arsip-keluar')->name('arsip-keluar.')->group(function () {
        Route::get('/create', [ArsipKeluarController::class, 'create'])->name('create');
        Route::post('/', [ArsipKeluarController::class, 'store'])->name('store');

        // Trash
        Route::get('/trash', [ArsipKeluarController::class, 'trash'])->name('trash');
        Route::patch('/{id}/restore', [ArsipKeluarController::class, 'restore'])->name('restore');
        Route::delete('/{id}/force-delete', [ArsipKeluarController::class, 'forceDelete'])->name('force-delete');

        // Edit & Hapus
        Route::get('/{arsipKeluar}/edit', [ArsipKeluarController::class, 'edit'])->name('edit');
        Route::put('/{arsipKeluar}', [ArsipKeluarController::class, 'update'])->name('update');
        Route::delete('/{arsipKeluar}', [ArsipKeluarController::class, 'destroy'])->name('destroy');
    });
});
