<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('surat_masuk', function (Blueprint $table) {
            $table->id();
            $table->string('nama_file');
            $table->string('perihal');
            $table->string('asal_instansi');
            $table->date('tanggal_surat');
            $table->date('tanggal_diterima');
            $table->date('tanggal_unggah');
            $table->string('link_file')->nullable();
            $table->foreignId('uploader_id')->constrained('users');
            $table->timestamps();
        });

        // Pivot table for disposisi/tujuan (many-to-many with users)
        Schema::create('surat_masuk_user', function (Blueprint $table) {
            $table->id();
            $table->foreignId('surat_masuk_id')->constrained('surat_masuk')->cascadeOnDelete();
            $table->foreignId('user_id')->constrained('users')->cascadeOnDelete();
            $table->string('tipe')->default('disposisi'); // 'disposisi' or 'tujuan'
            $table->timestamps();

            $table->unique(['surat_masuk_id', 'user_id', 'tipe']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('surat_masuk_user');
        Schema::dropIfExists('surat_masuk');
    }
};