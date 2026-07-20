<?php

namespace App\Services;

use App\Models\Notification;
use App\Models\User;
use Illuminate\Support\Facades\Route;

class NotifikasiService
{
    /**
     * Kirim notifikasi saat arsip dibuat (unggah).
     * Semua user aktif mendapat notifikasi (staff, komisioner, pengelola),
     * KECUALI user yang mengunggah (tidak perlu lihat notifikasi untuk aksinya sendiri).
     */
    public function notifyCreate(string $entityType, $entity, string $entityName): void
    {
        $message = "Arsip baru: {$entityName}";
        $link = route('aktivitas.index');
        $currentUserId = auth()->id();

        // Kirim ke semua user aktif, kecuali uploader
        $targetUsers = User::where('is_aktif', true)
            ->where('id', '!=', $currentUserId)
            ->get();

        foreach ($targetUsers as $user) {
            Notification::create([
                'user_id'     => $user->id,
                'type'        => 'create',
                'message'     => $message,
                'link'        => $link,
                'entity_type' => $entityType,
                'entity_id'   => $entity?->id,
            ]);
        }
    }

    /**
     * Kirim notifikasi saat arsip diperbarui (update/edit).
     * Hanya untuk Pengelola (Admin, Kasek, Kasubag).
     */
    public function notifyUpdate(string $entityType, $entity, string $entityName): void
    {
        $message = "Arsip diperbarui: {$entityName}";
        $link = route('aktivitas.index');

        $targetUsers = User::where('is_aktif', true)
            ->whereIn('role', ['admin', 'kepala_sekretariat', 'kepala_sub_bagian'])
            ->get();

        foreach ($targetUsers as $user) {
            Notification::create([
                'user_id'     => $user->id,
                'type'        => 'update',
                'message'     => $message,
                'link'        => $link,
                'entity_type' => $entityType,
                'entity_id'   => $entity?->id,
            ]);
        }
    }

    /**
     * Kirim notifikasi saat arsip dihapus (soft delete).
     * Hanya untuk Pengelola (Admin, Kasek, Kasubag).
     */
    public function notifyDelete(string $entityType, $entity, string $entityName): void
    {
        $message = "Arsip dipindahkan ke sampah oleh admin: {$entityName}";
        $link = route('aktivitas.index');

        $targetUsers = User::where('is_aktif', true)
            ->whereIn('role', ['admin', 'kepala_sekretariat', 'kepala_sub_bagian'])
            ->get();

        foreach ($targetUsers as $user) {
            Notification::create([
                'user_id'     => $user->id,
                'type'        => 'soft_delete',
                'message'     => $message,
                'link'        => $link,
                'entity_type' => $entityType,
                'entity_id'   => $entity?->id,
            ]);
        }
    }

    /**
     * Kirim notifikasi saat arsip dipulihkan (restore) oleh admin.
     * Kirim ke uploader (pemilik arsip) bahwa arsipnya sudah dipulihkan.
     */
    public function notifyRestore(string $entityType, $entity, string $entityName, int $uploaderId): void
    {
        $message = "Arsip dipulihkan oleh admin: {$entityName}";
        $link = route('aktivitas.index');

        // Kirim ke uploader (pemilik arsip) + semua admin
        $targetUsers = User::where('is_aktif', true)
            ->where(function($q) use ($uploaderId) {
                $q->where('id', $uploaderId)
                  ->orWhere('role', 'admin');
            })->get();

        foreach ($targetUsers as $user) {
            Notification::create([
                'user_id'     => $user->id,
                'type'        => 'restore',
                'message'     => $message,
                'link'        => $link,
                'entity_type' => $entityType,
                'entity_id'   => $entity?->id,
            ]);
        }
    }

    /**
     * Kirim notifikasi saat arsip dihapus permanen (force delete) oleh admin.
     * Kirim ke uploader (pemilik arsip) bahwa arsipnya sudah dihapus permanen.
     */
    public function notifyForceDelete(string $entityType, $entity, string $entityName, int $uploaderId): void
    {
        $message = "Arsip dihapus permanen oleh admin: {$entityName}";
        $link = route('aktivitas.index');

        // Kirim ke uploader (pemilik arsip) + semua admin
        $targetUsers = User::where('is_aktif', true)
            ->where(function($q) use ($uploaderId) {
                $q->where('id', $uploaderId)
                  ->orWhere('role', 'admin');
            })->get();

        foreach ($targetUsers as $user) {
            Notification::create([
                'user_id'     => $user->id,
                'type'        => 'force_delete',
                'message'     => $message,
                'link'        => $link,
                'entity_type' => $entityType,
                'entity_id'   => $entity?->id,
            ]);
        }
    }

    /**
     * Ambil notifikasi terbaru untuk user tertentu, difilter berdasarkan role.
     */
    public function getNotificationsForUser(User $user, int $limit = 5): array
    {
        $query = Notification::forUser($user->id)->latest();

        // Komisioner: hanya lihat notifikasi type 'create'
        if ($user->isKomisioner()) {
            $query->where('type', 'create');
        }

        $notifications = $query->take($limit)->get();
        $unreadCount = Notification::forUser($user->id)
            ->when($user->isKomisioner(), fn($q) => $q->where('type', 'create'))
            ->unread()
            ->count();

        return [
            'notifications'   => $notifications,
            'unreadCount'     => $unreadCount,
        ];
    }

    /**
     * Tandai semua notifikasi user sebagai sudah dibaca.
     */
    public function markAllAsRead(User $user): void
    {
        Notification::forUser($user->id)->unread()->update(['is_read' => true]);
    }
}