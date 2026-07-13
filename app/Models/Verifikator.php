<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Verifikator extends Model
{
    protected $fillable = ['user_id', 'is_aktif'];
    protected $table = 'verifikator';

    protected $casts = ['is_aktif' => 'boolean'];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function arsips(): HasMany
    {
        return $this->hasMany(Arsip::class);
    }

    public function arsipKeluars(): HasMany
    {
        return $this->hasMany(ArsipKeluar::class, 'verifikator_id');
    }
}