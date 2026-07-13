<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('arsip_keluar', function (Blueprint $table) {
            $table->id();
            $table->string('kode_arsip_keluar', 50)->unique()->nullable();
            $table->string('nama_file', 255);
            $table->string('perihal', 255);
            $table->foreignId('klasifikasi_id')->constrained('klasifikasi')->onDelete('restrict');
            $table->foreignId('sifat_id')->constrained('sifat_surat')->onDelete('restrict');
            $table->foreignId('sub_bagian_id')->constrained('sub_bagian')->onDelete('restrict');
            $table->foreignId('verifikator_id')->constrained('verifikator')->onDelete('restrict');
            $table->foreignId('tujuan_id')->constrained('tujuan')->onDelete('restrict');
            $table->foreignId('pembuat_id')->constrained('users')->onDelete('restrict');
            $table->date('tanggal_surat');
            $table->date('tanggal_unggah');
            $table->text('link_file')->nullable();
            $table->foreignId('uploader_id')->constrained('users')->onDelete('cascade');
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('arsip_keluar');
    }
};