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
        Schema::create('lembaga', function (Blueprint $table) {
            $table->id();

            // Identitas lembaga
            $table->string('nama_lembaga')->default('');
            $table->string('jenjang')->default('Madrasah Diniyah');
            $table->string('nama_sekolah')->default('')->nullable();
            $table->string('npsn')->nullable();
            $table->string('nsm')->nullable();
            $table->string('nss')->nullable();

            // Alamat
            $table->string('alamat')->nullable();
            $table->string('desa')->nullable();
            $table->string('kecamatan')->nullable();
            $table->string('kabupaten')->nullable();
            $table->string('provinsi')->nullable();
            $table->string('kode_pos')->nullable();

            // Kontak
            $table->string('telepon')->nullable();
            $table->string('email')->nullable();
            $table->string('website')->nullable();

            // Kepala lembaga (default fallback; raport memakai yang per periode jika diisi)
            $table->string('nama_kepala')->nullable();
            $table->string('nip_kepala')->nullable();

            // Logo
            $table->string('logo')->nullable();

            // Pengaturan akademik
            $table->decimal('kkm_default', 5, 2)->default(75);
            $table->decimal('grade_min_a', 5, 2)->default(85);
            $table->decimal('grade_min_b', 5, 2)->default(75);
            $table->decimal('grade_min_c', 5, 2)->default(60);

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('lembaga');
    }
};
