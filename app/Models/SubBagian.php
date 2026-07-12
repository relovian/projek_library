<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class SubBagian extends Model
{
    protected $fillable = ['nama','deskripsi', 'is_aktif'];
    protected $table = 'sub_bagian';

    protected $casts = ['is_aktif' => 'boolean'];

    public function arsips(): HasMany
    {
        return $this->hasMany(Arsip::class);
    }
}