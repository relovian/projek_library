<?php

namespace App\Http\Controllers;

use App\Models\Arsip;
use App\Models\AktivitasLog;
use App\Models\Kategori;
use App\Models\User;

class DashboardController extends Controller
{
    public function index()
    {
        $user = auth()->user();

        $stats = [
            'total_arsip' => Arsip::count(),
            'menunggu'    => Arsip::menunggu()->count(),
            'user_aktif'  => User::where('is_aktif', true)->count(),
        ];

        // Arsip per kategori
        $arsipPerKategori = Kategori::where('is_aktif', true)
            ->withCount('arsips')
            ->orderByDesc('arsips_count')
            ->get();

        // 5 arsip terbaru
        $arsipTerbaru = Arsip::with(['kategori', 'divisi', 'uploader', 'files'])
            ->latest()
            ->take(5)
            ->get();

        // Antrian persetujuan untuk admin/pimpinan
        $menungguPersetujuan = [];
        if ($user->isAdmin() || $user->isPimpinan()) {
            $menungguPersetujuan = Arsip::with(['uploader', 'divisi'])
                ->menunggu()
                ->latest()
                ->take(5)
                ->get();
        }

        // Aktivitas terakhir milik user
        $aktivitasSaya = AktivitasLog::with(['arsip'])
            ->where('user_id', $user->id)
            ->latest()
            ->take(5)
            ->get();

        return view('dashboard.index', compact(
            'stats', 'arsipPerKategori', 'arsipTerbaru', 'menungguPersetujuan', 'aktivitasSaya'
        ));
    }
}