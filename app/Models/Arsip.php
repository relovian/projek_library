<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Facades\Storage;

class Arsip extends Model
{
    use SoftDeletes;

    protected $table = 'arsip'; 

    protected $fillable = [
        'kode_arsip', 'judul', 'deskripsi', 'nomor_surat',
        'kategori_id', 'divisi_id', 'uploader_id',
        'tanggal_dokumen', 'periode_pemilu', 'status',
        'disetujui_oleh', 'disetujui_at', 'catatan_penolakan',
        'tingkat_akses', 'tags', 'versi', 'arsip_induk_id',
    ];

    protected $casts = [
        'tanggal_dokumen' => 'date',
        'disetujui_at'    => 'datetime',
    ];

    // ── Relations ──────────────────────────────────────────
    public function kategori(): BelongsTo
    {
        return $this->belongsTo(Kategori::class);
    }

    public function divisi(): BelongsTo
    {
        return $this->belongsTo(Divisi::class);
    }

    public function uploader(): BelongsTo
    {
        return $this->belongsTo(User::class, 'uploader_id');
    }

    public function penyetuju(): BelongsTo
    {
        return $this->belongsTo(User::class, 'disetujui_oleh');
    }

    public function arsipInduk(): BelongsTo
    {
        return $this->belongsTo(Arsip::class, 'arsip_induk_id');
    }

    public function revisis(): HasMany
    {
        return $this->hasMany(Arsip::class, 'arsip_induk_id');
    }

    public function files(): HasMany
    {
        return $this->hasMany(ArsipFile::class);
    }

    public function aktivitasLogs(): HasMany
    {
        return $this->hasMany(AktivitasLog::class);
    }

    // ── Helpers ─────────────────────────────────────────────
    public function getTagsArrayAttribute(): array
    {
        return $this->tags ? explode(',', $this->tags) : [];
    }

    public function getStatusLabelAttribute(): string
    {
        return match ($this->status) {
            'draft'     => 'Draft',
            'menunggu'  => 'Menunggu',
            'ditinjau'  => 'Ditinjau',
            'disetujui' => 'Disetujui',
            'ditolak'   => 'Ditolak',
            default     => '-',
        };
    }

    public function getStatusColorAttribute(): string
    {
        return match ($this->status) {
            'draft'     => 'gray',
            'menunggu'  => 'yellow',
            'ditinjau'  => 'blue',
            'disetujui' => 'green',
            'ditolak'   => 'red',
            default     => 'gray',
        };
    }

    public function getFilePertamaAttribute(): ?ArsipFile
    {
        return $this->files()->first();
    }

    // ── Scopes ──────────────────────────────────────────────
    public function scopeMenunggu($query)
    {
        return $query->where('status', 'menunggu');
    }

    public function scopeDisetujui($query)
    {
        return $query->where('status', 'disetujui');
    }

    public function scopeByUser($query, $userId)
    {
        return $query->where('uploader_id', $userId);
    }

    public function scopeDraft($query)
    {
        return $query->where('status', 'draft');
    }

    // ── Static Helpers ──────────────────────────────────────
    public static function generateKode(): string
    {
        $tahun  = date('Y');
        $urutan = static::whereYear('created_at', $tahun)->count() + 1;
        return 'ARS-' . $tahun . '-' . str_pad($urutan, 4, '0', STR_PAD_LEFT);
    }
}