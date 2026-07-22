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
            // Drop foreign key constraint first, then drop the column
            $table->dropForeign(['tujuan_id']);
            $table->dropColumn('tujuan_id');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('arsip_keluar', function (Blueprint $table) {
            $table->foreignId('tujuan_id')->nullable()->constrained('tujuan')->onDelete('restrict');
        });
    }
};

