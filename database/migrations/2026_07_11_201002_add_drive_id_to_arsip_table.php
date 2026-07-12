<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up()
    {
        Schema::table('surat_masuk', function (Blueprint $table) {
            $table->string('drive_id')->nullable()->after('link_file');
            // $table->string('drive_link')->nullable()->after('drive_id'); // opsional
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('arsip', function (Blueprint $table) {
            //
        });
    }
};
