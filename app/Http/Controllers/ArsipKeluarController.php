<?php

namespace App\Http\Controllers;

use App\Models\AktivitasLog;
use App\Models\ArsipKeluar;
use App\Models\Klasifikasi;
use App\Models\SifatSurat;
use App\Models\SubBagian;
use App\Models\Tujuan;
use App\Models\User;
use App\Models\Verifikator;
use App\Services\GoogleDriveService;
use App\Services\NotifikasiService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;

class ArsipKeluarController extends Controller
{
    protected $googleDriveService;
    protected $notifikasiService;
    /**
     * Tampilkan form tambah arsip keluar.
     */

    public function __construct(GoogleDriveService $googleDriveService, NotifikasiService $notifikasiService)
    {
        $this->googleDriveService = $googleDriveService;
        $this->notifikasiService = $notifikasiService;
    }

    /**
     * Lihat/preview file arsip keluar (catat aktivitas)
     */
    public function lihat(ArsipKeluar $arsipKeluar)
    {
        // Catat aktivitas melihat dengan user yang sedang login (bukan pemilik file)
        AktivitasLog::create([
            'user_id'          => Auth::id(),
            'arsip_id'         => null,
            'arsip_keluar_id'  => $arsipKeluar->id,
            'aksi'             => 'lihat',
            'keterangan'       => "Melihat file arsip keluar: {$arsipKeluar->nama_file}",
            'ip_address'       => request()->ip(),
        ]);

        // Redirect ke link file (Google Drive)
        if ($arsipKeluar->link_file) {
            return redirect()->away($arsipKeluar->link_file);
        }

        return redirect()->back()->with('error', 'File tidak tersedia.');
    }

    public function create()
    {
        if (auth()->user()->role === 'komisioner') {
            abort(403, 'Komisioner tidak dapat mengunggah arsip.');
        }

        $klasifikasi = Klasifikasi::where('is_aktif', true)->get();
        $sifat = SifatSurat::where('is_aktif', true)->get();
        $subBagian = SubBagian::where('is_aktif', true)->get();
        $verifikator = Verifikator::where('is_aktif', true)->with('user')->get();
        $tujuan = Tujuan::where('is_aktif', true)->get();
        $users = User::where('is_aktif', true)->orderBy('nama_lengkap')->get();

        return view('arsip_keluar.create', compact(
            'klasifikasi', 'sifat', 'subBagian', 'verifikator', 'tujuan', 'users'
        ));
    }

    public function edit(ArsipKeluar $arsipKeluar)
    {
        $user = auth()->user();

        // Komisioner tidak bisa edit
        if ($user->role === 'komisioner') {
            abort(403, 'Komisioner tidak dapat mengedit arsip.');
        }

        // Selain admin: hanya boleh edit arsip miliknya sendiri
        if ($user->role !== 'admin' && $arsipKeluar->uploader_id !== $user->id) {
            abort(403, 'Anda hanya dapat mengedit arsip milik Anda sendiri.');
        }

        $klasifikasi = Klasifikasi::where('is_aktif', true)->get();
        $sifat = SifatSurat::where('is_aktif', true)->get();
        $subBagian  = SubBagian::where('is_aktif', true)->get();
        $verifikator = Verifikator::where('is_aktif', true)->with('user')->get();
        $tujuan = Tujuan::where('is_aktif', true)->get();
        $users = User::where('is_aktif', true)->orderBy('nama_lengkap')->get();

        return view('arsip_keluar.edit', compact(
            'arsipKeluar', 'klasifikasi', 'sifat', 'subBagian', 'verifikator', 'tujuan', 'users'
        ));
    }

    public function update(Request $request, ArsipKeluar $arsipKeluar)
    {
        $user = auth()->user();

        // Komisioner tidak bisa edit
        if ($user->role === 'komisioner') {
            abort(403, 'Komisioner tidak dapat mengedit arsip.');
        }

        // Selain admin: hanya boleh edit arsip miliknya sendiri
        if ($user->role !== 'admin' && $arsipKeluar->uploader_id !== $user->id) {
            abort(403, 'Anda hanya dapat mengedit arsip milik Anda sendiri.');
        }

        $request->validate([
            'nama_file' => 'required|string|max:255',
            'perihal' => 'required|string|max:255',
            'klasifikasi_id' => 'required|exists:klasifikasi,id',
            'sifat_id' => 'required|exists:sifat_surat,id',
            'sub_bagian_id' => 'required|exists:sub_bagian,id',
            'verifikator_id' => 'required|exists:verifikator,id',
            'tujuan_id' => 'required|exists:tujuan,id',
            'pembuat_id' => 'required|exists:users,id',
            'tanggal_surat' => 'required|date',
            'tanggal_unggah' => 'required|date',
        ]);

        $arsipKeluar->update([
            'nama_file' => $request->nama_file,
            'perihal' => $request->perihal,
            'klasifikasi_id' => $request->klasifikasi_id,
            'sifat_id' => $request->sifat_id,
            'sub_bagian_id' => $request->sub_bagian_id,
            'verifikator_id' => $request->verifikator_id,
            'tujuan_id' => $request->tujuan_id,
            'pembuat_id' => $request->pembuat_id,
            'tanggal_surat' => $request->tanggal_surat,
            'tanggal_unggah' => $request->tanggal_unggah,
        ]);

        // Catat aktivitas edit
        AktivitasLog::catat('edit', null, "Memperbarui arsip keluar: {$arsipKeluar->nama_file}");

        // Kirim notifikasi ke pengelola
        $this->notifikasiService->notifyUpdate('ArsipKeluar', $arsipKeluar, $arsipKeluar->nama_file);

        return redirect()->route('arsip.index', ['tab' => 'keluar'])
            ->with('success', 'Arsip keluar berhasil diperbarui.');
    }

    public function destroy(ArsipKeluar $arsipKeluar)
    {
        $user = auth()->user();

        // Komisioner tidak bisa hapus
        if ($user->role === 'komisioner') {
            abort(403, 'Komisioner tidak dapat menghapus arsip.');
        }

        // Selain admin: hanya boleh hapus arsip miliknya sendiri
        if ($user->role !== 'admin' && $arsipKeluar->uploader_id !== $user->id) {
            abort(403, 'Anda hanya dapat menghapus arsip milik Anda sendiri.');
        }

        $arsipKeluar->delete(); // soft delete ke trash

        AktivitasLog::create([
            'user_id' => $user->id,
            'arsip_id' => null,
            'arsip_keluar_id' => $arsipKeluar->id,
            'aksi' => 'hapus',
            'keterangan' => "Menghapus arsip keluar: {$arsipKeluar->nama_file}",
            'ip_address' => request()->ip(),
        ]);

        // Kirim notifikasi ke pengelola
        $this->notifikasiService->notifyDelete('ArsipKeluar', $arsipKeluar, $arsipKeluar->nama_file);

        return redirect()->route('arsip.index', ['tab' => 'keluar'])
            ->with('success', 'Arsip keluar berhasil dipindahkan ke trash.');
    }

    // ── Trash (Daftar Arsip Keluar Terhapus) ─────────────────
    public function trash()
    {
        $user = auth()->user();

        $query = ArsipKeluar::onlyTrashed()
            ->with(['klasifikasi', 'sifatSurat', 'subBagian', 'verifikator.user', 'tujuan', 'pembuat', 'uploader']);

        // Staff/non-admin hanya lihat sampah miliknya sendiri
        if ($user->role !== 'admin') {
            $query->where('uploader_id', $user->id);
        }

        $arsipKeluar = $query->latest('deleted_at')->paginate(15);

        return view('arsip.trash_keluar', compact('arsipKeluar'));
    }

    // ── Restore (Pulihkan dari Trash) ─────────────────────
    public function restore($id)
    {
        if (auth()->user()->role !== 'admin') {
            abort(403, 'Hanya admin yang dapat memulihkan arsip.');
        }

        $arsip = ArsipKeluar::onlyTrashed()->findOrFail($id);
        $arsip->restore();

        // 1) Ubah status log hapus lama milik uploader menjadi pulihkan
        AktivitasLog::where('user_id', $arsip->uploader_id)
            ->where('aksi', 'hapus')
            ->where('arsip_keluar_id', $arsip->id)
            ->update([
                'aksi' => 'pulihkan',
                'keterangan' => "Arsip keluar sudah dipulihkan oleh admin: {$arsip->nama_file}",
            ]);

        // 2) Buat aktivitas baru pulihkan (biar ada aktivitas tambahan)
        AktivitasLog::create([
            'user_id' => $arsip->uploader_id,
            'arsip_id' => null,
            'arsip_keluar_id' => $arsip->id,
            'aksi' => 'pulihkan',
            'keterangan' => "Arsip keluar dipulihkan oleh admin: {$arsip->nama_file}",
            'ip_address' => request()->ip(),
        ]);

        $this->notifikasiService->notifyRestore('ArsipKeluar', $arsip, $arsip->nama_file, $arsip->uploader_id);

        return redirect()->route('arsip-keluar.trash')
            ->with('success', 'Arsip keluar "' . $arsip->nama_file . '" berhasil dipulihkan.');
    }

    // ── Force Delete (Hapus Permanen) 
    public function forceDelete($id)
    {
        if (auth()->user()->role !== 'admin') {
            abort(403, 'Hanya admin yang dapat menghapus arsip secara permanen.');
        }

        $arsip = ArsipKeluar::onlyTrashed()->findOrFail($id);

        // Catat aktivitas untuk uploader
        $uploaderId = $arsip->uploader_id;

        AktivitasLog::create([
            'user_id'    => $uploaderId,
            'arsip_id'   => null,
            'aksi'       => 'hapus_permanen',
            'keterangan' => "Arsip keluar dihapus permanen oleh admin: {$arsip->nama_file}",
            'ip_address' => request()->ip(),
        ]);

        $this->notifikasiService->notifyForceDelete('ArsipKeluar', $arsip, $arsip->nama_file, $arsip->uploader_id);

        $arsip->forceDelete();

        return redirect()->route('arsip-keluar.trash')
            ->with('success', 'Arsip keluar berhasil dihapus permanen.');
    }

    public function store(Request $request)
    {
        if (auth()->user()->role === 'komisioner') {
            abort(403, 'Komisioner tidak dapat mengunggah arsip.');
        }

        $request->validate([
            'nama_file' => 'required|string|max:255',
            'perihal' => 'required|string|max:255',
            'klasifikasi_id' => 'required|exists:klasifikasi,id',
            'sifat_id' => 'required|exists:sifat_surat,id',
            'sub_bagian_id' => 'required|exists:sub_bagian,id',
            'verifikator_id' => 'required|exists:verifikator,id',
            'pembuat_id' => 'required|exists:users,id',
            'tanggal_surat' => 'required|date',
            'file' => 'required|file',
        ], [
            'nama_file.required' => 'Nama file wajib diisi.',
            'perihal.required' => 'Perihal wajib diisi.',
            'klasifikasi_id.required' => 'Klasifikasi wajib dipilih.',
            'sifat_id.required' => 'Sifat surat wajib dipilih.',
            'sub_bagian_id.required' => 'Sub bagian wajib dipilih.',
            'verifikator_id.required' => 'Verifikator wajib dipilih.',
            'pembuat_id.required' => 'Pembuat wajib dipilih.',
            'tanggal_surat.required' => 'Tanggal surat wajib diisi.',
            'file.required_if' => 'File wajib diunggah jika memilih upload file.',
            'file.file' => 'Data yang diunggah harus berupa file.'
        ]);

        // Generate kode arsip 
        $klasifikasi = Klasifikasi::findOrFail($request->klasifikasi_id);
        $singkatan = strtoupper(substr($klasifikasi->nama, 0, 2));
        $kodeArsip = ArsipKeluar::generateKode($singkatan, now()->format('Y-m-d'));

        $arsipKeluar = null;

        $linkFile = null;
        $driveId = null;

        if ($request->hasFile('file')) {
            $driveData = $this->googleDriveService->uploadDrive($request->file('file'), '1DgN_Ndgxx7yz6FUuPn3yzO5HtEPGTJ6Y');
            
            // Cek jika file duplikat
            if ($driveData['is_duplicate']) {
                return redirect()->back()
                ->withInput() 
                ->with('error', 'File dengan nama "' . $request->file('file')->getClientOriginalName() . '" sudah ada di sistem.');
            }

            $linkFile = $driveData['link'];
            $driveId  = $driveData['id'];
        }

        $arsipKeluar = ArsipKeluar::create([
            'kode_arsip_keluar' => $kodeArsip,
            'nama_file' => $request->nama_file,
            'perihal' => $request->perihal,
            'klasifikasi_id' => $request->klasifikasi_id,
            'sifat_id' => $request->sifat_id,
            'sub_bagian_id' => $request->sub_bagian_id,
            'verifikator_id' => $request->verifikator_id,
            'pembuat_id' => $request->pembuat_id,
            'tanggal_surat' => $request->tanggal_surat,
            'tanggal_unggah' => now(),
            'link_file' => $linkFile,
            'uploader_id' => auth()->id(),
        ]);

        // Catat aktivitas unggah
        AktivitasLog::catat('unggah', null, "Mengunggah arsip keluar: {$arsipKeluar->nama_file}");

        // Kirim notifikasi ke semua user aktif
        $this->notifikasiService->notifyCreate('ArsipKeluar', $arsipKeluar, $arsipKeluar->nama_file);

        return redirect()->route('arsip-keluar.create')
            ->with('success', 'Arsip keluar berhasil ditambahkan ' . ($linkFile ? 'dan file berhasil diupload ke Google Drive.' : '.'))
            ->with('drive_link', $linkFile)
            ->with('kode_arsip', $kodeArsip);
      
    }
}