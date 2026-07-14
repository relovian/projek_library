<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('users', function (Blueprint $table) {
            $table->id();
            $table->string('nama_lengkap');
            $table->string('nip')->unique()->nullable();
            $table->string('email')->unique();
            $table->string('password');
            $table->enum('role', ['admin', 'komisioner', 'kepala_sekretariat', 'kepala_sub_bagian', 'staff'])->default('staff');
            $table->foreignId('divisi_id')->nullable()->constrained('divisi')->nullOnDelete();
            $table->string('telepon')->nullable();
            $table->string('foto')->nullable();
            $table->boolean('is_aktif')->default(true);
            $table->timestamp('last_login_at')->nullable();
            $table->rememberToken();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('users');
    }
};