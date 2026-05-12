<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('arsip', function (Blueprint $table) {
            $table->id();
            $table->string('kode_arsip')->unique(); // ARS-2026-0001
            $table->string('judul');
            $table->text('deskripsi')->nullable();
            $table->string('nomor_surat')->nullable();
            $table->foreignId('kategori_id')->constrained('kategori');
            $table->foreignId('divisi_id')->constrained('divisi');
            $table->foreignId('uploader_id')->constrained('users');
            $table->date('tanggal_dokumen');
            $table->string('periode_pemilu')->nullable(); // Pemilu 2024, Pilkada 2024, dll
            $table->enum('status', ['draft', 'menunggu', 'ditinjau', 'disetujui', 'ditolak'])->default('menunggu');
            $table->foreignId('disetujui_oleh')->nullable()->constrained('users')->nullOnDelete();
            $table->timestamp('disetujui_at')->nullable();
            $table->text('catatan_penolakan')->nullable();
            $table->enum('tingkat_akses', ['publik_internal', 'divisi', 'pimpinan', 'rahasia'])->default('publik_internal');
            $table->string('tags')->nullable(); // JSON string of tags
            $table->integer('versi')->default(1);
            $table->foreignId('arsip_induk_id')->nullable()->constrained('arsip')->nullOnDelete(); // untuk revisi
            $table->timestamps();
            $table->softDeletes();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('arsips');
    }
};