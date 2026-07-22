<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('arsip_keluar', function (Blueprint $table) {
            $table->timestamp('tanggal_unggah')->nullable()->change();
        });
    }

    public function down(): void
    {
        Schema::table('arsip_keluar', function (Blueprint $table) {
            $table->date('tanggal_unggah')->nullable()->change();
        });
    }
};