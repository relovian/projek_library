<?php

namespace App\Http\Controllers;

use App\Models\SuratMasuk;
use App\Models\User;
use App\Services\GoogleDriveService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class SuratMasukController extends Controller
{
    /**
     * Tampilkan form tambah surat masuk.
     */
    public function create()
    {
        $users = User::where('is_aktif', true)->orderBy('nama_lengkap')->get();
        return view('unggah_surat_masuk.create', compact('users'));
    }

    /**
     * Simpan surat masuk baru + upload file ke Google Drive.
     */
    public function store(Request $request)
    {
        // ── Validasi dasar ────────────────────────────────
        $rules = [
            'nama_file'                => 'required|string|max:255',
            'perihal'                  => 'required|string|max:255',
            'asal_instansi'            => 'required|string|max:255',
            'tanggal_surat'            => 'required|date',
            'tanggal_diterima'         => 'required|date',
            'tanggal_unggah'           => 'required|date',
            'users_disposisi'          => 'required|array',
            'users_disposisi.*'        => 'exists:users,id',
        ];

        $messages = [
            'nama_file.required'             => 'Nama file wajib diisi.',
            'perihal.required'               => 'Perihal wajib diisi.',
            'asal_instansi.required'         => 'Asal instansi wajib diisi.',
            'tanggal_surat.required'         => 'Tanggal surat wajib diisi.',
            'tanggal_surat.date'             => 'Format tanggal surat tidak valid.',
            'tanggal_diterima.required'      => 'Tanggal diterima wajib diisi.',
            'tanggal_diterima.date'          => 'Format tanggal diterima tidak valid.',
            'tanggal_unggah.required'        => 'Tanggal unggah wajib diisi.',
            'tanggal_unggah.date'            => 'Format tanggal unggah tidak valid.',
            'users_disposisi.required'       => 'Pilih minimal satu disposisi/tujuan.',
            'users_disposisi.*.exists'       => 'User disposisi tidak valid.',
        ];

        // Validasi tergantung metode upload
        $metodeUpload = $request->metode_upload ?? 'file';

        if ($metodeUpload === 'file') {
            $rules['file'] = 'required|file|max:51200|mimes:pdf,doc,docx,xls,xlsx,jpg,jpeg,png';
            $messages['file.required']  = 'File surat wajib diunggah.';
            $messages['file.file']      = 'Data yang diunggah harus berupa file.';
            $messages['file.max']       = 'Ukuran file maksimal 50 MB.';
            $messages['file.mimes']     = 'Format file tidak didukung (gunakan: pdf, doc, docx, xls, xlsx, jpg, jpeg, png).';
        } else {
            $rules['link_file'] = 'required|url';
            $messages['link_file.required'] = 'Link Google Drive wajib diisi.';
            $messages['link_file.url']      = 'Format link tidak valid. Masukkan URL yang benar.';
        }

        $request->validate($rules, $messages);

        // ── Upload file ke Google Drive atau pakai link langsung ──
        $driveLink = null;

        if ($metodeUpload === 'file' && $request->hasFile('file')) {
            try {
                $googleDrive = new GoogleDriveService();
                $file = $request->file('file');
                $fileName = time() . '_' . $file->getClientOriginalName();
                $driveLink = $googleDrive->upload($file, $fileName);
            } catch (\Exception $e) {
                return redirect()->back()
                    ->withInput()
                    ->with('error', 'Gagal upload ke Google Drive: ' . $e->getMessage());
            }
        } elseif ($metodeUpload === 'link' && $request->filled('link_file')) {
            try {
                $googleDrive = new GoogleDriveService();
                $driveLink = $googleDrive->copyFromLink($request->link_file);
            } catch (\Exception $e) {
                return redirect()->back()
                    ->withInput()
                    ->with('error', 'Gagal copy file dari link Google Drive: ' . $e->getMessage());
            }
        }

        $tanggalKode = str_replace('-', '', $request->tanggal_unggah);

        // ── Simpan ke Database ──────────────────────────────
        $suratMasuk = null;
        DB::transaction(function () use ($request, $driveLink, $tanggalKode, &$suratMasuk) {
            $suratMasuk = SuratMasuk::create([
                'nama_file'        => $request->nama_file,
                'perihal'          => $request->perihal,
                'asal_instansi'    => $request->asal_instansi,
                'tanggal_surat'    => $request->tanggal_surat,
                'tanggal_diterima' => $request->tanggal_diterima,
                'tanggal_unggah'   => $request->tanggal_unggah,
                'link_file'        => $driveLink,
                'uploader_id'      => auth()->id(),
            ]);

            // Generate kode arsip masuk: YYYYMMDD-NNN (contoh: 20260710-001)
            $urutan = str_pad($suratMasuk->id, 3, '0', STR_PAD_LEFT);
            $kodeArsipMasuk = $tanggalKode . '-' . $urutan;

            $suratMasuk->update(['kode_arsip_masuk' => $kodeArsipMasuk]);

            // Simpan disposisi user
            $userIds = $request->users_disposisi;
            $pivotData = [];
            foreach ($userIds as $userId) {
                $pivotData[$userId] = ['tipe' => 'disposisi'];
            }
            $suratMasuk->users()->sync($pivotData);
        });

        // Redirect dengan data link untuk ditampilkan
        return redirect()->route('surat-masuk.create')
            ->with('success', 'Surat masuk berhasil ditambahkan! File sudah tersimpan di Google Drive.')
            ->with('drive_link', $driveLink)
            ->with('kode_arsip', $suratMasuk->kode_arsip_masuk ?? '');
    }
}