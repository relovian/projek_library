<?php

namespace App\Http\Controllers;

use App\Models\AktivitasLog;
use App\Models\Divisi;
use App\Models\Kategori;
use App\Models\Klasifikasi;
use App\Models\SifatSurat;
use App\Models\SubBagian;
use App\Models\Tujuan;
use App\Models\User;
use App\Models\Verifikator;
use Ifsnop\Mysqldump as MysqlDump;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;
use Illuminate\Validation\Rules;

class PengaturanController extends Controller
{
    // ── Halaman Utama Pengaturan ──────────────────────────
    public function index()
    {
        return view('pengaturan.index');
    }

    // ── Profil & Password ─────────────────────────────────
    public function profil()
    {
        return view('pengaturan.profil', ['user' => auth()->user()]);
    }

    public function updateProfil(Request $request)
    {
        $user = auth()->user();

        $request->validate([
            'nama_lengkap'   => 'required|string|max:255',
            'nama_panggilan' => 'required|string|max:100',
            'email'          => 'required|email|unique:users,email,' . $user->id,
            'telepon'        => 'nullable|string|max:20',
        ]);

        $user->update($request->only('nama_lengkap', 'nama_panggilan', 'email', 'telepon'));

        return back()->with('success', 'Profil berhasil diperbarui.');
    }

    public function updatePassword(Request $request)
    {
        $request->validate([
            'password_lama' => ['required'],
            'password_baru' => [
                'required',
                'string',
                'min:8',
                'confirmed',
                Rules\Password::min(8)->mixedCase()->numbers(),
            ],
            'password_baru_confirmation' => ['required'],
        ], [
            'password_lama.required'              => 'Password lama wajib diisi.',

            'password_baru.required'              => 'Password baru wajib diisi.',
            'password_baru.string'                => 'Password baru harus berupa teks.',
            'password_baru.min'                   => 'Password baru minimal 8 karakter.',
            'password_baru.confirmed'             => 'Konfirmasi password baru tidak cocok.',
            'password_baru.mixed'                 => 'Password baru harus mengandung huruf besar dan huruf kecil.',
            'password_baru.numbers'               => 'Password baru harus mengandung minimal satu angka.',

            'password_baru_confirmation.required' => 'Konfirmasi password baru wajib diisi.',
        ]);

        if (!Hash::check($request->password_lama, auth()->user()->password)) {
            return back()->withErrors(['password_lama' => 'Password lama yang kamu masukkan tidak sesuai.']);
        }

        // Cegah pakai password yang sama dengan yang lama
        if (Hash::check($request->password_baru, auth()->user()->password)) {
            return back()->withErrors(['password_baru' => 'Password baru tidak boleh sama dengan password lama.']);
        }

        auth()->user()->update(['password' => Hash::make($request->password_baru)]);

        return back()->with('success', 'Password berhasil diubah.');
    }

    public function storeUser(Request $request)
    {
        $request->validate([
            'nama_lengkap'   => ['required', 'string', 'max:255'],
            'nama_panggilan' => ['required', 'string', 'max:100'],
            'email'          => ['required', 'string', 'lowercase', 'email', 'max:255', 'unique:'.User::class],
            'password'       => ['required', Rules\Password::min(8)->mixedCase()->numbers()->symbols()],
            'nip'            => 'nullable|digits:18|unique:users,nip', 
            'role'           => 'required|in:admin,komisioner,kepala_sekretariat,kepala_sub_bagian,staff',
            'is_verifikator' => 'boolean',
        ], [
            'nama_lengkap.required' => 'Nama lengkap wajib diisi.',
            'nama_lengkap.string' => 'Nama harus berupa teks.',
            'nama_lengkap.max' => 'Nama maksimal 255 karakter.',
            'nama_panggilan.required' => 'Nama panggilan wajib diisi.',
            'nama_panggilan.string' => 'Nama panggilan harus berupa teks.',
            'nama_panggilan.max' => 'Nama panggilan maksimal 100 karakter.',
            'email.required' => 'Alamat email wajib diisi.',
            'email.string' => 'Email harus berupa teks.',
            'email.lowercase' => 'Email harus menggunakan huruf kecil.',
            'email.email' => 'Format alamat email tidak valid.',
            'email.max' => 'Email maksimal 255 karakter.',
            'email.unique' => 'Email ini sudah terdaftar, silakan gunakan email lain.',
            'password.required' => 'Kata sandi wajib diisi.',
            'password.min' => 'Kata sandi minimal 8 karakter.',
            'password.mixed' => 'Kata sandi harus mengandung huruf besar dan huruf kecil.',
            'password.numbers' => 'Kata sandi harus mengandung minimal satu angka.',
            'password.symbols' => 'Kata sandi harus mengandung minimal satu simbol.',
            'nip.digits' => 'NIP harus terdiri dari tepat 18 angka.',
            'nip.unique' => 'NIP sudah terdaftar di sistem.',
            'role.required' => 'Pilih role pengguna.',
            'role.in' => 'Role yang dipilih tidak valid.',
        ]);

        $user = User::create([
            'nama_lengkap'   => $request->nama_lengkap,
            'nama_panggilan' => $request->nama_panggilan,
            'nip'            => $request->nip,
            'email'          => $request->email,
            'password'       => \Hash::make($request->password),
            'role'           => $request->role,
            'divisi_id'      => $request->divisi_id ?: null,
            'is_aktif'       => $request->is_aktif ?? 1,
            'is_verifikator'       => $request->boolean('is_verifikator'),
        ]);

        if ($user->is_verifikator) { 
            $user->dataVerifikator()->create([
                'is_aktif' => 1,
            ]);
        }

        return back()->with('success', 'User berhasil ditambahkan.');
    }

    public function updateUser(Request $request, $id )
    {

        $user = User::find($id);

        if (!$user) {
            return redirect()->route('admin.users')
                ->with('error', 'User dengan ID tersebut tidak ditemukan.');
        }

        $rules = [
            'nama_lengkap'   => 'required|string|max:255',
            'nama_panggilan' => 'required|string|max:100',
            'email'          => 'required|email|unique:users,email,' . $user->id,
            'nip'            => [
                'required',
                'string',
                'max:50',
                Rule::unique('users', 'nip')->ignore($user->id),
            ],
            'role'           => 'required|in:admin,komisioner,kepala_sekretariat,kepala_sub_bagian,staff',
        ];

        $messages = [
            'nama_panggilan.required' => 'Nama panggilan wajib diisi.',
            'nama_panggilan.string' => 'Nama panggilan harus berupa teks.',
            'nama_panggilan.max' => 'Nama panggilan maksimal 100 karakter.',
            'nip.unique' => 'NIP sudah digunakan. Silakan gunakan NIP yang berbeda.',
            'email.unique' => 'Email sudah digunakan. Silakan gunakan email yang berbeda.',
        ];

        // Only validate password if field is filled
        if ($request->filled('password')) {
            $rules['password'] = ['required', Rules\Password::min(8)->mixedCase()->numbers()->symbols()];
            $messages['password.required'] = 'Kata sandi wajib diisi jika ingin mengubahnya.';
            $messages['password.min'] = 'Kata sandi minimal 8 karakter.';
            $messages['password.mixed'] = 'Kata sandi harus mengandung huruf besar dan huruf kecil.';
            $messages['password.numbers'] = 'Kata sandi harus mengandung minimal satu angka.';
            $messages['password.symbols'] = 'Kata sandi harus mengandung minimal satu simbol.';
        }

        $request->validate($rules, $messages);

        $isVerifikator = $request->boolean('is_verifikator');

        $data = [
            'nama_lengkap'   => $request->nama_lengkap,
            'nama_panggilan' => $request->nama_panggilan,
            'nip'            => $request->nip,
            'email'          => $request->email,
            'role'           => $request->role,
            'divisi_id'      => $request->divisi_id ?: null,
            'is_aktif'       => $request->is_aktif ?? 1,
            'is_verifikator' => $isVerifikator,
        ];

        // Update password only if filled
        if ($request->filled('password')) {
            $data['password'] = \Hash::make($request->password);
        }

        $user->update($data);

        if ($isVerifikator) {
            $user->dataVerifikator()->updateOrCreate(
                ['user_id' => $user->id],
                ['is_aktif' => 1]
            );
        } else {
            $user->dataVerifikator()->delete();
        }

        return back()->with('success', 'User berhasil diperbarui.');

    }
    

    public function destroyUser(User $user)
    {
        abort_if($user->id === auth()->id(), 403, 'Tidak bisa hapus akun sendiri.');
        $user->delete();
        return back()->with('success', 'User berhasil dihapus.');
    }
       // ── Tambah Divisi ───────────────────────────
    public function storeDivisi(Request $request)
    {
        $request->validate([
            'nama' => 'required|string|max:100|unique:divisi,nama',
            'kode' => 'required|string|max:50|unique:divisi,kode',
        ],[
            'nama.unique' => "Nama sudah digunakan, silakan gunakan nama yang berbeda",
            'kode.unique' => "Kode sudah digunakan, silakan gunakan kode yang berbeda",
        ]);

        Divisi::create([
            'nama'      => $request->nama,
            'kode'      => strtoupper($request->kode),
            'deskripsi' => $request->deskripsi,
            'is_aktif'  => $request->is_aktif ?? 1,
        ]);

        return back()->with('success', 'Divisi berhasil ditambahkan.');
    }
       // ── Update Divisi ───────────────────────────
    public function updateDivisi(Request $request, Divisi $divisi)
    {
        $request->validate([
            'nama' => 'required|string|max:100|unique:divisi,nama,' . $divisi->id,
            'kode' => 'required|string|max:50|unique:divisi,kode,' . $divisi->id,
        ],[
            'nama.unique' => "Nama sudah digunakan, silakan gunakan nama yang berbeda",
            'kode.unique' => "Kode sudah digunakan, silakan gunakan kode yang berbeda",
        ]);

        $divisi->update([
            'nama'      => $request->nama,
            'kode'      => strtoupper($request->kode),
            'deskripsi' => $request->deskripsi,
            'is_aktif'  => $request->is_aktif ?? 1,
        ]);

        return back()->with('success', 'Divisi berhasil diperbarui.');
    }

    public function destroyDivisi(Divisi $divisi)
    {
        abort_if($divisi->arsips()->count() > 0, 403, 'Divisi masih memiliki arsip.');
        $divisi->delete();
        return back()->with('success', 'Divisi berhasil dihapus.');
    }

    // ── Kelola User (Admin) ───────────────────────────────
    public function users(Request $request)
    {
        abort_if(!auth()->user()->isAdmin(), 403, 'Akses ditolak.');

        $users   = User::with('divisi')->paginate(8);
        $divisi = Divisi::where('is_aktif', true)->get();
        return view('pengaturan.users', compact('users', 'divisi'));
    }

    // ── Halaman Tambah User (Halaman Terpisah) ─────────────
    public function createUser()
    {
        abort_if(!auth()->user()->isAdmin(), 403, 'Akses ditolak.');

        $divisi = Divisi::where('is_aktif', true)->get();
        return view('pengaturan.user_form', [
            'user' => null,
            'divisi' => $divisi,
            'mode' => 'create',
        ]);
    }

    // ── Halaman Edit User (Halaman Terpisah) ──────────────
    public function editUser(User $user)
    {
        abort_if(!auth()->user()->isAdmin(), 403, 'Akses ditolak.');

        $divisi = Divisi::where('is_aktif', true)->get();
        return view('pengaturan.user_form', [
            'user' => $user,
            'divisi' => $divisi,
            'mode' => 'edit',
        ]);
    }

    // ── Kelola Divisi (Admin) ─────────────────────────────
    public function divisi(Request $request)
    {
        abort_if(!auth()->user()->isAdmin(), 403, 'Akses ditolak.');

        if ($request->has('edit') && !User::find($request->get('edit'))) {
            return redirect()->route('pengaturan.divisi')
            ->with('error', 'Divisi dengan ID ' . $request->get('edit') . ' tidak ditemukan.');
        }

        $divisi = Divisi::withCount('arsips')->get();
        return view('pengaturan.divisi', compact('divisi'));
    }

    public function notifikasi()
    {
        return view('pengaturan.notifikasi', ['user' => auth()->user()]);
    }
    
    // ── Update Preferensi Notifikasi ──────────────────────
    public function updateNotifikasi(Request $request)
    {
        auth()->user()->update([
            'notif_arsip_baru'             => $request->boolean('notif_arsip_baru'),
            'notif_arsip_disetujui'        => $request->boolean('notif_arsip_disetujui'),
            'notif_arsip_ditolak'          => $request->boolean('notif_arsip_ditolak'),
            'notif_menunggu_persetujuan'   => $request->boolean('notif_menunggu_persetujuan'),
            'notif_revisi_dokumen'         => $request->boolean('notif_revisi_dokumen'),
        ]);
    
        return back()->with('success', 'Preferensi notifikasi berhasil disimpan.');
    }

    public function backup()
    {
        abort_if(!auth()->user()->isAdmin(), 403);

        // Ambil daftar file backup dari folder storage/app/backups
        $backupDir = storage_path('app/backups');
        $backups = [];

        if (file_exists($backupDir)) {
            $files = scandir($backupDir);
            foreach ($files as $file) {
                if ($file === '.' || $file === '..') continue;

                $filePath = $backupDir . '/' . $file;
                $tipe = str_starts_with($file, 'backup_db_') ? 'database' : 'files';
                $bytes = filesize($filePath);

                $ukuran = $bytes >= 1073741824
                    ? round($bytes / 1073741824, 2) . ' GB'
                    : ($bytes >= 1048576
                        ? round($bytes / 1048576, 2) . ' MB'
                        : ($bytes >= 1024
                            ? round($bytes / 1024, 2) . ' KB'
                            : $bytes . ' B'));

                $backups[] = [
                    'nama'   => $file,
                    'ukuran' => $ukuran,
                    'waktu'  => date('Y-m-d H:i:s', filemtime($filePath)),
                    'tipe'   => $tipe,
                ];
            }

            // Urutkan dari yang terbaru
            usort($backups, fn($a, $b) => strtotime($b['waktu']) - strtotime($a['waktu']));
        }

        return view('pengaturan.backup', compact('backups'));
    }

    public function backupDatabase()
    {
        abort_if(!auth()->user()->isAdmin(), 403);

        $filename = 'backup_db_' . date('Ymd_His') . '.sql';
        $path = storage_path('app/backups/' . $filename);

        if (!file_exists(storage_path('app/backups'))) {
            mkdir(storage_path('app/backups'), 0755, true);
        }

        try {
            $db = config('database.connections.mysql');
            $dump = new MysqlDump\Mysqldump(
                "mysql:host={$db['host']};dbname={$db['database']}",
                $db['username'],
                $db['password']
            );
            $dump->start($path);

            return back()->with('success', "Backup database berhasil disimpan: {$filename}");
        } catch (\Exception $e) {
            return back()->with('error', 'Gagal backup database: ' . $e->getMessage());
        }
    }

    public function backupFiles()
    {
        abort_if(!auth()->user()->isAdmin(), 403);

        $filename = 'backup_files_' . date('Ymd_His') . '.zip';
        $backupDir = storage_path('app/backups');
        
        if (!file_exists($backupDir)) {
            mkdir($backupDir, 0755, true);
        }

        $zipPath = $backupDir . '/' . $filename;
        $zip = new \ZipArchive();

        if ($zip->open($zipPath, \ZipArchive::CREATE | \ZipArchive::OVERWRITE) !== true) {
            return back()->with('error', 'Gagal membuat file backup.');
        }

        // Ambil semua file arsip dari database
        $files = \App\Models\ArsipFile::all();

        if ($files->isEmpty()) {
            $zip->close();
            unlink($zipPath);
            return back()->with('error', 'Tidak ada file arsip untuk di-backup.');
        }

        $totalFiles = 0;
        foreach ($files as $file) {
            $filePath = storage_path('app/private/' . $file->path);
            if (file_exists($filePath)) {
                // Simpan dengan struktur folder: arsip/tahun/bulan/nama_file
                $zip->addFile($filePath, $file->path);
                $totalFiles++;
            }
        }

        $zip->close();

        if ($totalFiles === 0) {
            unlink($zipPath);
            return back()->with('error', 'Tidak ada file fisik yang ditemukan untuk di-backup.');
        }

        $size = filesize($zipPath);
        $sizeFormatted = $size >= 1073741824 
            ? round($size / 1073741824, 2) . ' GB'
            : ($size >= 1048576 
                ? round($size / 1048576, 2) . ' MB' 
                : round($size / 1024, 2) . ' KB');

        return back()->with('success', "Backup file arsip berhasil: {$filename} ({$sizeFormatted}, {$totalFiles} file)");
    }

    public function downloadBackup($filename)
    {
        abort_if(!auth()->user()->isAdmin(), 403);

        $filePath = storage_path('app/backups/' . $filename);

        if (!file_exists($filePath)) {
            return back()->with('error', 'File backup tidak ditemukan.');
        }

        return response()->download($filePath);
    }

    public function destroyBackup($filename)
    {
        abort_if(!auth()->user()->isAdmin(), 403);

        $filePath = storage_path('app/backups/' . $filename);

        if (!file_exists($filePath)) {
            return back()->with('error', 'File backup tidak ditemukan.');
        }

        unlink($filePath);
        return back()->with('success', "Backup {$filename} berhasil dihapus.");
    }

    public function clearCache()
    {
        abort_if(!auth()->user()->isAdmin(), 403);
        \Artisan::call('cache:clear');
        \Artisan::call('config:clear');
        \Artisan::call('view:clear');
        return back()->with('success', 'Cache berhasil dibersihkan.');
    }

    public function clearLog()
    {
        abort_if(!auth()->user()->isAdmin(), 403);

        try {
            $deleted = AktivitasLog::where('created_at', '<', now()->subDays(30))->delete();
            
            if ($deleted > 0) {
                return back()->with('success', "{$deleted} log aktivitas berusia lebih dari 90 hari berhasil dihapus.");
            } else {
                return back()->with('success', 'Tidak ada log aktivitas yang berusia lebih dari 90 hari untuk dihapus.');
            }
        } catch (\Exception $e) {
            return back()->with('error', 'Gagal menghapus log aktivitas: ' . $e->getMessage());
        }
    }

    public function clearDraft()
    {
        abort_if(!auth()->user()->isAdmin(), 403);
        \App\Models\Arsip::draft()->where('updated_at', '<', now()->subDays(30))->delete();
        return back()->with('success', 'Draft kadaluarsa berhasil dihapus.');
    }

    // ── Kelola Sub Bagian (Admin) ─────────────────────────────
    public function subBagians(Request $request)
    {
        abort_if(!auth()->user()->isAdmin(), 403, 'Akses ditolak.');

        $subBagian = SubBagian::get();//withCount('arsips')->
        // $divisi = Divisi::where('is_aktif', true)->get();
        return view('pengaturan.sub_bagian', compact('subBagian'));
    }

    public function storeSubBagian(Request $request)
    {
        $request->validate([
            'nama' => 'required|string|max:100|unique:sub_bagian,nama',
        ],[
            'nama.unique' => "Nama sudah digunakan, silakan gunakan nama yang berbeda",
        ]);

        SubBagian::create([
            'nama'      => $request->nama,
            'deskripsi' => $request->deskripsi,
            'is_aktif'  => $request->is_aktif ?? 1,
        ]);

        return back()->with('success', 'Sub Bagian berhasil ditambahkan.');
    }

    public function updateSubBagian(Request $request, \App\Models\SubBagian $sub_bagian)
    {
        $request->validate([
            'nama' => 'required|string|max:100|unique:sub_bagian,nama,' . $sub_bagian->id,
          
        ],[
            'nama.unique' => "Nama sudah digunakan, silakan gunakan nama yang berbeda",
        ]);

        $sub_bagian->update([
            'nama'      => $request->nama,
            'deskripsi' => $request->deskripsi,
            'is_aktif'  => $request->is_aktif ?? 1,
        ]);

        return back()->with('success', 'Sub Bagian berhasil diperbarui.');
    }

    public function destroySubBagian(\App\Models\SubBagian $sub_bagian)
    {
        // abort_if($sub_bagian->arsips()->count() > 0, 403, 'Sub Bagian masih memiliki arsip.');
        $sub_bagian->delete();
        return back()->with('success', 'Sub Bagian berhasil dihapus.');
    }

    // ── Kelola Kode Klasifikasi (Admin) ─────────────────────
    public function klasifikasi(Request $request)
    {
        abort_if(!auth()->user()->isAdmin(), 403, 'Akses ditolak.');

        $klasifikasi = Klasifikasi::get();//withCount('arsips')->
        return view('pengaturan.klasifikasi', compact('klasifikasi'));
    }

    public function storeKlasifikasi(Request $request)
    {
        $request->validate([
            'nama' => 'required|string|max:100|unique:klasifikasi,nama',
        ],[
            'nama.unique' => "Nama sudah digunakan, silakan gunakan nama yang berbeda",
        ]);

        Klasifikasi::create([
            'nama'      => $request->nama,
            'deskripsi' => $request->deskripsi,
            'is_aktif'  => $request->is_aktif ?? 1,
        ]);

        return back()->with('success', 'Kode Klasifikasi berhasil ditambahkan.');
    }

    public function updateKlasifikasi(Request $request, \App\Models\Klasifikasi $klasifikasi)
    {
        $request->validate([
            'nama' => 'required|string|max:100|unique:klasifikasi,nama,' . $klasifikasi->id,
        ],[
            'nama.unique' => "Nama sudah digunakan, silakan gunakan nama yang berbeda",
        ]);

        $klasifikasi->update([
            'nama'      => $request->nama,
            'deskripsi' => $request->deskripsi,
            'is_aktif'  => $request->is_aktif ?? 1,
        ]);

        return back()->with('success', 'Kode Klasifikasi berhasil diperbarui.');
    }

    public function destroyKlasifikasi(Klasifikasi $klasifikasi)
    {
        // abort_if($klasifikasi->arsips()->count() > 0, 403, 'Kode Klasifikasi masih memiliki arsip.');
        $klasifikasi->delete();
        return back()->with('success', 'Kode Klasifikasi berhasil dihapus.');
    }

    // ── Kelola Sifat Surat (Admin) ──────────────────────────
    public function sifatSurat(Request $request)
    {
        abort_if(!auth()->user()->isAdmin(), 403, 'Akses ditolak.');

        $sifatSurat = SifatSurat::get();//withCount('arsips')->
        return view('pengaturan.sifat_surat', compact('sifatSurat'));
    }

    public function storeSifatSurat(Request $request)
    {
        $request->validate([
            'nama' => 'required|string|max:100|unique:sifat_surat,nama',
        ],[
            'nama.unique' => "Nama sudah digunakan, silakan gunakan nama yang berbeda",
        ]);

        SifatSurat::create([
            'nama'      => $request->nama,
            'deskripsi' => $request->deskripsi,
            'is_aktif'  => $request->is_aktif ?? 1,
        ]);

        return back()->with('success', 'Sifat Surat berhasil ditambahkan.');
    }

    public function updateSifatSurat(Request $request, \App\Models\SifatSurat $sifat_surat)
    {
        $request->validate([
            'nama' => 'required|string|max:100|unique:sifat_surat,nama,' . $sifat_surat->id,
        ],[
            'nama.unique' => "Nama sudah digunakan, silakan gunakan nama yang berbeda",
        ]);

        $sifat_surat->update([
            'nama'      => $request->nama,
            'deskripsi' => $request->deskripsi,
            'is_aktif'  => $request->is_aktif ?? 1,
        ]);

        return back()->with('success', 'Sifat Surat berhasil diperbarui.');
    }

    public function destroySifatSurat(\App\Models\SifatSurat $sifat_surat)
    {
       // abort_if($sifat_surat->arsips()->count() > 0, 403, 'Sifat Surat masih memiliki arsip.');
        $sifat_surat->delete();
        return back()->with('success', 'Sifat Surat berhasil dihapus.');
    }
    
    public function verifikator(Request $request)
    {
        abort_if(!auth()->user()->isAdmin(), 403, 'Akses ditolak.');

        $users = User::verifikator()->with('dataVerifikator')->get();

        return view('pengaturan.verifikator', compact('users'));
    }

    public function tujuan(Request $request)
    {
        abort_if(!auth()->user()->isAdmin(), 403, 'Akses ditolak.');

        $tujuan = Tujuan::get();
        return view('pengaturan.tujuan', compact('tujuan'));
    }

    public function storeTujuan(Request $request)
    {
        $request->validate([
            'nama' => 'required|string|max:100|unique:tujuan,nama',
        ],[
            'nama.unique' => "Nama sudah digunakan, silakan gunakan nama yang berbeda",
        ]);

        Tujuan::create([
            'nama'      => $request->nama,
            'deskripsi' => $request->deskripsi,
            'is_aktif'  => $request->is_aktif ?? 1,
        ]);

        return back()->with('success', 'Tujuan berhasil ditambahkan.');
    }

    public function updateTujuan(Request $request, Tujuan $tujuan)
    {
        $request->validate([
            'nama' => 'required|string|max:100|unique:tujuan,nama,' . $tujuan->id,
        ],[
            'nama.unique' => "Nama sudah digunakan, silakan gunakan nama yang berbeda",
        ]);

        Tujuan::update([
            'nama'      => $request->nama,
            'deskripsi' => $request->deskripsi,
            'is_aktif'  => $request->is_aktif ?? 1,
        ]);

        return back()->with('success', 'Tujuan berhasil diperbarui.');
    }

    public function destroyTujuan(Tujuan $tujuan)
    {
       // abort_if($sifat_surat->arsips()->count() > 0, 403, 'Sifat Surat masih memiliki arsip.');
        $tujuan->delete();
        return back()->with('success', 'Tujuan berhasil dihapus.');
    }

}
