<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // Hapus duplikasi sebelum menambahkan unique constraint
        // NilaiMapel: santri_id + kelas_mapel_id + periode_id
        if (Schema::hasTable('nilai_mapel')) {
            DB::statement('
                DELETE nm1 FROM nilai_mapel nm1
                INNER JOIN nilai_mapel nm2
                WHERE nm1.id > nm2.id
                  AND nm1.santri_id = nm2.santri_id
                  AND nm1.kelas_mapel_id = nm2.kelas_mapel_id
                  AND nm1.periode_id = nm2.periode_id
            ');

            Schema::table('nilai_mapel', function (Blueprint $table) {
                $table->unique(['santri_id', 'kelas_mapel_id', 'periode_id'], 'unique_nilai_per_santri_mapel_periode');
            });
        }

        // RekapNilai: santri_id + periode_id
        if (Schema::hasTable('rekap_nilai')) {
            DB::statement('
                DELETE rn1 FROM rekap_nilai rn1
                INNER JOIN rekap_nilai rn2
                WHERE rn1.id > rn2.id
                  AND rn1.santri_id = rn2.santri_id
                  AND rn1.periode_id = rn2.periode_id
            ');

            Schema::table('rekap_nilai', function (Blueprint $table) {
                $table->unique(['santri_id', 'periode_id'], 'unique_rekap_per_santri_periode');
            });
        }

        // Absensi: santri_id + periode_id
        if (Schema::hasTable('absensi')) {
            DB::statement('
                DELETE a1 FROM absensi a1
                INNER JOIN absensi a2
                WHERE a1.id > a2.id
                  AND a1.santri_id = a2.santri_id
                  AND a1.periode_id = a2.periode_id
            ');

            Schema::table('absensi', function (Blueprint $table) {
                $table->unique(['santri_id', 'periode_id'], 'unique_absensi_per_santri_periode');
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        if (Schema::hasTable('nilai_mapel')) {
            Schema::table('nilai_mapel', function (Blueprint $table) {
                $table->dropUnique('unique_nilai_per_santri_mapel_periode');
            });
        }

        if (Schema::hasTable('rekap_nilai')) {
            Schema::table('rekap_nilai', function (Blueprint $table) {
                $table->dropUnique('unique_rekap_per_santri_periode');
            });
        }

        if (Schema::hasTable('absensi')) {
            Schema::table('absensi', function (Blueprint $table) {
                $table->dropUnique('unique_absensi_per_santri_periode');
            });
        }
    }
};
