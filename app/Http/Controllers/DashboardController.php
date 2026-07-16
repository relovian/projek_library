<?php

namespace App\Http\Controllers;

use App\Models\Arsip;
use App\Models\AktivitasLog;
use App\Models\ArsipKeluar;
use App\Models\ArsipMasuk;
use App\Models\Kategori;
use App\Models\Klasifikasi;
use App\Models\SifatSurat;
use App\Models\SubBagian;
use App\Models\Tujuan;
use App\Models\User;
use App\Models\Verifikator;

use Illuminate\Support\Facades\Auth;

class DashboardController extends Controller
{
    public function index()
    {
        $user = Auth::user();

        $stats = [
            'total_arsip_masuk' => ArsipMasuk::count(),
            'total_arsip_keluar' => ArsipKeluar::count(),
            'total_arsip_semua' => ArsipMasuk::count() + ArsipKeluar::count(),
            'total_arsip' => Arsip::count(),
            'menunggu' => Arsip::menunggu()->count(),
            'user_aktif' => User::where('is_aktif', true)->count(),
        ];

        $limitTop = 7;

        // ── Statistik dari ArsipKeluar (tabel yang punya foreign key) ──
        $subbaganStats = SubBagian::where('is_aktif', true)
            ->withCount(['arsipKeluar'])
            ->orderByDesc('arsip_keluar_count')
            ->get(['id', 'nama'])
            ->map(fn($item) => ['label' => $item->nama, 'value' => $item->arsip_keluar_count])
            ->toArray();

        $klasifikasiStats = Klasifikasi::where('is_aktif', true)
            ->withCount(['arsipKeluar'])
            ->orderByDesc('arsip_keluar_count')
            ->get(['id', 'nama'])
            ->map(fn($item) => ['label' => $item->nama, 'value' => $item->arsip_keluar_count])
            ->toArray();

        $sifatStats = SifatSurat::where('is_aktif', true)
            ->withCount(['arsipKeluar'])
            ->orderByDesc('arsip_keluar_count')
            ->get(['id', 'nama'])
            ->map(fn($item) => ['label' => $item->nama, 'value' => $item->arsip_keluar_count])
            ->toArray();

        $verifikatorStats = Verifikator::where('is_aktif', true)
            ->withCount(['arsipKeluar'])
            ->with(['user'])
            ->orderByDesc('arsip_keluar_count')
            ->get()
            ->map(function ($item) {
                $label = $item->user?->nama_lengkap ?? 'Verifikator';
                return ['label' => $label, 'value' => $item->arsip_keluar_count];
            })
            ->toArray();

        $tujuanStats = Tujuan::where('is_aktif', true)
            ->withCount(['arsipKeluar'])
            ->orderByDesc('arsip_keluar_count')
            ->get(['id', 'nama'])
            ->map(fn($item) => ['label' => $item->nama, 'value' => $item->arsip_keluar_count])
            ->toArray();

        // helper untuk top N + lainnya
        $makePieData = function (array $stats, int $topN) {
            $stats = array_values($stats);
            $top   = array_slice($stats, 0, $topN);
            $rest  = array_slice($stats, $topN);
            $lainnya = array_sum(array_map(fn($x) => (int)$x['value'], $rest));
            $labels  = array_map(fn($x) => $x['label'], $top);
            $values  = array_map(fn($x) => (int)$x['value'], $top);
            if ($lainnya > 0) {
                $labels[] = 'Lainnya';
                $values[] = (int)$lainnya;
            }
            return ['labels' => $labels, 'values' => $values];
        };

        $chartData = [
            'sub_bagian'  => $makePieData($subbaganStats, $limitTop),
            'klasifikasi' => $makePieData($klasifikasiStats, $limitTop),
            'sifat'       => $makePieData($sifatStats, $limitTop),
            'verifikator' => $makePieData($verifikatorStats, $limitTop),
            'tujuan'      => $makePieData($tujuanStats, $limitTop),
        ];

        // 5 arsip masuk terbaru
        $arsipMasukTerbaru = ArsipMasuk::with(['uploader'])
            ->latest()
            ->take(5)
            ->get();

        // 5 arsip keluar terbaru
        $arsipKeluarTerbaru = ArsipKeluar::with(['uploader', 'tujuan'])
            ->latest()
            ->take(5)
            ->get();

        return view('dashboard.index', compact(
            'stats', 'chartData', 'arsipMasukTerbaru', 'arsipKeluarTerbaru'
        ));
    }
}