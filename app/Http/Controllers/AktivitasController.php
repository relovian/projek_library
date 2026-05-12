<?php

namespace App\Http\Controllers;

use App\Models\AktivitasLog;
use App\Models\Divisi;
use Illuminate\Http\Request;

class AktivitasController extends Controller
{
    public function index(Request $request)
    {
        $user  = auth()->user();
        $query = AktivitasLog::with(['user', 'arsip']);

        // Tab filter
        $tab = $request->get('tab', 'semua');

        if ($tab === 'unduh') {
            $query->where('user_id', $user->id)->where('aksi', 'unduh');
        } elseif ($tab === 'perubahan') {
            $query->where('user_id', $user->id)->whereIn('aksi', ['edit', 'unggah', 'revisi']);
        } elseif ($tab === 'log' && ($user->isAdmin())) {
            // tampilkan semua — tidak ada filter tambahan
        } else {
            // default: semua aktivitas milik sendiri
            $query->where('user_id', $user->id);
        }

        $logs = $query->latest()->paginate(20)->withQueryString();

        return view('aktivitas.index', compact('logs', 'tab'));
    }
}