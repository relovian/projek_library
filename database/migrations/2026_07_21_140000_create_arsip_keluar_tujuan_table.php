<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('arsip_keluar_tujuan', function (Blueprint $table) {
            $table->id();
            $table->foreignId('arsip_keluar_id')->constrained('arsip_keluar')->onDelete('cascade');
            $table->foreignId('tujuan_id')->constrained('tujuan')->onDelete('cascade');
            $table->timestamps();

            $table->unique(['arsip_keluar_id', 'tujuan_id']);
        });
    }

    public function down()
    {
        Schema::dropIfExists('arsip_keluar_tujuan');
    }
};