<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Klasifikasi extends Model
{
    protected $fillable = ['nama', 'deskripsi', 'is_aktif'];
    protected $table = 'klasifikasi';

    protected $casts = ['is_aktif' => 'boolean'];

    public function arsips(): HasMany
    {
        return $this->hasMany(Arsip::class);
    }
}