<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->string('pertanyaan_keamanan')->nullable()->after('notif_revisi_dokumen');
            $table->string('jawaban_keamanan')->nullable()->after('pertanyaan_keamanan');
        });
    }

    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn(['pertanyaan_keamanan', 'jawaban_keamanan']);
        });
    }
};