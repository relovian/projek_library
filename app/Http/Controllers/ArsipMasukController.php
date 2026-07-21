<?php

namespace App\Http\Controllers;

use App\Models\AktivitasLog;
use App\Models\ArsipMasuk;
use App\Models\Tujuan;
use App\Models\User;
use App\Services\GoogleDriveService;
use App\Services\NotifikasiService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class ArsipMasukController extends Controller
{

    protected $googleDriveService;
    protected $notifikasiService;

    // Lakukan Dependency Injection melalui Constructor
    public function __construct(GoogleDriveService $googleDriveService, NotifikasiService $notifikasiService)
    {
        $this->googleDriveService = $googleDriveService;
        $this->notifikasiService = $notifikasiService;
    }

    /**
     * Lihat/preview file arsip masuk (catat aktivitas)
     */
    public function lihat(ArsipMasuk $arsipMasuk)
    {
        // Catat aktivitas melihat dengan user yang sedang login (bukan pemilik file)
        AktivitasLog::create([
            'user_id'         => Auth::id(),
            'arsip_id'        => null,
            'surat_masuk_id'  => $arsipMasuk->id,
            'aksi'            => 'lihat',
            'keterangan'      => "Melihat file arsip masuk: {$arsipMasuk->nama_file}",
            'ip_address'      => request()->ip(),
        ]);

        // Redirect ke link file (Google Drive)
        if ($arsipMasuk->link_file) {
            return redirect()->away($arsipMasuk->link_file);
        }

        return redirect()->back()->with('error', 'File tidak tersedia.');
    }

    public function create()
    {
        if (auth()->user()->role === 'komisioner') {
            abort(403, 'Komisioner tidak dapat mengunggah arsip.');
        }

        $users = User::with('divisi')->get();
        $tujuans = \App\Models\Tujuan::where('is_aktif', true)->get();

        return view('arsip_masuk.create', compact('users', 'tujuans'));
    }

    public function store(Request $request)
    {
        if (auth()->user()->role === 'komisioner') {
            abort(403, 'Komisioner tidak dapat mengunggah arsip.');
        }

        $request->validate([
            'perihal'           => 'required|max:255',
            'asal_instansi'     => 'required|max:255',
            'tanggal_surat'     => 'required|date',
            'tanggal_diterima'  => 'required|date',

            'file' => 'required',
            'link_file' => 'nullable|url',

            'users_disposisi'   => 'required|array|min:1',
            'tujuan_id'         => 'required|array|min:1',
        ]);

        $tanggal = now()->format('Ymd');

        $lastId = (int) ArsipMasuk::latest('id')->value('id');
        $nextId = $lastId ? $lastId + 1 : 1;

        $mod = $nextId % 1000;
        $nextNumber = $mod === 0 ? 1000 : $mod;

        $kodeArsip = $tanggal . '-' . str_pad($nextNumber, 3, '0', STR_PAD_LEFT);


        $linkFile = null;
        $driveId = null;
        $namaFile = null;

        if ($request->hasFile('file')) {
            $file = $request->file('file');
            $namaFile = $file->getClientOriginalName();
            
            $driveData = $this->googleDriveService->uploadDrive($file, '1pKXlQBYIKaySucXPOA9fudfC7u2L_x6y');
            
            // Cek jika file duplikat
            if ($driveData['is_duplicate']) {
                return redirect()->back()
                ->withInput() 
                ->with('error', 'File dengan nama "' . $namaFile . '" sudah ada di sistem.');
            }

            $linkFile = $driveData['link'];
            $driveId  = $driveData['id'];
        }

        

        // Buat record surat
        $surat = ArsipMasuk::create([
            'kode_arsip_masuk' => $kodeArsip,
            'nama_file'        => $namaFile,
            'perihal'          => $request->perihal,
            'asal_instansi'    => $request->asal_instansi,
            'tanggal_surat'    => $request->tanggal_surat,
            'tanggal_diterima' => $request->tanggal_diterima,
            'link_file'        => $linkFile,
            'drive_id'         => $driveId,
            'uploader_id'      => Auth::id(),
            'tujuan_id'        => $request->tujuan_id[0] ?? null, // Ambil tujuan pertama sebagai utama
        ]);

        // Sync tujuan (bisa multiple)
        $surat->tujuans()->sync($request->tujuan_id);

        // Sync disposisi user
        $surat->users()->sync($request->users_disposisi);

        // Catat aktivitas unggah
        AktivitasLog::catat('unggah', null, "Mengunggah arsip masuk: {$surat->nama_file}");

        // Kirim notifikasi ke semua user aktif
        $this->notifikasiService->notifyCreate('ArsipMasuk', $surat, $surat->nama_file);

        return redirect()
                ->route('arsip-masuk.create')
                ->with('success', 'Surat berhasil ditambahkan.')
                ->with('kode_arsip', $kodeArsip)
                ->with('drive_link', $linkFile);
    }


    public function edit(ArsipMasuk $arsipMasuk)
    {
        $user = auth()->user();

        // Komisioner tidak bisa edit
        if ($user->role === 'komisioner') {
            abort(403, 'Komisioner tidak dapat mengedit arsip.');
        }

        // Selain admin: hanya boleh edit arsip miliknya sendiri
        if ($user->role !== 'admin' && $arsipMasuk->uploader_id !== $user->id) {
            abort(403, 'Anda hanya dapat mengedit arsip milik Anda sendiri.');
        }

        $users = User::with('divisi')->get();
        $tujuans = Tujuan::where('is_aktif', true)->get();

        return view('arsip_masuk.edit', compact('arsipMasuk', 'users', 'tujuans'));
    }

    public function update(Request $request, arsipMasuk $arsipMasuk)
    {
        $user = auth()->user();

        // Komisioner tidak bisa edit
        if ($user->role === 'komisioner') {
            abort(403, 'Komisioner tidak dapat mengedit arsip.');
        }

        // Selain admin: hanya boleh edit arsip miliknya sendiri
        if ($user->role !== 'admin' && $arsipMasuk->uploader_id !== $user->id) {
            abort(403, 'Anda hanya dapat mengedit arsip milik Anda sendiri.');
        }

        $request->validate([
            'perihal'           => 'required|max:255',
            'asal_instansi'     => 'required|max:255',
            'tanggal_surat'     => 'required|date',
            'tanggal_diterima'  => 'required|date',

            'file'              => 'nullable|file',
            'link_file'         => 'nullable|url',

            'users_disposisi'   => 'required|array|min:1',
            'tujuan_id'         => 'required|array|min:1',
        ]);

        // Update nama_file jika ada file baru diupload
        $namaFile = $arsipMasuk->nama_file;
        if ($request->hasFile('file')) {
            $file = $request->file('file');
            $namaFile = $file->getClientOriginalName();
            
            $driveData = $this->googleDriveService->uploadDrive($file, '1pKXlQBYIKaySucXPOA9fudfC7u2L_x6y');
            
            if ($driveData['is_duplicate']) {
                return redirect()->back()
                ->withInput() 
                ->with('error', 'File dengan nama "' . $namaFile . '" sudah ada di sistem.');
            }
        }

        $arsipMasuk->update([
            'nama_file'        => $namaFile,
            'perihal'          => $request->perihal,
            'asal_instansi'    => $request->asal_instansi,
            'tanggal_surat'    => $request->tanggal_surat,
            'tanggal_diterima' => $request->tanggal_diterima,
            'tujuan_id'        => $request->tujuan_id[0] ?? null, // Ambil tujuan pertama sebagai utama
        ]);

        // Sync tujuan (bisa multiple)
        $arsipMasuk->tujuans()->sync($request->tujuan_id);

        // Sync disposisi user
        $arsipMasuk->users()->sync($request->users_disposisi);

        // Catat aktivitas edit
        AktivitasLog::catat('edit', null, "Memperbarui arsip masuk: {$arsipMasuk->nama_file}");

        // Kirim notifikasi ke pengelola
        $this->notifikasiService->notifyUpdate('ArsipMasuk', $arsipMasuk, $arsipMasuk->nama_file);

        return redirect()->route('arsip.index', ['tab' => 'masuk'])
            ->with('success', 'Surat masuk berhasil diperbarui.');
    }

    public function destroy(arsipMasuk $arsipMasuk)
    {
        $user = auth()->user();

        // Komisioner tidak bisa hapus
        if ($user->role === 'komisioner') {
            abort(403, 'Komisioner tidak dapat menghapus arsip.');
        }

        // Selain admin: hanya boleh hapus arsip miliknya sendiri
        if ($user->role !== 'admin' && $arsipMasuk->uploader_id !== $user->id) {
            abort(403, 'Anda hanya dapat menghapus arsip milik Anda sendiri.');
        }

        $arsipMasuk->delete(); // soft delete ke trash

        AktivitasLog::create([
            'user_id' => $user->id,
            'arsip_id' => null,
            'surat_masuk_id' => $arsipMasuk->id,
            'aksi' => 'hapus',
            'keterangan' => "Menghapus dokumen: {$arsipMasuk->nama_file}",
            'ip_address' => request()->ip(),
        ]);

        // Kirim notifikasi ke pengelola
        $this->notifikasiService->notifyDelete('ArsipMasuk', $arsipMasuk, $arsipMasuk->nama_file);

        return redirect()->route('arsip.index', ['tab' => 'masuk'])
            ->with('success', 'Surat masuk berhasil dipindahkan ke trash.');
    }

    // ── Trash (Daftar Arsip Masuk Terhapus) ─────────────────
    public function trash()
    {
        $user = auth()->user();

        $query = ArsipMasuk::onlyTrashed()
            ->with(['usersDisposisi', 'tujuan', 'uploader']);

        // Staff/non-admin hanya lihat sampah miliknya sendiri
        if ($user->role !== 'admin') {
            $query->where('uploader_id', $user->id);
        }

        $arsipMasuk = $query->latest('deleted_at')->paginate(15);

        return view('arsip.trash_masuk', compact('arsipMasuk'));
    }

    // ── Restore (Pulihkan dari Trash) ─────────────────────
    public function restore($id)
    {
        if (auth()->user()->role !== 'admin') {
            abort(403, 'Hanya admin yang dapat memulihkan arsip.');
        }

        $arsip = ArsipMasuk::onlyTrashed()->findOrFail($id);
        $arsip->restore();

        // 1) Ubah status log hapus lama milik uploader menjadi pulihkan
        AktivitasLog::where('user_id', $arsip->uploader_id)
            ->where('aksi', 'hapus')
            ->where('arsip_masuk_id', $arsip->id)
            ->update([
                'aksi' => 'pulihkan',
                'keterangan' => "Arsip masuk sudah dipulihkan oleh admin: {$arsip->nama_file}",
            ]);

        // 2) Buat aktivitas baru pulihkan (biar ada aktivitas tambahan)
        AktivitasLog::create([
            'user_id' => $arsip->uploader_id,
            'arsip_id' => null,
            'surat_masuk_id' => $arsip->id,
            'aksi' => 'pulihkan',
            'keterangan' => "Arsip masuk dipulihkan oleh admin: {$arsip->nama_file}",
            'ip_address' => request()->ip(),
        ]);

        $this->notifikasiService->notifyRestore('ArsipMasuk', $arsip, $arsip->nama_file, $arsip->uploader_id);

        return redirect()->route('arsip-masuk.trash')
            ->with('success', 'Surat masuk "' . $arsip->nama_file . '" berhasil dipulihkan.');
    }

    // ── Force Delete (Hapus Permanen) ─────────────────────
    public function forceDelete($id)
    {
        if (auth()->user()->role !== 'admin') {
            abort(403, 'Hanya admin yang dapat menghapus arsip secara permanen.');
        }

        $arsip = ArsipMasuk::onlyTrashed()->findOrFail($id);

        // Catat aktivitas untuk uploader
        $uploaderId = $arsip->uploader_id;

        AktivitasLog::create([
            'user_id'    => $uploaderId,
            'arsip_id'   => null,
            'aksi'       => 'hapus_permanen',
            'keterangan' => "Arsip masuk dihapus permanen oleh admin: {$arsip->nama_file}",
            'ip_address' => request()->ip(),
        ]);
        $this->notifikasiService->notifyForceDelete('ArsipMasuk', $arsip, $arsip->nama_file, $arsip->uploader_id);

        $arsip->forceDelete();

        return redirect()->route('arsip-masuk.trash')
            ->with('success', 'Surat masuk berhasil dihapus permanen.');
    }


}