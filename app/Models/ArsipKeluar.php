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
        'tanggal_unggah' => 'datetime',
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
        
        // Hitung jumlah semua arsip keluar hari ini (termasuk yang sudah dihapus/soft deleted)
        // supaya nomor urut terus naik dan tidak ada nomor yang dipakai ulang
        $count = self::withTrashed()
            ->whereDate('created_at', today())
            ->count();
        
        $urutan = str_pad($count + 1, 3, '0', STR_PAD_LEFT);

        return $klasifikasiSingkatan . '-' . $tgl . '-' . $urutan;
    }
}