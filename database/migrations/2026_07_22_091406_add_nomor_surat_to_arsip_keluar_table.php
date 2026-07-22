<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('arsip_keluar', function (Blueprint $table) {
            $table->string('nomor_surat', 100)->nullable()->after('kode_arsip_keluar');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('arsip_keluar', function (Blueprint $table) {
            $table->dropColumn('nomor_surat');
        });
    }
};
