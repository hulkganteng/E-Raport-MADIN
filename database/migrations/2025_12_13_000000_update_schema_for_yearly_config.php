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
        // 1. Create wali_kelas table for yearly assignment
        Schema::create('wali_kelas', function (Blueprint $table) {
            $table->id();
            $table->foreignId('kelas_id')->constrained('kelas')->onDelete('cascade');
            $table->foreignId('user_id')->constrained('users')->onDelete('cascade'); // The teacher
            $table->foreignId('periode_id')->constrained('periode')->onDelete('cascade');
            $table->timestamps();
            
            // Ensure one teacher per class per period
            $table->unique(['kelas_id', 'periode_id']); 
        });

        // 2. Create riwayat_kelas table for student history
        Schema::create('riwayat_kelas', function (Blueprint $table) {
            $table->id();
            $table->foreignId('santri_id')->constrained('santri')->onDelete('cascade');
            $table->foreignId('kelas_id')->constrained('kelas')->onDelete('cascade');
            $table->foreignId('periode_id')->constrained('periode')->onDelete('cascade');
            $table->string('status'); // e.g., 'naik_kelas', 'tinggal_kelas', 'lulus', 'baru'
            $table->timestamps();
        });

        // 3. Update kelas table
        Schema::table('kelas', function (Blueprint $table) {
            $table->integer('tingkat')->after('nama_kelas')->default(1); // 1, 2, 3...
            // wali_kelas_id in 'kelas' is now deprecated but kept for existing data compatibility if needed, 
            // or we could make it nullable if it wasn't. It was nullable in original migration.
        });

        // 4. Update kelas_mapel table to be period-aware
        Schema::table('kelas_mapel', function (Blueprint $table) {
            $table->foreignId('periode_id')->after('mapel_id')->nullable()->constrained('periode')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('kelas_mapel', function (Blueprint $table) {
            $table->dropForeign(['periode_id']);
            $table->dropColumn('periode_id');
        });

        Schema::table('kelas', function (Blueprint $table) {
            $table->dropColumn('tingkat');
        });

        Schema::dropIfExists('riwayat_kelas');
        Schema::dropIfExists('wali_kelas');
    }
};
