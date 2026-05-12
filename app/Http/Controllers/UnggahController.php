<?php

namespace App\Http\Controllers;

use App\Models\Arsip;
use App\Models\ArsipFile;
use App\Models\AktivitasLog;
use App\Models\Kategori;
use App\Models\Divisi;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;

class UnggahController extends Controller
{
    // ── Form Unggah ──────────────────────────────────────
    public function create()
    {
        $kategoris = Kategori::where('is_aktif', true)->get();
        $divisis   = Divisi::where('is_aktif', true)->get();
        $drafts    = Arsip::draft()->byUser(auth()->id())->latest()->get();

        return view('unggah.create', compact('kategoris', 'divisis', 'drafts'));
    }

    // ── Simpan Arsip ─────────────────────────────────────
    public function store(Request $request)
    {
        $request->validate([
            'judul'           => 'required|string|max:255',
            'kategori_id'     => 'required|exists:kategori,id',  // ← kategori
            'divisi_id'       => 'required|exists:divisi,id',    // ← divisi
            'tanggal_dokumen' => 'required|date',
            'tingkat_akses'   => 'required|in:publik_internal,divisi,pimpinan,rahasia',
            'file'            => 'required|file|max:51200|mimes:pdf,doc,docx,xls,xlsx,jpg,jpeg,png',
            'aksi'            => 'required|in:kirim,draft',
        ]);

        DB::transaction(function () use ($request) {
            $status = $request->aksi === 'draft' ? 'draft' : 'menunggu';

            $arsip = Arsip::create([
                'kode_arsip'      => Arsip::generateKode(),
                'judul'           => $request->judul,
                'deskripsi'       => $request->deskripsi,
                'nomor_surat'     => $request->nomor_surat,
                'kategori_id'     => $request->kategori_id,
                'divisi_id'       => $request->divisi_id,
                'uploader_id'     => auth()->id(),
                'tanggal_dokumen' => $request->tanggal_dokumen,
                'periode_pemilu'  => $request->periode_pemilu,
                'status'          => $status,
                'tingkat_akses'   => $request->tingkat_akses,
                'tags'            => $request->tags,
                'arsip_induk_id'  => $request->arsip_induk_id,
                'versi'           => $request->arsip_induk_id
                    ? Arsip::where('arsip_induk_id', $request->arsip_induk_id)->count() + 2
                    : 1,
            ]);

            // Simpan file
            $file      = $request->file('file');
            $ekstensi  = $file->getClientOriginalExtension();
            $namaSimp  = $arsip->kode_arsip . '_v' . $arsip->versi . '.' . $ekstensi;
            $path = $file->storeAs('arsip/' . date('Y/m'), $namaSimp, 'local');

            ArsipFile::create([
                'arsip_id'   => $arsip->id,
                'nama_asli'  => $file->getClientOriginalName(),
                'nama_simpan'=> $namaSimp,
                'path'       => $path,
                'mime_type'  => $file->getMimeType(),
                'ekstensi'   => $ekstensi,
                'ukuran'     => $file->getSize(),
            ]);

            $aksi = $request->arsip_induk_id ? 'revisi' : 'unggah';
            AktivitasLog::catat($aksi, $arsip->id, "Mengunggah: {$arsip->judul}");
        });

        $pesan = $request->aksi === 'draft'
            ? 'Arsip disimpan sebagai draft.'
            : 'Arsip berhasil dikirim dan menunggu persetujuan.';

        return redirect()->route('arsip.index')->with('success', $pesan);
    }
}