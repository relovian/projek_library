<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Facades\Storage;

class ArsipFile extends Model
{
    protected $table = 'files';

    protected $fillable = [
        'arsip_id', 'nama_asli', 'nama_simpan', 'path', 'mime_type', 'ekstensi', 'ukuran',
    ];

    public function arsip(): BelongsTo
    {
        return $this->belongsTo(Arsip::class);
    }

    public function getUkuranFormatAttribute(): string
    {
        $bytes = $this->ukuran;
        if ($bytes >= 1048576) return round($bytes / 1048576, 1) . ' MB';
        if ($bytes >= 1024)    return round($bytes / 1024, 1) . ' KB';
        return $bytes . ' B';
    }

    public function getUrlAttribute(): string
    {
        return Storage::url($this->path);
    }

    public function getIkonAttribute(): string
    {
        return match ($this->ekstensi) {
            'pdf'  => '📕',
            'docx', 'doc' => '📘',
            'xlsx', 'xls' => '📗',
            'jpg', 'jpeg', 'png' => '🖼️',
            default => '📄',
        };
    }
}