<?php

namespace App\Http\Controllers;

use App\Models\AktivitasLog;
use App\Models\Divisi;
use Illuminate\Http\Request;

class AktivitasController extends Controller
{
    public function index(Request $request)
    {
        $user = auth()->user();

        // Komisioner: halaman aktivitas dihilangkan.
        // Jika dipaksa lewat URL, redirect ke dashboard.
        if ($user->isKomisioner()) {
            return redirect()->route('dashboard');
        }

        $query = AktivitasLog::with(['user']);

        // Tab filter
        $tab = $request->get('tab', 'semua');

        // Admin bisa lihat semua log melalui tab "log".
        // Non-admin selalu dibatasi ke aktivitas miliknya sendiri.
        if ($user->isAdmin()) {
            if ($tab === 'unduh') {
                $query->where('user_id', $user->id)->where('aksi', 'unduh');
            } elseif ($tab === 'perubahan') {
                $query->where('user_id', $user->id)->whereIn('aksi', ['edit', 'unggah', 'revisi']);
            } elseif ($tab === 'log') {
                // tampilkan semua — tidak ada filter tambahan
            } else {
                // Admin: tampilkan semua aktivitas dari semua user (termasuk force_delete, restore, dll)
            }
        } else {
            // Non-admin: paksa selalu milik sendiri (tidak peduli tab)
            if ($tab === 'unduh') {
                $query->where('user_id', $user->id)->where('aksi', 'unduh');
            } elseif ($tab === 'perubahan') {
                $query->where('user_id', $user->id)->whereIn('aksi', ['edit', 'unggah', 'revisi']);
            } else {
                $query->where('user_id', $user->id);
            }

            // Buat mencegah kasus query-string tab=log, tetap paksa filter user_id.
            // (Sudah dilakukan pada percabangan else di atas.)
        }

        $logs = $query->latest()->paginate(20)->withQueryString();

        return view('aktivitas.index', compact('logs', 'tab'));
    }
}