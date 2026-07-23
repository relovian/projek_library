<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class ArsipMasuk extends Model
{
    use SoftDeletes;

    protected $table = 'surat_masuk';

    protected $fillable = [
        'kode_arsip_masuk',
        'nama_file',
        'perihal',
        'asal_instansi',
        'tanggal_surat',
        'tanggal_diterima',
        'tanggal_unggah',
        'link_file',
        'uploader_id',
        'tujuan_id',
        'force_deleted_at',
    ];

    protected $casts = [
        'tanggal_surat'    => 'date',
        'tanggal_diterima' => 'date',
        'tanggal_unggah'   => 'timestamp',
        'force_deleted_at' => 'datetime',
    ];

    /**
     * Multiple tujuan surat (many-to-many).
     */
    public function tujuans(): BelongsToMany
    {
        return $this->belongsToMany(Tujuan::class, 'surat_masuk_tujuan', 'surat_masuk_id', 'tujuan_id')
                    ->withTimestamps();
    }

    // ── Relations ──────────────────────────────────────────
    public function uploader(): BelongsTo
    {
        return $this->belongsTo(User::class, 'uploader_id');
    }

    public function tujuan(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(Tujuan::class, 'tujuan_id');
    }

    /**
     * Users yang dipilih sebagai disposisi/tujuan surat.
     * Tipe: 'disposisi'
     */
    public function usersDisposisi(): BelongsToMany
    {
        return $this->belongsToMany(User::class, 'surat_masuk_user')
                    ->withPivot('tipe')
                    ->wherePivot('tipe', 'disposisi')
                    ->withTimestamps();
    }

    /**
     * All users terkait surat ini.
     */
    public function users(): BelongsToMany
    {
        return $this->belongsToMany(User::class, 'surat_masuk_user')
                    ->withPivot('tipe')
                    ->withTimestamps();
    }
}