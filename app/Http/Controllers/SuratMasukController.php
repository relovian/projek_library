<?php

namespace App\Http\Controllers;

use App\Models\SuratMasuk;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use App\Services\GoogleDriveService;

class SuratMasukController extends Controller
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

        return view('unggah_surat_masuk.create', compact('users'));
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
            'metode_upload'     => 'required|in:file,link',

            'file' => 'required_if:metode_upload,file|nullable|file|max:51200|mimes:pdf,doc,docx,xls,xlsx,jpg,jpeg,png',

            'link_file' => 'required_if:metode_upload,link|nullable|url',

            'users_disposisi' => 'required|array|min:1'
        ]);

        $tanggal = now()->format('Ymd');

        $lastData = SuratMasuk::whereDate('created_at', today())
                        ->latest('id')
                        ->first();

        if ($lastData) {
            $lastNumber = (int) substr($lastData->kode_arsip_masuk, -3);
            $nextNumber = $lastNumber + 1;
        } else {
            $nextNumber = 1;
        }

        $kodeArsip = $tanggal . '-' . str_pad($nextNumber, 3, '0', STR_PAD_LEFT);
        $linkFile = null;

        if ($request->metode_upload == 'file') {

            $path = $request->file('file')->store('surat_masuk', 'public');
            $linkFile = Storage::url($path);

        } else {
            $linkFile = $request->link_file;
        }

        $this->googleDriveService->uploadDrive($request->file('file'));

        $surat = SuratMasuk::create([
            'kode_arsip_masuk' => $kodeArsip,
            'nama_file' => $request->nama_file,
            'perihal' => $request->perihal,
            'asal_instansi' => $request->asal_instansi,
            'tanggal_surat' => $request->tanggal_surat,
            'tanggal_diterima' => $request->tanggal_diterima,
            'tanggal_unggah' => $request->tanggal_unggah,
            'link_file' => $linkFile,
            'drive_id' => null,
            'uploader_id' => Auth::id(),
        ]);

        $surat->users()->sync($request->users_disposisi);

        return redirect()
                ->route('surat-masuk.create')
                ->with('success', 'Surat berhasil ditambahkan.')
                ->with('kode_arsip', $kodeArsip)
                ->with('drive_link', $linkFile);
    }
}