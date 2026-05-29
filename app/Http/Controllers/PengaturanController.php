<?php

namespace App\Http\Controllers;

use App\Models\Kategori;
use App\Models\Divisi;
use App\Models\User;
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

    public function storeKategori(Request $request)
    {
        $request->validate([
            'nama' => 'required|string|max:100|unique:kategori,nama',
            'kode' => 'required|string|max:50|unique:kategori,kode', // ← kategori bukan kategoris
            'warna'=> 'required|string',
        ],[
            'nama.unique' => 'Nama telah digunakan, silakan gunakan nama yang berbeda',
            'kode.unique' => 'Kode sudah digunakan, silakan gunakan Kode yang berbeda',
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
            'nama' => 'required|string|max:100|unique:kategori,nama',
            'kode' => 'required|string|max:50|unique:kategori,kode,' . $kategori->id, // ← kategori
            'warna'=> 'required|string',
        ],[
            'nama.unique' => 'Nama telah digunakan, silakan gunakan nama yang berbeda',
            'kode.unique' => 'Kode sudah digunakan, silakan gunakan Kode yang berbeda',
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
            'nama_lengkap' => ['required', 'string', 'max:255'],
            'email'        => ['required', 'string', 'lowercase', 'email', 'max:255', 'unique:'.User::class],
            'password'     => ['required', Rules\Password::min(8)->mixedCase()->numbers()],
            'nip'          => 'required|digits:18|unique:users,nip', 
            'role'         => 'required|in:admin,staff,pimpinan',
        ], [
            'nama_lengkap.required' => 'Nama lengkap wajib diisi.',
            'nama_lengkap.string' => 'Nama harus berupa teks.',
            'nama_lengkap.max' => 'Nama maksimal 255 karakter.',
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
            'nip.required' => 'NIP wajib diisi.',
            'nip.digits' => 'NIP harus terdiri dari tepat 18 angka.',
            'nip.unique' => 'NIP sudah terdaftar di sistem.',
            'role.required' => 'Pilih role pengguna.',
            'role.in' => 'Role yang dipilih tidak valid.',
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

    public function updateUser(Request $request, $id )
    {

        $user = User::find($id);

        if (!$user) {
            return redirect()->route('admin.users')
                ->with('error', 'User dengan ID tersebut tidak ditemukan.');
        }

        $request->validate([
            'nama_lengkap' => 'required|string|max:255',
            'email'        => 'required|email|unique:users,email,' . $user->id,
            'nip'          => [
                'required',
                'string',
                'max:50',
                Rule::unique('users', 'nip')->ignore($user->id),
            ],
            'role'         => 'required|in:admin,staff,pimpinan',
        ], [
            'nip.unique' => 'NIP sudah digunakan. Silakan gunakan NIP yang berbeda.',
            'email.unique' => 'Email sudah digunakan. Silakan gunakan email yang berbeda.',
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

    // ── Kelola Kategori (Admin) ───────────────────────────
    public function kategoris()
    {
        abort_if(!auth()->user()->isAdmin(), 403, 'Akses ditolak.');

        $kategoris = Kategori::withCount('arsips')->get();
        return view('pengaturan.kategoris', compact('kategoris'));
    }

    // ── Kelola User (Admin) ───────────────────────────────
    public function users(Request $request)
    {
        abort_if(!auth()->user()->isAdmin(), 403, 'Akses ditolak.');

        // Cek kalau ada parameter ?edit= tapi user tidak ditemukan
        if ($request->has('edit') && !User::find($request->get('edit'))) {
            return redirect()->route('pengaturan.users')
                ->with('error', 'User dengan ID ' . $request->get('edit') . ' tidak ditemukan.');
        }

        $users   = User::with('divisi')->paginate(8);
        $divisis = Divisi::where('is_aktif', true)->get();
        return view('pengaturan.users', compact('users', 'divisis'));
    }

    // ── Kelola Divisi (Admin) ─────────────────────────────
    public function divisis(Request $request)
    {
        abort_if(!auth()->user()->isAdmin(), 403, 'Akses ditolak.');

        if ($request->has('edit') && !User::find($request->get('edit'))) {
            return redirect()->route('pengaturan.divisis')
            ->with('error', 'Divisi dengan ID ' . $request->get('edit') . ' tidak ditemukan.');
        }

        $divisis = Divisi::withCount('arsips')->get();
        return view('pengaturan.divisis', compact('divisis'));
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