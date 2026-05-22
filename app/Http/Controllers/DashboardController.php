<?php

namespace App\Http\Controllers;

use App\Models\Arsip;
use App\Models\AktivitasLog;
use App\Models\User;

class DashboardController extends Controller
{
    public function index()
    {
        $user = auth()->user();

        $stats = [
            'total_arsip'    => Arsip::count(),
            //'unggah_bulan'   => Arsip::whereMonth('created_at', now()->month)->count(),
            'menunggu'       => Arsip::menunggu()->count(),
            'user_aktif'     => User::where('is_aktif', true)->count(),
        ];

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
            'stats', 'arsipTerbaru', 'menungguPersetujuan', 'aktivitasSaya'
        ));
    }
}