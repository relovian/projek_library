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
        Schema::table('sub_bagian', function (Blueprint $table) {
            $table->dropForeign(['divisi_id']); 
            $table->dropColumn('divisi_id');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('sub_bagian', function (Blueprint $table) {
            //
        });
    }
};
