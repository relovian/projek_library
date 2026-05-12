<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Kategori extends Model
{
    protected $fillable = ['nama', 'kode', 'warna', 'deskripsi', 'is_aktif'];
    protected $table = 'kategori';

    protected $casts = ['is_aktif' => 'boolean'];

    public function arsips(): HasMany
    {
        return $this->hasMany(Arsip::class);
    }
}