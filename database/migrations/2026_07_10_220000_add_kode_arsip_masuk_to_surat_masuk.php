<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('surat_masuk', function (Blueprint $table) {
            $table->string('kode_arsip_masuk', 20)->nullable()->unique()->after('id');
        });
    }

    public function down(): void
    {
        Schema::table('surat_masuk', function (Blueprint $table) {
            $table->dropColumn('kode_arsip_masuk');
        });
    }
};