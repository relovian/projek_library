<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class AktivitasLog extends Model
{
    protected $table = 'aktivitas_logs';

    protected $fillable = [
        'user_id', 'arsip_id', 'aksi', 'keterangan', 'ip_address',
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
            'edit'    => 'Metadata diperbarui',
            'hapus'   => 'Dokumen dihapus',
            'setujui' => 'Dokumen disetujui',
            'tolak'   => 'Dokumen ditolak',
            'revisi'  => 'Revisi dokumen diunggah',
            default   => $this->aksi,
        };
    }

    public function getAksiIkonAttribute(): string
    {
        return match ($this->aksi) {
            'unggah'  => '⬆️',
            'unduh'   => '⬇️',
            'lihat'   => '👁',
            'edit'    => '✏️',
            'hapus'   => '🗑️',
            'setujui' => '✅',
            'tolak'   => '❌',
            'revisi'  => '🔄',
            default   => '📋',
        };
    }

    public function getAksiWarnaDotAttribute(): string
    {
        return match ($this->aksi) {
            'unggah', 'revisi' => 'upload',
            'unduh'            => 'download',
            'setujui'          => 'approve',
            'edit'             => 'edit',
            'tolak', 'hapus'   => 'reject',
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