<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\ArsipController;
use App\Http\Controllers\UnggahController;
use App\Http\Controllers\PersetujuanController;
use App\Http\Controllers\AktivitasController;
use App\Http\Controllers\PengaturanController;

// ── Redirect root ke dashboard ──────────────────────────
Route::get('/', fn() => redirect()->route('dashboard'));

// ── Auth (gunakan Laravel Breeze / bawaan) ───────────────
require __DIR__ . '/auth.php';

// ── Protected routes ─────────────────────────────────────
Route::middleware(['auth'])->group(function () {

    // Dashboard
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

    // Arsip
    Route::prefix('arsip')->name('arsip.')->group(function () {
        Route::get('/',                    [ArsipController::class, 'index'])->name('index');
        
        // ← taruh semua route statis DI SINI, sebelum {arsip}
        Route::get('/trash',               [ArsipController::class, 'trash'])->name('trash');
        Route::delete('/empty-trash',      [ArsipController::class, 'emptyTrash'])->name('empty-trash');
        Route::patch('/{id}/restore',      [ArsipController::class, 'restore'])->name('restore');
        Route::delete('/{id}/force-delete',[ArsipController::class, 'forceDelete'])->name('force-delete');

        // ← baru route dinamis {arsip} di bawah
        Route::get('/{arsip}',             [ArsipController::class, 'show'])->name('show');
        Route::get('/{arsip}/edit',        [ArsipController::class, 'edit'])->name('edit');
        Route::put('/{arsip}',             [ArsipController::class, 'update'])->name('update');
        Route::delete('/{arsip}',          [ArsipController::class, 'destroy'])->name('destroy');
        Route::get('/{arsip}/unduh',       [ArsipController::class, 'download'])->name('download');
    });

    // Unggah
    Route::prefix('unggah')->name('unggah.')->group(function () {
        Route::get('/',    [UnggahController::class, 'create'])->name('create');
        Route::post('/',   [UnggahController::class, 'store'])->name('store');
    });

    // Aktivitas
    Route::get('/aktivitas', [AktivitasController::class, 'index'])->name('aktivitas.index');

    // Persetujuan (admin & pimpinan)
    Route::prefix('persetujuan')->name('persetujuan.')->middleware('role:admin,pimpinan')->group(function () {
        Route::get('/',                       [PersetujuanController::class, 'index'])->name('index');
        Route::post('/{arsip}/setujui',       [PersetujuanController::class, 'setujui'])->name('setujui');
        Route::post('/{arsip}/tolak',         [PersetujuanController::class, 'tolak'])->name('tolak');
        Route::post('/bulk-setujui',          [PersetujuanController::class, 'bulkSetujui'])->name('bulk-setujui');
    });

    // Pengaturan
    Route::prefix('pengaturan')->name('pengaturan.')->group(function () {
        Route::get('/',          [PengaturanController::class, 'index'])->name('index');
        Route::get('/profil',    [PengaturanController::class, 'profil'])->name('profil');
        Route::put('/profil',    [PengaturanController::class, 'updateProfil'])->name('profil.update');
        Route::put('/password',  [PengaturanController::class, 'updatePassword'])->name('password.update');

        // Admin only
        Route::get('/kategoris', [PengaturanController::class, 'kategoris'])->name('kategoris')->middleware('role:admin');

        Route::post('/kategoris',        [PengaturanController::class, 'storeKategori'])->name('kategoris.store');
        Route::put('/kategoris/{kategori}',   [PengaturanController::class, 'updateKategori'])->name('kategoris.update');
        Route::delete('/kategoris/{kategori}',[PengaturanController::class, 'destroyKategori'])->name('kategoris.destroy');

        Route::get('/users',     [PengaturanController::class, 'users'])->name('users')->middleware('role:admin');
        Route::get('/divisis',   [PengaturanController::class, 'divisis'])->name('divisis')->middleware('role:admin');

        // Users
        Route::post('/users',           [PengaturanController::class, 'storeUser'])->name('users.store');
        Route::put('/users/{user}',     [PengaturanController::class, 'updateUser'])->name('users.update');
        Route::delete('/users/{user}',  [PengaturanController::class, 'destroyUser'])->name('users.destroy');

        // Divisis
        Route::post('/divisis',              [PengaturanController::class, 'storeDivisi'])->name('divisis.store');
        Route::put('/divisis/{divisi}',      [PengaturanController::class, 'updateDivisi'])->name('divisis.update');
        Route::delete('/divisis/{divisi}',   [PengaturanController::class, 'destroyDivisi'])->name('divisis.destroy');

        // notifikasi
        Route::get('/notifikasi', [PengaturanController::class, 'notifikasi'])->name('notifikasi');
        Route::get('/notifikasi',  [PengaturanController::class, 'notifikasi'])->name('notifikasi');
        Route::put('/notifikasi',  [PengaturanController::class, 'updateNotifikasi'])->name('notifikasi.update');
 

        Route::get('/backup',                    [PengaturanController::class, 'backup'])->name('backup')->middleware('role:admin');
        Route::post('/backup/database',          [PengaturanController::class, 'backupDatabase'])->name('backup.database')->middleware('role:admin');
        Route::post('/backup/files',             [PengaturanController::class, 'backupFiles'])->name('backup.files')->middleware('role:admin');
        Route::post('/maintenance/cache',        [PengaturanController::class, 'clearCache'])->name('maintenance.cache')->middleware('role:admin');
        Route::post('/maintenance/log',          [PengaturanController::class, 'clearLog'])->name('maintenance.log')->middleware('role:admin');
        Route::post('/maintenance/draft',        [PengaturanController::class, 'clearDraft'])->name('maintenance.draft')->middleware('role:admin');
    });

    Route::prefix('unggah')->name('unggah.')->group(function () {
        Route::get('/',                    [UnggahController::class, 'create'])->name('create');
        Route::post('/',                   [UnggahController::class, 'store'])->name('store');

        // ← Tambahkan 2 route ini
        Route::get('/draft/{arsip}/edit',  [UnggahController::class, 'editDraft'])->name('draft.edit');
        Route::put('/draft/{arsip}',       [UnggahController::class, 'updateDraft'])->name('draft.update');
    });
});