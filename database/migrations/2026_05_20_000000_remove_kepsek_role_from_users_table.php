<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        DB::table('users')
            ->where('role', 'kepsek')
            ->update(['role' => 'guru']);

        DB::statement("ALTER TABLE users MODIFY role ENUM('super_admin', 'guru', 'wali_kelas') NOT NULL DEFAULT 'guru'");
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        DB::statement("ALTER TABLE users MODIFY role ENUM('super_admin', 'guru', 'wali_kelas', 'kepsek') NOT NULL DEFAULT 'guru'");
    }
};
