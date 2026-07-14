<?php
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\ArsipController;
use App\Http\Controllers\SuratMasukController;
use App\Http\Controllers\PersetujuanController;
use App\Http\Controllers\AktivitasController;
use App\Http\Controllers\PengaturanController;
use Google\Client;
use Google\Service\Drive;
// ── Redirect root ke dashboard ──────────────────────────
Route::get('/', fn() => redirect()->route('dashboard'));

// ── Auth (gunakan Laravel Breeze / bawaan) ───────────────
require __DIR__ . '/auth.php';

// ── Protected routes ─────────────────────────────────────
Route::middleware(['auth'])->group(function () {

    // Dashboard
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

    Route::get('/auth/google', function () {
        $client = new Client();
        $client->setAuthConfig(storage_path('app/oauth-credentials.json'));
        $client->addScope(Drive::DRIVE_FILE);
        $client->setRedirectUri('http://127.0.0.1:8000/oauth-callback');
        $client->setAccessType('offline');
        $client->setPrompt('consent');

        return redirect($client->createAuthUrl());
    });

    Route::get('/test-drive', function () {
    $client = new Client();
    $client->setAuthConfig(storage_path('app/oauth-credentials.json'));
    $client->setAccessToken(json_decode(Storage::get('google-token.json'), true));
    
    $drive = new Drive($client);

    $fileMetadata = new Google\Service\Drive\DriveFile([
        'name' => 'Test_File_Dari_Laravel.txt'
    ]);
    
    $content = "Halo! Ini file yang dibuat lewat Laravel.";
    
    $file = $drive->files->create($fileMetadata, [
        'data' => $content,
        'mimeType' => 'text/plain',
        'uploadType' => 'multipart'
    ]);
    
   
    return "File berhasil dibuat! ID File: " . $file->id;
});

   Route::get('/oauth-callback', function (Request $request) {
    $client = new \Google\Client();
    $client->setAuthConfig(storage_path('app/oauth-credentials.json'));
    $client->setRedirectUri('http://127.0.0.1:8000/oauth-callback');

    $token = $client->fetchAccessTokenWithAuthCode($request->query('code'));

    // TAMBAHKAN INI UNTUK CEK ERROR
    if (isset($token['error'])) {
        dd("Gagal mendapatkan token: " . $token['error_description']);
    }

    // SIMPAN DENGAN PATH LENGKAP UNTUK MEMASTIKAN LOKASI
    $path = storage_path('app/google-token.json');
    file_put_contents($path, json_encode($token));

    return "Token berhasil dibuat di: " . $path;
});

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

        // Sub Bagian
        Route::get('/sub_bagians',           [PengaturanController::class, 'subBagians'])->name('sub_bagians')->middleware('role:admin');
        Route::post('/sub_bagians',          [PengaturanController::class, 'storeSubBagian'])->name('sub_bagians.store')->middleware('role:admin');
        Route::put('/sub_bagians/{sub_bagian}', [PengaturanController::class, 'updateSubBagian'])->name('sub_bagians.update')->middleware('role:admin');
        Route::delete('/sub_bagians/{sub_bagian}', [PengaturanController::class, 'destroySubBagian'])->name('sub_bagians.destroy')->middleware('role:admin');

        // Kode Klasifikasi
        Route::get('/klasifikasis',          [PengaturanController::class, 'klasifikasis'])->name('klasifikasis')->middleware('role:admin');
        Route::post('/klasifikasis',         [PengaturanController::class, 'storeKlasifikasi'])->name('klasifikasis.store')->middleware('role:admin');
        Route::put('/klasifikasis/{klasifikasi}', [PengaturanController::class, 'updateKlasifikasi'])->name('klasifikasis.update')->middleware('role:admin');
        Route::delete('/klasifikasis/{klasifikasi}', [PengaturanController::class, 'destroyKlasifikasi'])->name('klasifikasis.destroy')->middleware('role:admin');

        // Sifat Surat
        Route::get('/sifat_surats',          [PengaturanController::class, 'sifatSurats'])->name('sifat_surats')->middleware('role:admin');
        Route::post('/sifat_surats',         [PengaturanController::class, 'storeSifatSurat'])->name('sifat_surats.store')->middleware('role:admin');
        Route::put('/sifat_surats/{sifat_surat}', [PengaturanController::class, 'updateSifatSurat'])->name('sifat_surats.update')->middleware('role:admin');
        Route::delete('/sifat_surats/{sifat_surat}', [PengaturanController::class, 'destroySifatSurat'])->name('sifat_surats.destroy')->middleware('role:admin');

        // notifikasi
        Route::get('/notifikasi', [PengaturanController::class, 'notifikasi'])->name('notifikasi');
        Route::get('/notifikasi',  [PengaturanController::class, 'notifikasi'])->name('notifikasi');
        Route::put('/notifikasi',  [PengaturanController::class, 'updateNotifikasi'])->name('notifikasi.update');
 
        // backup
        Route::get('/backup',                    [PengaturanController::class, 'backup'])->name('backup')->middleware('role:admin');
        Route::post('/backup/database',          [PengaturanController::class, 'backupDatabase'])->name('backup.database')->middleware('role:admin');
        Route::post('/backup/files',             [PengaturanController::class, 'backupFiles'])->name('backup.files')->middleware('role:admin');
        Route::get('/backup/download/{filename}', [PengaturanController::class, 'downloadBackup'])->name('backup.download')->middleware('role:admin');
        Route::delete('/backup/{filename}',       [PengaturanController::class, 'destroyBackup'])->name('backup.destroy')->middleware('role:admin');
        Route::post('/maintenance/cache',        [PengaturanController::class, 'clearCache'])->name('maintenance.cache')->middleware('role:admin');
        Route::post('/maintenance/log',          [PengaturanController::class, 'clearLog'])->name('maintenance.log')->middleware('role:admin');
        Route::post('/maintenance/draft',        [PengaturanController::class, 'clearDraft'])->name('maintenance.draft')->middleware('role:admin');

        // Verifikator
        Route::get('/verifikator',          [PengaturanController::class, 'verifikator'])->name('verifikator')->middleware('role:admin');
        Route::post('/verifikator',         [PengaturanController::class, 'storeVerifikator'])->name('verifikator.store')->middleware('role:admin');
        Route::put('/verifikator/{verifikator}', [PengaturanController::class, 'updateVerifikator'])->name('verifikator.update')->middleware('role:admin');
        Route::delete('/verifikator/{verifikator}', [PengaturanController::class, 'destroyVerifikator'])->name('verifikator.destroy')->middleware('role:admin');
        
        // Tujuan
        Route::get('/tujuan',          [PengaturanController::class, 'tujuan'])->name('tujuan')->middleware('role:admin');
        Route::post('/tujuan',         [PengaturanController::class, 'storeTujuan'])->name('tujuan.store')->middleware('role:admin');
        Route::put('/tujuan/{tujuan}', [PengaturanController::class, 'updateTujuan'])->name('tujuan.update')->middleware('role:admin');
        Route::delete('/tujuan/{tujuan}', [PengaturanController::class, 'destroyTujuan'])->name('tujuan.destroy')->middleware('role:admin');
    });

    // Surat Masuk
    Route::prefix('surat-masuk')->name('surat-masuk.')->group(function () {
        Route::get('/create',  [SuratMasukController::class, 'create'])->name('create');
        Route::post('/',       [SuratMasukController::class, 'store'])->name('store');
        Route::get('/upload',       [SuratMasukController::class, 'upload']);
    });

    // Arsip Keluar
    Route::prefix('arsip-keluar')->name('arsip-keluar.')->group(function () {
        Route::get('/create',  [\App\Http\Controllers\ArsipKeluarController::class, 'create'])->name('create');
        Route::post('/',       [\App\Http\Controllers\ArsipKeluarController::class, 'store'])->name('store');
    });
});
