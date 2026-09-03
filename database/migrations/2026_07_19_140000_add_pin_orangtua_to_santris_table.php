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
        if (!Schema::hasTable('santri') || Schema::hasColumn('santri', 'pin_orangtua')) {
            return;
        }

        Schema::table('santri', function (Blueprint $table) {
            $table->string('pin_orangtua', 6)->nullable()->after('no_hp_ortu');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        if (!Schema::hasTable('santri') || !Schema::hasColumn('santri', 'pin_orangtua')) {
            return;
        }

        Schema::table('santri', function (Blueprint $table) {
            $table->dropColumn('pin_orangtua');
        });
    }
};
