<?php 

namespace App\Http\Controllers; // Pastikan namespace ini ada jika belum

use Illuminate\Http\Request; // Diperbaiki: Ditambah huruf 'l'
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\File; // Diperbaiki: Menggunakan namespace penuh

class SuratMasukController extends Controller
{
    public function upload() {
        $path = public_path('img/arsip.png'); // Sintaks lebih bersih
        $filename = 'arsip.png';

        // Pastikan driver 'google' sudah dikonfigurasi di config/filesystems.php
        Storage::disk('google')->put($filename, File::get($path));

        return response()->json(['success' => true]); 
    }
}
