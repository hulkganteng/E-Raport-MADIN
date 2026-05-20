<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        if (!Schema::hasTable('santri')) {
            return;
        }

        Schema::table('santri', function (Blueprint $table) {
            if (!Schema::hasColumn('santri', 'tempat_lahir')) {
                $table->string('tempat_lahir')->nullable()->after('kelas_id');
            }
            if (!Schema::hasColumn('santri', 'tanggal_lahir')) {
                $table->date('tanggal_lahir')->nullable()->after('tempat_lahir');
            }
            if (!Schema::hasColumn('santri', 'alamat')) {
                $table->text('alamat')->nullable()->after('tanggal_lahir');
            }
            if (!Schema::hasColumn('santri', 'nama_ayah')) {
                $table->string('nama_ayah')->nullable()->after('alamat');
            }
            if (!Schema::hasColumn('santri', 'pekerjaan_ayah')) {
                $table->string('pekerjaan_ayah')->nullable()->after('nama_ayah');
            }
            if (!Schema::hasColumn('santri', 'nama_ibu')) {
                $table->string('nama_ibu')->nullable()->after('pekerjaan_ayah');
            }
            if (!Schema::hasColumn('santri', 'pekerjaan_ibu')) {
                $table->string('pekerjaan_ibu')->nullable()->after('nama_ibu');
            }
            if (!Schema::hasColumn('santri', 'no_hp_ortu')) {
                $table->string('no_hp_ortu')->nullable()->after('pekerjaan_ibu');
            }
        });

        if (Schema::hasTable('santri_biodata')) {
            DB::table('santri_biodata')->orderBy('id')->get()->each(function ($biodata) {
                DB::table('santri')
                    ->where('id', $biodata->santri_id)
                    ->update([
                        'tempat_lahir' => $biodata->tempat_lahir,
                        'tanggal_lahir' => $biodata->tanggal_lahir,
                        'alamat' => $biodata->alamat,
                        'nama_ayah' => $biodata->nama_ayah,
                        'pekerjaan_ayah' => $biodata->pekerjaan_ayah,
                        'nama_ibu' => $biodata->nama_ibu,
                        'pekerjaan_ibu' => $biodata->pekerjaan_ibu,
                        'no_hp_ortu' => $biodata->no_hp_ortu,
                    ]);
            });

            Schema::drop('santri_biodata');
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        if (!Schema::hasTable('santri')) {
            return;
        }

        if (!Schema::hasTable('santri_biodata')) {
            Schema::create('santri_biodata', function (Blueprint $table) {
                $table->id();
                $table->foreignId('santri_id')->constrained('santri')->onDelete('cascade');
                $table->string('tempat_lahir')->nullable();
                $table->date('tanggal_lahir')->nullable();
                $table->text('alamat')->nullable();
                $table->string('nama_ayah')->nullable();
                $table->string('pekerjaan_ayah')->nullable();
                $table->string('nama_ibu')->nullable();
                $table->string('pekerjaan_ibu')->nullable();
                $table->string('no_hp_ortu')->nullable();
                $table->timestamps();
            });
        }

        DB::table('santri')->orderBy('id')->get()->each(function ($santri) {
            DB::table('santri_biodata')->updateOrInsert(
                ['santri_id' => $santri->id],
                [
                    'tempat_lahir' => $santri->tempat_lahir,
                    'tanggal_lahir' => $santri->tanggal_lahir,
                    'alamat' => $santri->alamat,
                    'nama_ayah' => $santri->nama_ayah,
                    'pekerjaan_ayah' => $santri->pekerjaan_ayah,
                    'nama_ibu' => $santri->nama_ibu,
                    'pekerjaan_ibu' => $santri->pekerjaan_ibu,
                    'no_hp_ortu' => $santri->no_hp_ortu,
                    'updated_at' => now(),
                    'created_at' => now(),
                ]
            );
        });

        Schema::table('santri', function (Blueprint $table) {
            $columns = [
                'tempat_lahir',
                'tanggal_lahir',
                'alamat',
                'nama_ayah',
                'pekerjaan_ayah',
                'nama_ibu',
                'pekerjaan_ibu',
                'no_hp_ortu',
            ];

            foreach ($columns as $column) {
                if (Schema::hasColumn('santri', $column)) {
                    $table->dropColumn($column);
                }
            }
        });
    }
};
