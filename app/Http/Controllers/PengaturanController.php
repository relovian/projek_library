<?php

namespace App\Http\Controllers;

use App\Models\Kategori;
use App\Models\Divisi;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class PengaturanController extends Controller
{
    // ── Halaman Utama Pengaturan ──────────────────────────
    public function index()
    {
        return view('pengaturan.index');
    }

    public function storeKategori(Request $request)
    {
        $request->validate([
            'nama' => 'required|string|max:100',
            'kode' => 'required|string|max:50|unique:kategori,kode', // ← kategori bukan kategoris
            'warna'=> 'required|string',
        ]);

        Kategori::create([
            'nama'      => $request->nama,
            'kode'      => strtoupper($request->kode),
            'warna'     => $request->warna,
            'deskripsi' => $request->deskripsi,
            'is_aktif'  => $request->is_aktif ?? 1,
        ]);

        return back()->with('success', 'Kategori berhasil ditambahkan.');
    }

    public function updateKategori(Request $request, Kategori $kategori)
    {
        $request->validate([
            'nama' => 'required|string|max:100',
            'kode' => 'required|string|max:50|unique:kategori,kode,' . $kategori->id, // ← kategori
            'warna'=> 'required|string',
        ]);

        $kategori->update([
            'nama'      => $request->nama,
            'kode'      => strtoupper($request->kode),
            'warna'     => $request->warna,
            'deskripsi' => $request->deskripsi,
            'is_aktif'  => $request->is_aktif ?? 1,
        ]);

        return back()->with('success', 'Kategori berhasil diperbarui.');
    }

    public function destroyKategori(Kategori $kategori)
    {
        $kategori->delete();
        return back()->with('success', 'Kategori berhasil dihapus.');
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
            'nama_lengkap' => 'required|string|max:255',
            'email'        => 'required|email|unique:users,email,' . $user->id,
            'telepon'      => 'nullable|string|max:20',
        ]);

        $user->update($request->only('nama_lengkap', 'email', 'telepon'));

        return back()->with('success', 'Profil berhasil diperbarui.');
    }

    public function updatePassword(Request $request)
    {
        $request->validate([
            'password_lama' => 'required',
            'password_baru' => 'required|min:8|confirmed',
        ]);

        if (!Hash::check($request->password_lama, auth()->user()->password)) {
            return back()->withErrors(['password_lama' => 'Password lama tidak sesuai.']);
        }

        auth()->user()->update(['password' => Hash::make($request->password_baru)]);

        return back()->with('success', 'Password berhasil diubah.');
    }

    public function storeUser(Request $request)
    {
        $request->validate([
            'nama_lengkap' => 'required|string|max:255',
            'email'        => 'required|email|unique:users,email',
            'password'     => 'required|min:8',
            'role'         => 'required|in:admin,staff,pimpinan',
        ]);

        User::create([
            'nama_lengkap' => $request->nama_lengkap,
            'nip'          => $request->nip,
            'email'        => $request->email,
            'password'     => \Hash::make($request->password),
            'role'         => $request->role,
            'divisi_id'    => $request->divisi_id ?: null,
            'is_aktif'     => $request->is_aktif ?? 1,
        ]);

        return back()->with('success', 'User berhasil ditambahkan.');
    }

    public function updateUser(Request $request, User $user)
    {
        $request->validate([
            'nama_lengkap' => 'required|string|max:255',
            'email'        => 'required|email|unique:users,email,' . $user->id,
            'role'         => 'required|in:admin,staff,pimpinan',
        ]);

        $user->update([
            'nama_lengkap' => $request->nama_lengkap,
            'nip'          => $request->nip,
            'email'        => $request->email,
            'role'         => $request->role,
            'divisi_id'    => $request->divisi_id ?: null,
            'is_aktif'     => $request->is_aktif ?? 1,
        ]);

        return back()->with('success', 'User berhasil diperbarui.');
    }

    public function destroyUser(User $user)
    {
        abort_if($user->id === auth()->id(), 403, 'Tidak bisa hapus akun sendiri.');
        $user->delete();
        return back()->with('success', 'User berhasil dihapus.');
    }

    public function storeDivisi(Request $request)
    {
        $request->validate([
            'nama' => 'required|string|max:100',
            'kode' => 'required|string|max:50|unique:divisi,kode',
        ]);

        Divisi::create([
            'nama'      => $request->nama,
            'kode'      => strtoupper($request->kode),
            'deskripsi' => $request->deskripsi,
            'is_aktif'  => $request->is_aktif ?? 1,
        ]);

        return back()->with('success', 'Divisi berhasil ditambahkan.');
    }

    public function updateDivisi(Request $request, Divisi $divisi)
    {
        $request->validate([
            'nama' => 'required|string|max:100',
            'kode' => 'required|string|max:50|unique:divisi,kode,' . $divisi->id,
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

    // // ── Kelola Kategori (Admin) ───────────────────────────
    // public function kategoris()
    // {
    //     $this->authorize('admin-only');
    //     $kategoris = Kategori::withCount('arsips')->get();
    //     return view('pengaturan.kategoris', compact('kategoris'));
    // }

    // // ── Kelola User (Admin) ───────────────────────────────
    // public function users()
    // {
    //     $this->authorize('admin-only');
    //     $users   = User::with('divisi')->paginate(20);
    //     $divisis = Divisi::where('is_aktif', true)->get();
    //     return view('pengaturan.users', compact('users', 'divisis'));
    // }

    // // ── Kelola Divisi (Admin) ─────────────────────────────
    // public function divisis()
    // {
    //     $this->authorize('admin-only');
    //     $divisis = Divisi::withCount('arsips')->get();
    //     return view('pengaturan.divisis', compact('divisis'));
    // }

    public function kategoris()
    {
        // HAPUS: $this->authorize('admin-only');
        abort_if(!auth()->user()->isAdmin(), 403, 'Akses ditolak.');

        $kategoris = Kategori::withCount('arsips')->get();
        return view('pengaturan.kategoris', compact('kategoris'));
    }

    public function users()
    {
        abort_if(!auth()->user()->isAdmin(), 403, 'Akses ditolak.');

        $users   = User::with('divisi')->paginate(20);
        $divisis = Divisi::where('is_aktif', true)->get();
        return view('pengaturan.users', compact('users', 'divisis'));
    }

    public function divisis()
    {
        abort_if(!auth()->user()->isAdmin(), 403, 'Akses ditolak.');

        $divisis = Divisi::withCount('arsips')->get();
        return view('pengaturan.divisis', compact('divisis'));
    }

    public function notifikasi()
    {
        return view('pengaturan.notifikasi');
    }

    public function backup()
    {
        abort_if(!auth()->user()->isAdmin(), 403);
        return view('pengaturan.backup');
    }

    public function backupDatabase()
    {
        abort_if(!auth()->user()->isAdmin(), 403);
        // Jalankan backup database
        $filename = 'backup_db_' . date('Ymd_His') . '.sql';
        $path = storage_path('app/backups/' . $filename);
        
        if (!file_exists(storage_path('app/backups'))) {
            mkdir(storage_path('app/backups'), 0755, true);
        }

        $db = config('database.connections.mysql');
        $command = sprintf(
            'mysqldump -h %s -u %s -p%s %s > %s',
            $db['host'], $db['username'], $db['password'], $db['database'], $path
        );
        exec($command);

        return back()->with('success', "Backup database berhasil disimpan: {$filename}");
    }

    public function backupFiles()
    {
        abort_if(!auth()->user()->isAdmin(), 403);
        return back()->with('success', 'Backup file arsip sedang diproses.');
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
        \App\Models\AktivitasLog::where('created_at', '<', now()->subDays(90))->delete();
        return back()->with('success', 'Log lama berhasil dihapus.');
    }

    public function clearDraft()
    {
        abort_if(!auth()->user()->isAdmin(), 403);
        \App\Models\Arsip::draft()->where('updated_at', '<', now()->subDays(30))->delete();
        return back()->with('success', 'Draft kadaluarsa berhasil dihapus.');
    }

}