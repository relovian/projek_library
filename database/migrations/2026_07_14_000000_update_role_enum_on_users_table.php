<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        // First, convert existing 'pimpinan' roles to 'kepala_sekretariat'
        DB::table('users')->where('role', 'pimpinan')->update(['role' => 'kepala_sekretariat']);

        Schema::table('users', function (Blueprint $table) {
            // Drop the old enum column
            $table->dropColumn('role');
        });

        Schema::table('users', function (Blueprint $table) {
            // Add the new enum column with updated values
            $table->enum('role', ['admin', 'komisioner', 'kepala_sekretariat', 'kepala_sub_bagian', 'staff'])->default('staff')->after('password');
        });
    }

    public function down(): void
    {
        // Convert 'kepala_sekretariat' back to 'pimpinan'
        DB::table('users')->where('role', 'kepala_sekretariat')->update(['role' => 'pimpinan']);

        Schema::table('users', function (Blueprint $table) {
            // Drop the new enum column
            $table->dropColumn('role');
        });

        Schema::table('users', function (Blueprint $table) {
            // Restore the old enum column
            $table->enum('role', ['admin', 'staff', 'pimpinan'])->default('staff')->after('password');
        });
    }
};
