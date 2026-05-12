<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('files', function (Blueprint $table) {
            $table->id();
            $table->foreignId('arsip_id')->constrained('arsip')->cascadeOnDelete();
            $table->string('nama_asli');         // nama file asli saat upload
            $table->string('nama_simpan');       // nama file di storage
            $table->string('path');              // path di storage
            $table->string('mime_type');
            $table->string('ekstensi');
            $table->unsignedBigInteger('ukuran'); // bytes
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('files');
    }
};