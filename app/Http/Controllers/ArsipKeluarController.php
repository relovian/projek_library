<?php

namespace App\Http\Controllers;

use App\Models\ArsipKeluar;
use App\Models\Klasifikasi;
use App\Models\SifatSurat;
use App\Models\SubBagian;
use App\Models\Verifikator;
use App\Models\Tujuan;
use App\Models\User;
use App\Services\GoogleDriveService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;

class ArsipKeluarController extends Controller
{
    protected $googleDriveService;
    /**
     * Tampilkan form tambah arsip keluar.
     */

    public function __construct(GoogleDriveService $googleDriveService)
    {
        $this->googleDriveService = $googleDriveService;
    }

    public function create()
    {
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

        $klasifikasi = Klasifikasi::where('is_aktif', true)->get();
        $sifat = SifatSurat::where('is_aktif', true)->get();
        $subBagian  = SubBagian::where('is_aktif', true)->get();
        $verifikator = Verifikator::where('is_aktif', true)->with('user')->get();
        $tujuan = Tujuan::where('is_aktif', true)->get();
        $users = User::where('is_aktif', true)->orderBy('nama_lengkap')->get();

        return view('unggah_keluar.edit', compact(
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

        return redirect()->route('arsip-keluar.trash')
            ->with('success', 'Arsip keluar "' . $arsip->nama_file . '" berhasil dipulihkan.');
    }

    // ── Force Delete (Hapus Permanen) ─────────────────────
    public function forceDelete($id)
    {
        if (auth()->user()->role !== 'admin') {
            abort(403, 'Hanya admin yang dapat menghapus arsip secara permanen.');
        }

        $arsip = ArsipKeluar::onlyTrashed()->findOrFail($id);
        $arsip->forceDelete();

        return redirect()->route('arsip-keluar.trash')
            ->with('success', 'Arsip keluar berhasil dihapus permanen.');
    }

    public function store(Request $request)
    {
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
            'file' => 'required|file',
        ], [
            'nama_file.required' => 'Nama file wajib diisi.',
            'perihal.required' => 'Perihal wajib diisi.',
            'klasifikasi_id.required' => 'Klasifikasi wajib dipilih.',
            'sifat_id.required' => 'Sifat surat wajib dipilih.',
            'sub_bagian_id.required' => 'Sub bagian wajib dipilih.',
            'verifikator_id.required' => 'Verifikator wajib dipilih.',
            'tujuan_id.required' => 'Tujuan wajib dipilih.',
            'pembuat_id.required' => 'Pembuat wajib dipilih.',
            'tanggal_surat.required' => 'Tanggal surat wajib diisi.',
            'tanggal_unggah.required' => 'Tanggal unggah wajib diisi.',
            'file.required_if' => 'File wajib diunggah jika memilih upload file.',
            'file.file' => 'Data yang diunggah harus berupa file.'
        ]);

        // Generate kode arsip 
        $klasifikasi = Klasifikasi::findOrFail($request->klasifikasi_id);
        $singkatan = strtoupper(substr($klasifikasi->nama, 0, 2));
        $kodeArsip = ArsipKeluar::generateKode($singkatan, $request->tanggal_unggah);

        $arsipKeluar = null;
        DB::transaction(function () use ($request, $kodeArsip, &$arsipKeluar) {
            $arsipKeluar = ArsipKeluar::create([
                'kode_arsip_keluar' => $kodeArsip,
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
                'uploader_id' => auth()->id(),
            ]);
        });

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


        return redirect()->route('arsip-keluar.create')
            ->with('success', 'Arsip keluar berhasil ditambahkan ' . ($linkFile ? 'dan file berhasil diupload ke Google Drive.' : '.'))
            ->with('drive_link', $linkFile)
            ->with('kode_arsip', $kodeArsip);
      
    }
}