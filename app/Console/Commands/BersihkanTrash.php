<?php

namespace App\Console\Commands;

use App\Models\Arsip;
use App\Models\AktivitasLog;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Storage;

class BersihkanTrash extends Command
{
    protected $signature = 'trash:bersihkan {--hari=7 : Hapus arsip yang sudah di trash lebih dari X hari}';
    protected $description = 'Hapus permanen arsip di trash yang sudah melebihi batas hari';

    public function handle(): void
    {
        $hari    = (int) $this->option('hari');
        $batas   = now()->subDays($hari);
        $trashed = Arsip::onlyTrashed()
                        ->with('files')
                        ->where('deleted_at', '<', $batas)
                        ->get();

        if ($trashed->isEmpty()) {
            $this->info("Tidak ada arsip di trash yang lebih dari {$hari} hari.");
            return;
        }

        $this->info("Ditemukan {$trashed->count()} arsip yang akan dihapus permanen...");

        $berhasil = 0;
        $gagal    = 0;

        foreach ($trashed as $arsip) {
            try {
                foreach ($arsip->files as $file) {
                    if (Storage::exists($file->path)) {
                        Storage::delete($file->path);
                    }
                }

                AktivitasLog::catat(
                    'hapus_permanen',
                    $arsip->id,
                    "Auto-delete trash ({$hari} hari): {$arsip->judul}"
                );

                $arsip->forceDelete();
                $berhasil++;
                $this->line("  ✓ Dihapus: {$arsip->judul}");

            } catch (\Exception $e) {
                $gagal++;
                $this->error("  ✗ Gagal: {$arsip->judul} — {$e->getMessage()}");
            }
        }

        $this->newLine();
        $this->info("Selesai! Berhasil: {$berhasil}, Gagal: {$gagal}");
    }
}