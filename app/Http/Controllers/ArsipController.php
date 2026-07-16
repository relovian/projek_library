<?php

namespace App\Http\Controllers;

use App\Models\Arsip;
use App\Models\ArsipFile;
use App\Models\AktivitasLog;
use App\Models\Kategori;
use App\Models\Divisi;
use App\Models\ArsipMasuk;
use App\Models\ArsipKeluar;
use App\Models\Klasifikasi;
use App\Models\User;
use App\Models\Tujuan;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use App\Models\SifatSurat;
use App\Models\SubBagian;
use App\Models\Verifikator;


class ArsipController extends Controller
{
    // ── Index (Daftar Arsip) ─────────────────────────────
    public function index(Request $request)
    {
        $user = auth()->user();

        // Redirect ke tab "saya" jika tab tidak di-set atau tidak valid
        if (!in_array($request->tab, ['masuk', 'keluar', 'saya'])) {
            if($user->role == 'komisioner') {
                return redirect()->route('arsip.index', ['tab' => 'masuk']);
            }
            return redirect()->route('arsip.index', ['tab' => 'saya']);
        }
        
        // Tab Arsip Masuk
        if ($request->tab === 'masuk') {
            $query = ArsipMasuk::with(['usersDisposisi', 'tujuan']);
            
            // Filter pencarian - cari berdasarkan kode arsip, nama file, perihal, asal instansi
            if ($request->filled('q')) {
                $query->where(function ($q) use ($request) {
                    $q->where('perihal', 'like', '%' . $request->q . '%')
                      ->orWhere('kode_arsip_masuk', 'like', '%' . $request->q . '%')
                      ->orWhere('nama_file', 'like', '%' . $request->q . '%')
                      ->orWhere('asal_instansi', 'like', '%' . $request->q . '%');
                });
            }
            
            if ($request->filled('asal_instansi')) {
                $query->where('asal_instansi', $request->asal_instansi);
            }
            
            // Filter tanggal diterima
            if ($request->filled('tanggal_diterima')) {
                $query->whereDate('tanggal_diterima', $request->tanggal_diterima);
            }
            
            // Filter tanggal unggah
            if ($request->filled('tanggal_unggah')) {
                $query->whereDate('tanggal_unggah', $request->tanggal_unggah);
            }
            
            // Filter disposisi user (multiple)
            if ($request->filled('disposisi_user_id')) {
                $query->whereHas('usersDisposisi', function ($q) use ($request) {
                     $q->whereIn('users.id', (array) $request->disposisi_user_id);
                });
            }
            
            $ArsipMasuk = $query->latest()->paginate(15)->withQueryString();
            $asalInstansiList = ArsipMasuk::select('asal_instansi')->distinct()->orderBy('asal_instansi')->pluck('asal_instansi');
            $tahunList = ArsipMasuk::selectRaw('YEAR(tanggal_surat) as tahun')
                        ->distinct()->orderByDesc('tahun')->pluck('tahun');
            $users = User::where('is_aktif', true)->orderBy('nama_lengkap')->get();
            $tujuan = Tujuan::where('is_aktif', true)->get();
            
            return view('arsip.index', compact('ArsipMasuk', 'asalInstansiList', 'tahunList', 'users', 'tujuan'));
        }
        
        // Tab Arsip Keluar
        if ($request->tab === 'keluar') {
            $query = ArsipKeluar::with([
                'klasifikasi',
                'sifatSurat',
                'subBagian',
                'verifikator.user',
                'tujuan',
                'pembuat',
                'uploader'
            ]);


            // Filter pencarian (q)
            if ($request->filled('q')) {
                $query->where(function ($q) use ($request) {
                    $q->where('kode_arsip_keluar', 'like', '%' . $request->q . '%')
                      ->orWhere('nama_file', 'like', '%' . $request->q . '%')
                      ->orWhere('perihal', 'like', '%' . $request->q . '%');
                });
            }

            // Filter dropdown
            if ($request->filled('tujuan_id')) {
                $query->where('tujuan_id', $request->tujuan_id);
            }

            if ($request->filled('klasifikasi_id')) {
                $query->where('klasifikasi_id', $request->klasifikasi_id);
            }

            if ($request->filled('sifat_id')) {
                $query->where('sifat_id', $request->sifat_id);
            }

            if ($request->filled('sub_bagian_id')) {
                $query->where('sub_bagian_id', $request->sub_bagian_id);
            }

            if ($request->filled('verifikator_id')) {
                $query->where('verifikator_id', $request->verifikator_id);
            }

            if ($request->filled('pembuat_id')) {
                $query->where('pembuat_id', $request->pembuat_id);
            }

            // Filter tanggal
            if ($request->filled('tanggal_surat')) {
                $query->whereDate('tanggal_surat', $request->tanggal_surat);
            }

            if ($request->filled('tanggal_unggah')) {
                $query->whereDate('tanggal_unggah', $request->tanggal_unggah);
            }

            $arsipKeluar = $query->latest()->paginate(15)->withQueryString();

            $klasifikasi = Klasifikasi::where('is_aktif', true)->get();
            $sifat = SifatSurat::where('is_aktif', true)->get();
            $subBagian = SubBagian::where('is_aktif', true)->get();
            $verifikator = Verifikator::where('is_aktif', true)->with('user')->get();
            $tujuan = Tujuan::where('is_aktif', true)->get();
            $users = User::where('is_aktif', true)->orderBy('nama_lengkap')->get();

            return view('arsip.index', compact(
                'arsipKeluar',
                'klasifikasi',
                'sifat',
                'subBagian',
                'verifikator',
                'tujuan',
                'users'
            ));
        }



        // Tab Arsip Saya
        $query = Arsip::with(['kategori', 'divisi', 'uploader', 'files']);

        // Filter berdasarkan role — staff hanya lihat divisi sendiri dan publik
        if ($user->role === 'staff') {
            $query->where(function ($q) use ($user) {
                $q->where('divisi_id', $user->divisi_id)
                  ->orWhere('tingkat_akses', 'publik_internal');
            });
        }

        // Filter pencarian
        if ($request->filled('q')) {
            $query->where(function ($q) use ($request) {
                $q->where('judul', 'like', '%' . $request->q . '%')
                  ->orWhere('kode_arsip', 'like', '%' . $request->q . '%')
                  ->orWhere('nomor_surat', 'like', '%' . $request->q . '%');
            });
        }

        if ($request->filled('kategori_id')) {
            $query->where('kategori_id', $request->kategori_id);
        }

        if ($request->filled('divisi_id')) {
            $query->where('divisi_id', $request->divisi_id);
        }

        if ($request->filled('tahun')) {
            $query->whereYear('tanggal_dokumen', $request->tahun);
        }

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        // Tab khusus "arsip saya"
        if ($request->tab === 'saya') {
            // Jika user memilih filter "Arsip Masuk" di dropdown
            if ($request->arsip_id === 'arsip_masuk') {
                $queryMasuk = ArsipMasuk::with(['usersDisposisi', 'tujuan']);

                if ($request->filled('q')) {
                    $queryMasuk->where(function ($q) use ($request) {
                        $q->where('perihal', 'like', '%' . $request->q . '%')
                          ->orWhere('kode_arsip_masuk', 'like', '%' . $request->q . '%')
                          ->orWhere('nama_file', 'like', '%' . $request->q . '%')
                          ->orWhere('asal_instansi', 'like', '%' . $request->q . '%');
                    });
                }

                $ArsipMasuk = $queryMasuk->latest()->paginate(15)->withQueryString();
                $asalInstansiList = ArsipMasuk::select('asal_instansi')->distinct()->orderBy('asal_instansi')->pluck('asal_instansi');
                $tahunList = ArsipMasuk::selectRaw('YEAR(tanggal_surat) as tahun')
                            ->distinct()->orderByDesc('tahun')->pluck('tahun');
                $users = User::where('is_aktif', true)->orderBy('nama_lengkap')->get();
                $tujuan = Tujuan::where('is_aktif', true)->get();

                return view('arsip.index', compact('ArsipMasuk', 'asalInstansiList', 'tahunList', 'users', 'tujuan'));
            }

            // Jika user memilih filter "Arsip Keluar" di dropdown
            if ($request->arsip_id === 'arsip_keluar') {
                $queryKeluar = ArsipKeluar::with([
                    'klasifikasi',
                    'sifatSurat',
                    'subBagian',
                    'verifikator.user',
                    'tujuan',
                    'pembuat',
                    'uploader'
                ]);

                if ($request->filled('q')) {
                    $queryKeluar->where(function ($q) use ($request) {
                        $q->where('kode_arsip_keluar', 'like', '%' . $request->q . '%')
                          ->orWhere('nama_file', 'like', '%' . $request->q . '%')
                          ->orWhere('perihal', 'like', '%' . $request->q . '%');
                    });
                }

                if ($request->filled('tujuan_id')) {
                    $queryKeluar->where('tujuan_id', $request->tujuan_id);
                }

                if ($request->filled('klasifikasi_id')) {
                    $queryKeluar->where('klasifikasi_id', $request->klasifikasi_id);
                }

                if ($request->filled('sifat_id')) {
                    $queryKeluar->where('sifat_id', $request->sifat_id);
                }

                if ($request->filled('sub_bagian_id')) {
                    $queryKeluar->where('sub_bagian_id', $request->sub_bagian_id);
                }

                if ($request->filled('verifikator_id')) {
                    $queryKeluar->where('verifikator_id', $request->verifikator_id);
                }

                if ($request->filled('pembuat_id')) {
                    $queryKeluar->where('pembuat_id', $request->pembuat_id);
                }

                if ($request->filled('tanggal_surat')) {
                    $queryKeluar->whereDate('tanggal_surat', $request->tanggal_surat);
                }

                if ($request->filled('tanggal_unggah')) {
                    $queryKeluar->whereDate('tanggal_unggah', $request->tanggal_unggah);
                }

                $arsipKeluar = $queryKeluar->latest()->paginate(15)->withQueryString();

                $klasifikasi = Klasifikasi::where('is_aktif', true)->get();
                $sifat = SifatSurat::where('is_aktif', true)->get();
                $subBagian = SubBagian::where('is_aktif', true)->get();
                $verifikator = Verifikator::where('is_aktif', true)->with('user')->get();
                $tujuan = Tujuan::where('is_aktif', true)->get();
                $users = User::where('is_aktif', true)->orderBy('nama_lengkap')->get();

                return view('arsip.index', compact(
                    'arsipKeluar',
                    'klasifikasi',
                    'sifat',
                    'subBagian',
                    'verifikator',
                    'tujuan',
                    'users'
                ));
            }

            // Default: tampilkan arsip milik user (Arsip Saya tanpa filter spesifik)
            $query->where('uploader_id', $user->id);
        }

        $arsip = $query->latest()->paginate(15)->withQueryString();
        $kategori = Kategori::where('is_aktif', true)->get();
        $divisi = Divisi::where('is_aktif', true)->get();
        $tahunList = Arsip::selectRaw('YEAR(tanggal_dokumen) as tahun')
                    ->distinct()->orderByDesc('tahun')->pluck('tahun');

        return view('arsip.index', compact('arsip', 'kategori', 'divisi', 'tahunList'));
    }

    // ── Show (Detail Arsip) ──────────────────────────────
    public function show(Arsip $arsip)
    {
        $arsip->load(['kategori', 'divisi', 'uploader', 'penyetuju', 'files', 'revisis.files', 'arsipInduk']);
        AktivitasLog::catat('lihat', $arsip->id, "Melihat dokumen: {$arsip->judul}");
        return view('arsip.show', compact('arsip'));
    }

    // ── Download ─────────────────────────────────────────
    public function download(Arsip $arsip)
    {
        $file = $arsip->files()->firstOrFail();
        AktivitasLog::catat('unduh', $arsip->id, "Mengunduh dokumen: {$arsip->judul}");
        return Storage::download($file->path, $file->nama_asli);
    }

    // ── Edit (Form) ──────────────────────────────────────
    public function edit(Arsip $arsip)
    {
        $user = auth()->user();

        if ($user->role === 'komisioner') {
            abort(403, 'Komisioner tidak dapat mengedit arsip.');
        }

        if ($user->role !== 'admin' && $user->id !== $arsip->uploader_id) {
            abort(403, 'Anda tidak memiliki akses untuk mengedit arsip ini.');
        }

        $kategori = Kategori::where('is_aktif', true)->get();
        $divisi  = Divisi::where('is_aktif', true)->get();
        return view('arsip.edit', compact('arsip', 'kategori', 'divisi'));
    }

    // ── Update ───────────────────────────────────────────
    public function update(Request $request, Arsip $arsip)
    {
        $user = auth()->user();

        if ($user->role === 'komisioner') {
            abort(403, 'Komisioner tidak dapat mengedit arsip.');
        }

        if ($user->role !== 'admin' && $user->id !== $arsip->uploader_id) {
            abort(403, 'Anda tidak memiliki akses untuk mengedit arsip ini.');
        }

        $request->validate([
            'judul'           => 'required|string|max:255',
            'kategori_id'     => 'required|exists:kategori,id',
            'divisi_id'       => 'required|exists:divisi,id',
            'tanggal_dokumen' => 'required|date',
            'tingkat_akses'   => 'required|in:publik_internal,divisi,pimpinan,rahasia',
        ]);

        $arsip->update($request->only([
            'judul', 'deskripsi', 'nomor_surat', 'kategori_id',
            'divisi_id', 'tanggal_dokumen', 'periode_pemilu',
            'tingkat_akses', 'tags',
        ]));

        AktivitasLog::catat('edit', $arsip->id, "Memperbarui metadata: {$arsip->judul}");

        return redirect()->route('arsip.show', $arsip)->with('success', 'Arsip berhasil diperbarui.');
    }

    // ── Destroy (Soft Delete → Trash) ────────────────────
    public function destroy(Arsip $arsip)
    {
        $user = auth()->user();

        // Komisioner: tidak boleh menghapus
        if ($user->role === 'komisioner') {
            abort(403, 'Komisioner tidak dapat menghapus arsip.');
        }

        // Selain admin: hanya boleh hapus arsip miliknya sendiri (uploader_id)
        if ($user->role !== 'admin' && $arsip->uploader_id !== $user->id) {
            abort(403, 'Anda hanya dapat menghapus arsip milik Anda sendiri.');
        }

        AktivitasLog::catat('hapus', $arsip->id, "Menghapus dokumen: {$arsip->judul}");
        $arsip->delete(); // soft delete ke trash

        return redirect()->route('arsip.index', ['tab' => 'saya'])->with('success', 'Arsip berhasil dipindahkan ke trash.');
    }


    // ── Trash (Daftar Arsip Terhapus) ────────────────────
    public function trash()
    {
        $user = auth()->user();

        $query = Arsip::onlyTrashed()
            ->with(['kategori', 'divisi', 'uploader']);

        // Staff hanya lihat sampah miliknya sendiri
        if ($user->role !== 'admin') {
            $query->where('uploader_id', $user->id);
        }

        $arsip = $query->latest('deleted_at')->paginate(15);

        return view('arsip.trash', compact('arsip'));
    }

    // ── Restore (Pulihkan dari Trash) ─────────────────────
    public function restore($id)
    {
        if (auth()->user()->role !== 'admin') {
            abort(403, 'Hanya admin yang dapat memulihkan arsip.');
        }

        $arsip = Arsip::onlyTrashed()->findOrFail($id);
        $arsip->restore();

        AktivitasLog::catat('pulihkan', $arsip->id, "Memulihkan dokumen: {$arsip->judul}");

        return redirect()->route('arsip.trash')
            ->with('success', 'Arsip "' . $arsip->judul . '" berhasil dipulihkan.');
    }

    // ── Force Delete (Hapus Permanen) ─────────────────────
    public function forceDelete($id)
    {
        if (auth()->user()->role !== 'admin') {
            abort(403, 'Hanya admin yang dapat menghapus arsip secara permanen.');
        }

        $arsip = Arsip::onlyTrashed()->findOrFail($id);

        foreach ($arsip->files as $file) {
            if (Storage::exists($file->path)) {
                Storage::delete($file->path);
            }
        }

        AktivitasLog::catat('hapus_permanen', $arsip->id, "Menghapus permanen: {$arsip->judul}");
        $arsip->forceDelete();

        return redirect()->route('arsip.trash')
            ->with('success', 'Arsip "' . $arsip->judul . '" berhasil dihapus permanen.');
    }

    // ── Empty Trash (Kosongkan Semua Trash) ───────────────
    public function emptyTrash()
    {
        if (auth()->user()->role !== 'admin') {
            abort(403, 'Hanya admin yang dapat mengosongkan trash.');
        }

        $trashed = Arsip::onlyTrashed()->with('files')->get();

        foreach ($trashed as $arsip) {
            foreach ($arsip->files as $file) {
                if (Storage::exists($file->path)) {
                    Storage::delete($file->path);
                }
            }
            AktivitasLog::catat('hapus_permanen', $arsip->id, "Menghapus permanen: {$arsip->judul}");
            $arsip->forceDelete();
        }

        return redirect()->route('arsip.trash')
            ->with('success', 'Semua arsip di trash berhasil dihapus permanen.');
    }
}