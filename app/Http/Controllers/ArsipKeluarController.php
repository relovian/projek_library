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

        // ── Generate kode arsip ──────────────────────────────
        $klasifikasi = Klasifikasi::findOrFail($request->klasifikasi_id);
        $singkatan = strtoupper(substr($klasifikasi->nama, 0, 2));
        $kodeArsip = ArsipKeluar::generateKode($singkatan, $request->tanggal_unggah);

        // ── Simpan ke Database TERLEBIH DAHULU ──────────────
        $arsipKeluar = null;
        DB::transaction(function () use ($request, $kodeArsip, &$arsipKeluar) {
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
                'uploader_id'       => auth()->id(),
            ]);
        });

        // ── Simpan file secara lokal (jika upload file) ──
        $filePath = null;
        $driveLink = null;

        if ($request->metode_upload === 'file' && $request->hasFile('file')) {
            $file = $request->file('file');
            $fileName = $kodeArsip . '_' . time() . '_' . $file->getClientOriginalName();
            $filePath = $file->storeAs('uploads/arsip_keluar', $fileName, 'public');
            
            // Update link_file dengan path lokal
            $arsipKeluar->link_file = Storage::url($filePath);
            $arsipKeluar->save();
        } elseif ($request->metode_upload === 'link' && $request->filled('link_file')) {
            $arsipKeluar->link_file = $request->link_file;
            $arsipKeluar->save();
        }

        // ── Coba upload ke Google Drive (opsional, tidak memblokir) ──
        try {
            $googleDrive = new GoogleDriveService();

            if ($request->metode_upload === 'file' && $filePath) {
                $fullPath = Storage::disk('public')->path($filePath);
                $file = new \Illuminate\Http\UploadedFile($fullPath, basename($filePath));
                $fileName = $kodeArsip . '_' . time() . '_' . basename($filePath);
                $driveLink = $googleDrive->upload($file, $fileName);
                
                if ($driveLink) {
                    $arsipKeluar->link_file = $driveLink;
                    $arsipKeluar->save();
                }
            } elseif ($request->metode_upload === 'link' && $request->filled('link_file')) {
                $driveLink = $googleDrive->copyFromLink($request->link_file);
                if ($driveLink) {
                    $arsipKeluar->link_file = $driveLink;
                    $arsipKeluar->save();
                }
            }
        } catch (\Exception $e) {
            \Illuminate\Support\Facades\Log::warning('Gagal upload ke Google Drive: ' . $e->getMessage());
        }

        $message = 'Arsip keluar berhasil ditambahkan ke database!';
        if ($driveLink) {
            $message .= ' File sudah tersimpan di Google Drive.';
        } else {
            $message .= ' (Google Drive tidak tersedia, file tersimpan secara lokal)';
        }

        return redirect()->route('arsip-keluar.create')
            ->with('success', $message)
            ->with('drive_link', $driveLink)
            ->with('kode_arsip', $kodeArsip);
    }
}