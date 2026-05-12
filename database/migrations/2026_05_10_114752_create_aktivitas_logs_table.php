<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('aktivitas_logs', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained('users');
            $table->foreignId('arsip_id')->nullable()->constrained('arsip')->nullOnDelete();
            $table->enum('aksi', [
                'unggah',
                'unduh',
                'lihat',
                'edit',
                'hapus',
                'setujui',
                'tolak',
                'revisi',
            ]);
            $table->text('keterangan')->nullable();
            $table->string('ip_address')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('aktivitas_logs');
    }
};