<?php

namespace App\Models;

use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class User extends Authenticatable
{
    use Notifiable;

    protected $fillable = [
        'nama_lengkap', 'nip', 'email', 'password',
        'role', 'divisi_id', 'telepon', 'foto', 'is_aktif', 'last_login_at',
        'notif_arsip_baru', 'notif_arsip_disetujui', 'notif_arsip_ditolak',
        'notif_menunggu_persetujuan','notif_revisi_dokumen',
    ];

    protected $hidden = ['password', 'remember_token'];

    protected $casts = [
        'is_aktif'      => 'boolean',
        'last_login_at' => 'datetime',
    ];

    // ── Relations ──────────────────────────────────────────
    public function divisi(): BelongsTo
    {
        return $this->belongsTo(Divisi::class);
    }

    public function arsips(): HasMany
    {
        return $this->hasMany(Arsip::class, 'uploader_id');
    }

    public function arsipsDisetujui(): HasMany
    {
        return $this->hasMany(Arsip::class, 'disetujui_oleh');
    }

    public function aktivitasLogs(): HasMany
    {
        return $this->hasMany(AktivitasLog::class);
    }

    // ── Helpers ─────────────────────────────────────────────
    public function isAdmin(): bool
    {
        return $this->role === 'admin';
    }

    public function isPimpinan(): bool
    {
        return $this->role === 'pimpinan';
    }

    public function isStaff(): bool
    {
        return $this->role === 'staff';
    }

    public function getInisialAttribute(): string
    {
        $kata = explode(' ', $this->nama_lengkap);
        return strtoupper(
            count($kata) >= 2
                ? $kata[0][0] . $kata[1][0]
                : substr($kata[0], 0, 2)
        );
    }

    public function getRoleLabelAttribute(): string
    {
        return match ($this->role) {
            'admin'    => 'Admin',
            'pimpinan' => 'Pimpinan',
            default    => 'Staff',
        };
    }
}