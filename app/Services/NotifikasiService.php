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

        // Kirim ke semua user aktif yang mengaktifkan notif_arsip_baru, kecuali uploader
        $targetUsers = User::where('is_aktif', true)
            ->where('id', '!=', $currentUserId)
            ->where('notif_arsip_baru', true)
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
            ->where('notif_arsip_baru', true)
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
     * Kirim notifikasi saat arsip dihapus (soft delete) oleh user.
     * Notifikasi masuk ke Admin untuk menunggu persetujuan hapus permanen.
     * Admin tinggal cek di trash.
     */
    public function notifyDelete(string $entityType, $entity, string $entityName): void
    {
        $message = "Menunggu persetujuan hapus permanen: {$entityName}";
        $link = route('aktivitas.index');

        // Kirim ke semua admin yang mengaktifkan notif_menunggu_persetujuan
        $targetUsers = User::where('is_aktif', true)
            ->where('role', 'admin')
            ->where('notif_menunggu_persetujuan', true)
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
     * Notifikasi hanya ke uploader (pemilik arsip) bahwa arsipnya tidak disetujui & dipulihkan.
     * Hanya untuk user yang mengaktifkan notif_arsip_ditolak.
     */
    public function notifyRestore(string $entityType, $entity, string $entityName, int $uploaderId): void
    {
        $message = "Arsip tidak disetujui dipulihkan oleh admin: {$entityName}";
        $link = route('aktivitas.index');

        // Kirim hanya ke uploader (pemilik arsip) yang mengaktifkan notif_arsip_ditolak
        $targetUser = User::where('is_aktif', true)
            ->where('id', $uploaderId)
            ->where('notif_arsip_ditolak', true)
            ->first();

        if ($targetUser) {
            Notification::create([
                'user_id'     => $targetUser->id,
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
     * Notifikasi hanya ke uploader (pemilik arsip) bahwa arsipnya disetujui & dihapus permanen.
     * Hanya untuk user yang mengaktifkan notif_arsip_disetujui.
     */
    public function notifyForceDelete(string $entityType, $entity, string $entityName, int $uploaderId): void
    {
        $message = "Arsip disetujui hapus permanen oleh admin: {$entityName}";
        $link = route('aktivitas.index');

        // Kirim hanya ke uploader (pemilik arsip) yang mengaktifkan notif_arsip_disetujui
        $targetUser = User::where('is_aktif', true)
            ->where('id', $uploaderId)
            ->where('notif_arsip_disetujui', true)
            ->first();

        if ($targetUser) {
            Notification::create([
                'user_id'     => $targetUser->id,
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