<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->boolean('notif_arsip_baru')->default(true)->after('is_aktif');
            $table->boolean('notif_arsip_disetujui')->default(true)->after('notif_arsip_baru');
            $table->boolean('notif_arsip_ditolak')->default(true)->after('notif_arsip_disetujui');
            $table->boolean('notif_menunggu_persetujuan')->default(true)->after('notif_arsip_ditolak');
            $table->boolean('notif_revisi_dokumen')->default(true)->after('notif_menunggu_persetujuan');
        });
    }

    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn([
                'notif_arsip_baru',
                'notif_arsip_disetujui',
                'notif_arsip_ditolak',
                'notif_menunggu_persetujuan',
                'notif_revisi_dokumen',
            ]);
        });
    }
};