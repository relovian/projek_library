<?php

namespace App\Http\Controllers;

use App\Models\ArsipMasuk;
use App\Models\Tujuan;
use App\Models\User;
use App\Services\GoogleDriveService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class ArsipMasukController extends Controller
{

    protected $googleDriveService;

    // Lakukan Dependency Injection melalui Constructor
    public function __construct(GoogleDriveService $googleDriveService)
    {
        $this->googleDriveService = $googleDriveService;
    }

    public function create()
    {
        $users = User::with('divisi')->get();
        $tujuans = \App\Models\Tujuan::where('is_aktif', true)->get();

        return view('arsip_masuk.create', compact('users', 'tujuans'));
    }


    public function edit(ArsipMasuk $arsipMasuk)
    {
        $user = auth()->user();

        // Komisioner tidak bisa edit
        if ($user->role === 'komisioner') {
            abort(403, 'Komisioner tidak dapat mengedit arsip.');
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

        $request->validate([
            'nama_file'         => 'required|max:255',
            'perihal'           => 'required|max:255',
            'asal_instansi'     => 'required|max:255',
            'tanggal_surat'     => 'required|date',
            'tanggal_diterima'  => 'required|date',
            'tanggal_unggah'    => 'required|date',
            'users_disposisi'   => 'required|array|min:1',
            'tujuan_id'         => 'required|exists:tujuan,id'
        ]);

        $arsipMasuk->update([
            'nama_file'        => $request->nama_file,
            'perihal'          => $request->perihal,
            'asal_instansi'    => $request->asal_instansi,
            'tanggal_surat'    => $request->tanggal_surat,
            'tanggal_diterima' => $request->tanggal_diterima,
            'tanggal_unggah'   => $request->tanggal_unggah,
            'tujuan_id'        => $request->tujuan_id,
        ]);

        $arsipMasuk->users()->sync($request->users_disposisi);

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
        $arsip->forceDelete();

        return redirect()->route('arsip-masuk.trash')
            ->with('success', 'Surat masuk berhasil dihapus permanen.');
    }

    public function store(Request $request)
    {
        $request->validate([
            'nama_file'         => 'required|max:255',
            'perihal'           => 'required|max:255',
            'asal_instansi'     => 'required|max:255',
            'tanggal_surat'     => 'required|date',
            'tanggal_diterima'  => 'required|date',
            'tanggal_unggah'    => 'required|date',

            'file' => 'required',
            'link_file' => 'nullable|url',

            'users_disposisi' => 'required|array|min:1',
            'tujuan_id'       => 'required|exists:tujuan,id'
        ]);

        $tanggal = now()->format('Ymd');

        $lastId = (int) ArsipMasuk::latest('id')->value('id');
        $nextId = $lastId ? $lastId + 1 : 1;

        $mod = $nextId % 1000;
        $nextNumber = $mod === 0 ? 1000 : $mod;

        $kodeArsip = $tanggal . '-' . str_pad($nextNumber, 3, '0', STR_PAD_LEFT);


       $linkFile = null;
    $driveId = null;

    if ($request->hasFile('file')) {
            $driveData = $this->googleDriveService->uploadDrive($request->file('file'), '1pKXlQBYIKaySucXPOA9fudfC7u2L_x6y');
            
            // Cek jika file duplikat
        if ($driveData['is_duplicate']) {
            return redirect()->back()
            ->withInput() 
            ->with('error', 'File dengan nama "' . $request->file('file')->getClientOriginalName() . '" sudah ada di sistem.');
        }

        $linkFile = $driveData['link'];
        $driveId  = $driveData['id'];
    }

    

    // Buat record surat
    $surat = ArsipMasuk::create([
        'kode_arsip_masuk' => $kodeArsip,
        'nama_file'        => $request->nama_file,
        'perihal'          => $request->perihal,
        'asal_instansi'    => $request->asal_instansi,
        'tanggal_surat'    => $request->tanggal_surat,
        'tanggal_diterima' => $request->tanggal_diterima,
        'tanggal_unggah'   => $request->tanggal_unggah,
        'link_file'        => $linkFile,
        'drive_id'         => $driveId,
        'uploader_id'      => Auth::id(),
        'tujuan_id'        => $request->tujuan_id,
    ]);

    $surat->users()->sync($request->users_disposisi);

    return redirect()
            ->route('arsip-masuk.create')
            ->with('success', 'Surat berhasil ditambahkan.')
            ->with('kode_arsip', $kodeArsip)
            ->with('drive_link', $linkFile);
    }
}