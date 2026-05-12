<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Divisi extends Model
{
    protected $fillable = ['nama', 'kode', 'deskripsi', 'is_aktif'];
    protected $table = 'divisi';

    protected $casts = ['is_aktif' => 'boolean'];

    public function arsips(): HasMany
    {
        return $this->hasMany(Arsip::class);
    }

    public function users(): HasMany
    {
        return $this->hasMany(User::class);
    }
}