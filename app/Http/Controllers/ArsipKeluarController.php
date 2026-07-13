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

class ArsipKeluarController extends Controller
{
    /**
     * Tampilkan form tambah arsip keluar.
     */
    public function create()
    {
        $klasifikasis = Klasifikasi::where('is_aktif', true)->get();
        $sifats       = SifatSurat::where('is_aktif', true)->get();
        $subBagians   = SubBagian::where('is_aktif', true)->get();
        $verifikators = Verifikator::where('is_aktif', true)->with('user')->get();
        $tujuans      = Tujuan::where('is_aktif', true)->get();
        $users        = User::where('is_aktif', true)->orderBy('nama_lengkap')->get();

        return view('unggah_keluar.create', compact(
            'klasifikasis', 'sifats', 'subBagians', 'verifikators', 'tujuans', 'users'
        ));
    }

    /**
     * Simpan arsip keluar baru + upload file ke Google Drive.
     */
    public function store(Request $request)
    {
        $request->validate([
            'nama_file'       => 'required|string|max:255',
            'perihal'         => 'required|string|max:255',
            'klasifikasi_id'  => 'required|exists:klasifikasi,id',
            'sifat_id'        => 'required|exists:sifat_surat,id',
            'sub_bagian_id'   => 'required|exists:sub_bagian,id',
            'verifikator_id'  => 'required|exists:verifikator,id',
            'tujuan_id'       => 'required|exists:tujuan,id',
            'pembuat_id'      => 'required|exists:users,id',
            'tanggal_surat'   => 'required|date',
            'tanggal_unggah'  => 'required|date',
            'metode_upload'   => 'required|in:file,link',
            'file'            => 'required_if:metode_upload,file|file|max:51200|mimes:pdf,doc,docx,xls,xlsx,jpg,jpeg,png',
            'link_file'       => 'required_if:metode_upload,link|url',
        ], [
            'nama_file.required'              => 'Nama file wajib diisi.',
            'perihal.required'                => 'Perihal wajib diisi.',
            'klasifikasi_id.required'         => 'Klasifikasi wajib dipilih.',
            'sifat_id.required'               => 'Sifat surat wajib dipilih.',
            'sub_bagian_id.required'          => 'Sub bagian wajib dipilih.',
            'verifikator_id.required'         => 'Verifikator wajib dipilih.',
            'tujuan_id.required'              => 'Tujuan wajib dipilih.',
            'pembuat_id.required'             => 'Pembuat wajib dipilih.',
            'tanggal_surat.required'          => 'Tanggal surat wajib diisi.',
            'tanggal_unggah.required'         => 'Tanggal unggah wajib diisi.',
            'file.required_if'                => 'File wajib diunggah jika memilih upload file.',
            'file.file'                       => 'Data yang diunggah harus berupa file.',
            'file.max'                        => 'Ukuran file maksimal 50 MB.',
            'file.mimes'                      => 'Format file tidak didukung (gunakan: pdf, doc, docx, xls, xlsx, jpg, jpeg, png).',
            'link_file.required_if'           => 'Link Google Drive wajib diisi.',
            'link_file.url'                   => 'Format link tidak valid.',
        ]);

        // ── Upload file ke Google Drive atau copy dari link ──
        $driveLink = null;
        $googleDrive = new GoogleDriveService();

        if ($request->metode_upload === 'file' && $request->hasFile('file')) {
            try {
                $file = $request->file('file');
                $fileName = time() . '_' . $file->getClientOriginalName();
                $driveLink = $googleDrive->upload($file, $fileName);
            } catch (\Exception $e) {
                return redirect()->back()
                    ->withInput()
                    ->with('error', 'Gagal upload ke Google Drive: ' . $e->getMessage());
            }
        } elseif ($request->metode_upload === 'link' && $request->filled('link_file')) {
            try {
                $driveLink = $googleDrive->copyFromLink($request->link_file);
            } catch (\Exception $e) {
                return redirect()->back()
                    ->withInput()
                    ->with('error', 'Gagal copy file dari link: ' . $e->getMessage());
            }
        }

        // ── Generate kode arsip ──────────────────────────────
        $klasifikasi = Klasifikasi::findOrFail($request->klasifikasi_id);
        // Ambil 2 huruf pertama dari nama klasifikasi sebagai kode
        $singkatan = strtoupper(substr($klasifikasi->nama, 0, 2));
        $kodeArsip = ArsipKeluar::generateKode($singkatan, $request->tanggal_unggah);

        // ── Simpan ke Database ──────────────────────────────
        $arsipKeluar = null;
        DB::transaction(function () use ($request, $driveLink, $kodeArsip, &$arsipKeluar) {
            $arsipKeluar = ArsipKeluar::create([
                'kode_arsip_keluar' => $kodeArsip,
                'nama_file'         => $request->nama_file,
                'perihal'           => $request->perihal,
                'klasifikasi_id'    => $request->klasifikasi_id,
                'sifat_id'          => $request->sifat_id,
                'sub_bagian_id'     => $request->sub_bagian_id,
                'verifikator_id'    => $request->verifikator_id,
                'tujuan_id'         => $request->tujuan_id,
                'pembuat_id'        => $request->pembuat_id,
                'tanggal_surat'     => $request->tanggal_surat,
                'tanggal_unggah'    => $request->tanggal_unggah,
                'link_file'         => $driveLink,
                'uploader_id'       => auth()->id(),
            ]);
        });

        return redirect()->route('arsip-keluar.create')
            ->with('success', 'Arsip keluar berhasil ditambahkan! File sudah tersimpan di Google Drive.')
            ->with('drive_link', $driveLink)
            ->with('kode_arsip', $kodeArsip);
    }
}