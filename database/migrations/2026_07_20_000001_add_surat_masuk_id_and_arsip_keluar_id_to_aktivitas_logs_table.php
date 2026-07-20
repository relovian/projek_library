<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('aktivitas_logs', function (Blueprint $table) {
            // Pastikan idempotent: kolom mungkin sudah ada di DB.
            if (!Schema::hasColumn('aktivitas_logs', 'surat_masuk_id')) {
                $table->foreignId('surat_masuk_id')->nullable()->after('arsip_id');
                $table->foreign('surat_masuk_id')->references('id')->on('surat_masuk')->nullOnDelete();
            }

            if (!Schema::hasColumn('aktivitas_logs', 'arsip_keluar_id')) {
                $table->foreignId('arsip_keluar_id')->nullable()->after('surat_masuk_id');
                $table->foreign('arsip_keluar_id')->references('id')->on('arsip_keluar')->nullOnDelete();
            }
        });
    }

    public function down(): void
    {
        Schema::table('aktivitas_logs', function (Blueprint $table) {
            // drop foreign keys dulu
            $table->dropForeign(['surat_masuk_id']);
            $table->dropForeign(['arsip_keluar_id']);

            $table->dropColumn(['arsip_keluar_id', 'surat_masuk_id']);
        });
    }
};

