<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class AktivitasLog extends Model
{
    protected $table = 'aktivitas_logs';

    protected $fillable = [
        'user_id',
        'arsip_id',
        'surat_masuk_id',
        'arsip_keluar_id',
        'aksi',
        'keterangan',
        'ip_address',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function arsip(): BelongsTo
    {
        return $this->belongsTo(Arsip::class);
    }

    public function getAksiLabelAttribute(): string
    {
        return match ($this->aksi) {
            'unggah'  => 'Dokumen baru diunggah',
            'unduh'   => 'Dokumen diunduh',
            'lihat'   => 'Dokumen dilihat',
            'edit'    => 'Arsip diperbarui',
            'hapus'   => 'Arsip dihapus',
            'hapus_permanen' => 'Arsip dihapus permanen',
            'pulihkan' => 'Arsip dipulihkan',
            default   => $this->aksi,
        };
    }

    public function getAksiIkonAttribute(): string
    {
        return match ($this->aksi) {
            'unggah' => asset('img/unggah.png'),
            'unduh' => asset('img/unduh.png'),
            'lihat' => asset('img/pratinjau.png'),
            'edit' => asset('img/edit.png'),
            'hapus' => asset('img/pending.png'),
            'hapus_permanen' => asset('img/hapus.png'),
            'setujui' => asset('img/persetujuan.png'),
            'tolak'   => asset('img/tolak.png'),
            'revisi'  => asset('img/revisi.png'),
            'pulihkan' => asset('img/pulihkan.png'),
            default   => asset('img/berkas.png'),
        };
    }

    public function getAksiWarnaDotAttribute(): string
    {
        return match ($this->aksi) {
            'unggah', 'revisi' => 'upload',
            'unduh'            => 'download',
            'setujui'          => 'approve',
            'edit'             => 'edit',
            'tolak', 'hapus', 'hapus_permanen' => 'reject',
            default            => 'download',
        };
    }

    // ── Static Helper ──────────────────────────────────────
    public static function catat(string $aksi, ?int $arsipId = null, ?string $keterangan = null): void
    {
        static::create([
            'user_id'    => auth()->id(),
            'arsip_id'   => $arsipId,
            'aksi'       => $aksi,
            'keterangan' => $keterangan,
            'ip_address' => request()->ip(),
        ]);
    }
}