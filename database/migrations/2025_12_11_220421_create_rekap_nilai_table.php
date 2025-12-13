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
        Schema::create('rekap_nilai', function (Blueprint $table) {
            $table->id();
            $table->foreignId('santri_id')->constrained('santri');
            $table->foreignId('periode_id')->constrained('periode');
            $table->decimal('total_nilai', 8, 2)->nullable();
            $table->decimal('rata_rata', 5, 2)->nullable();
            $table->integer('ranking')->nullable();
            $table->string('akhlaq')->nullable();
            $table->string('kerajinan')->nullable();
            $table->string('kedisiplinan')->nullable();
            $table->string('kerapihan')->nullable();
            $table->text('catatan_wali')->nullable();
            $table->string('keputusan')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('rekap_nilai');
    }
};
