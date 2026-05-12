<?php

namespace App\Http\Controllers;

use App\Models\Arsip;
use App\Models\AktivitasLog;
use Illuminate\Http\Request;

class PersetujuanController extends Controller
{

    // ── Daftar Menunggu Persetujuan ───────────────────────
    public function index(Request $request)
    {
        $query = Arsip::with(['uploader', 'divisi', 'kategori', 'files'])
            ->menunggu();

        if ($request->filled('divisi_id')) {
            $query->where('divisi_id', $request->divisi_id);
        }

        $arsips  = $query->oldest()->paginate(15)->withQueryString();
        $divisis = \App\Models\Divisi::where('is_aktif', true)->get();

        return view('persetujuan.index', compact('arsips', 'divisis'));
    }

    // ── Setujui Satu Arsip ────────────────────────────────
    public function setujui(Arsip $arsip)
    {
        abort_if($arsip->status !== 'menunggu', 422, 'Arsip bukan dalam status menunggu.');

        $arsip->update([
            'status'         => 'disetujui',
            'disetujui_oleh' => auth()->id(),
            'disetujui_at'   => now(),
        ]);

        AktivitasLog::catat('setujui', $arsip->id, "Menyetujui: {$arsip->judul}");

        return back()->with('success', "Arsip \"{$arsip->judul}\" berhasil disetujui.");
    }

    // ── Tolak Arsip ───────────────────────────────────────
    public function tolak(Request $request, Arsip $arsip)
    {
        $request->validate([
            'catatan_penolakan' => 'required|string|max:500',
        ]);

        abort_if($arsip->status !== 'menunggu', 422, 'Arsip bukan dalam status menunggu.');

        $arsip->update([
            'status'             => 'ditolak',
            'catatan_penolakan'  => $request->catatan_penolakan,
            'disetujui_oleh'     => auth()->id(),
            'disetujui_at'       => now(),
        ]);

        AktivitasLog::catat('tolak', $arsip->id, "Menolak: {$arsip->judul}. Alasan: {$request->catatan_penolakan}");

        return back()->with('success', "Arsip \"{$arsip->judul}\" telah ditolak.");
    }

    // ── Setujui Banyak Sekaligus (Bulk) ──────────────────
    public function bulkSetujui(Request $request)
    {
        $request->validate(['ids' => 'required|array', 'ids.*' => 'exists:arsips,id']);

        $arsips = Arsip::whereIn('id', $request->ids)->menunggu()->get();

        foreach ($arsips as $arsip) {
            $arsip->update([
                'status'         => 'disetujui',
                'disetujui_oleh' => auth()->id(),
                'disetujui_at'   => now(),
            ]);
            AktivitasLog::catat('setujui', $arsip->id, "Bulk setujui: {$arsip->judul}");
        }

        return back()->with('success', "{$arsips->count()} arsip berhasil disetujui.");
    }
}