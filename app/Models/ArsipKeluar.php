<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ArsipKeluar extends Model
{
    use SoftDeletes;

    protected $table = 'arsip_keluar';

    protected $fillable = [
        'kode_arsip_keluar',
        'nama_file',
        'perihal',
        'klasifikasi_id',
        'sifat_id',
        'sub_bagian_id',
        'verifikator_id',
        'tujuan_id',
        'pembuat_id',
        'tanggal_surat',
        'tanggal_unggah',
        'link_file',
        'uploader_id',
    ];

    protected $casts = [
        'tanggal_surat'  => 'date',
        'tanggal_unggah' => 'date',
    ];

    // ── Relations ──────────────────────────────────────────
    public function klasifikasi(): BelongsTo
    {
        return $this->belongsTo(Klasifikasi::class);
    }

    public function sifatSurat(): BelongsTo
    {
        return $this->belongsTo(SifatSurat::class, 'sifat_id');
    }

    public function subBagian(): BelongsTo
    {
        return $this->belongsTo(SubBagian::class);
    }

    public function verifikator(): BelongsTo
    {
        return $this->belongsTo(Verifikator::class);
    }

    public function tujuan(): BelongsTo
    {
        return $this->belongsTo(Tujuan::class);
    }

    public function pembuat(): BelongsTo
    {
        return $this->belongsTo(User::class, 'pembuat_id');
    }

    public function uploader(): BelongsTo
    {
        return $this->belongsTo(User::class, 'uploader_id');
    }

    // ── Helper ─────────────────────────────────────────────
    public static function generateKode(string $klasifikasiSingkatan, string $tanggal): string
    {
        // Format: KLASIFIKASI-TAHUNBULANTANGGAL-NNN
        // Contoh: PM-20260720-001
        $tgl = str_replace('-', '', $tanggal); // 2026-07-20 -> 20260720
        
        // Cari nomor urut terakhir hari ini
        $last = self::whereDate('created_at', today())
            ->orderBy('id', 'desc')
            ->first();

        // Ambil nomor urut dari kode arsip terakhir hari ini
        if ($last && preg_match('/\d{3}$/', $last->kode_arsip_keluar, $m)) {
            $urutan = (int) $m[0] + 1;
        } else {
            $urutan = 1;
        }
        $urutan = str_pad($urutan, 3, '0', STR_PAD_LEFT);

        return $klasifikasiSingkatan . '-' . $tgl . '-' . $urutan;
    }
}