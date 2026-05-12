<?php

namespace App\Http\Controllers;

use App\Models\Arsip;
use App\Models\ArsipFile;
use App\Models\AktivitasLog;
use App\Models\Kategori;
use App\Models\Divisi;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class ArsipController extends Controller
{

    // ── Index (Daftar Arsip) ─────────────────────────────
    public function index(Request $request)
    {
        $query = Arsip::with(['kategori', 'divisi', 'uploader', 'files']);

        // Filter berdasarkan role — staff hanya lihat divisi sendiri dan publik
        $user = auth()->user();
        if ($user->isStaff()) {
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
            $query->where('uploader_id', $user->id);
        }

        $arsips     = $query->latest()->paginate(15)->withQueryString();
        $kategoris  = Kategori::where('is_aktif', true)->get();
        $divisis    = Divisi::where('is_aktif', true)->get();
        $tahunList  = Arsip::selectRaw('YEAR(tanggal_dokumen) as tahun')
                        ->distinct()->orderByDesc('tahun')->pluck('tahun');

        return view('arsip.index', compact('arsips', 'kategoris', 'divisis', 'tahunList'));
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
        $this->authorize('update', $arsip);
        $kategoris = Kategori::where('is_aktif', true)->get();
        $divisis   = Divisi::where('is_aktif', true)->get();
        return view('arsip.edit', compact('arsip', 'kategoris', 'divisis'));
    }

    // ── Update ───────────────────────────────────────────
    public function update(Request $request, Arsip $arsip)
    {
        $this->authorize('update', $arsip);

        $request->validate([
            'judul'          => 'required|string|max:255',
            'kategori_id'    => 'required|exists:kategoris,id',
            'divisi_id'      => 'required|exists:divisis,id',
            'tanggal_dokumen'=> 'required|date',
            'tingkat_akses'  => 'required|in:publik_internal,divisi,pimpinan,rahasia',
        ]);

        $arsip->update($request->only([
            'judul', 'deskripsi', 'nomor_surat', 'kategori_id',
            'divisi_id', 'tanggal_dokumen', 'periode_pemilu',
            'tingkat_akses', 'tags',
        ]));

        

        AktivitasLog::catat('edit', $arsip->id, "Memperbarui metadata: {$arsip->judul}");

        return redirect()->route('arsip.show', $arsip)->with('success', 'Arsip berhasil diperbarui.');
    }

    // ── Destroy ──────────────────────────────────────────
    public function destroy(Arsip $arsip)
    {
        $this->authorize('delete', $arsip);
        AktivitasLog::catat('hapus', $arsip->id, "Menghapus dokumen: {$arsip->judul}");
        $arsip->delete();
        return redirect()->route('arsip.index')->with('success', 'Arsip berhasil dihapus.');
    }
}