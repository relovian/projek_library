<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Tujuan extends Model
{
    protected $fillable = ['nama', 'is_aktif'];
    protected $table = 'tujuan';

    protected $casts = ['is_aktif' => 'boolean'];

    public function arsip(): HasMany
    {
        return $this->hasMany(Arsip::class);
    }

    public function arsipMasuk(): BelongsToMany
    {
        return $this->belongsToMany(ArsipMasuk::class, 'surat_masuk_tujuan', 'tujuan_id', 'surat_masuk_id')
                    ->withTimestamps();
    }
}
