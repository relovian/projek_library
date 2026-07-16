<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class SifatSurat extends Model
{
    protected $fillable = ['nama','deskripsi', 'is_aktif'];
    protected $table = 'sifat_surat';

    protected $casts = ['is_aktif' => 'boolean'];

    public function arsip(): HasMany
    {
        return $this->hasMany(Arsip::class);
    }

    public function arsipKeluar(): HasMany
    {
        return $this->hasMany(ArsipKeluar::class, 'sifat_id');
    }
}
