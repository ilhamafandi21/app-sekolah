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
        Schema::create('ortu_siswas', function (Blueprint $table) {
            $table->id();
            $table->string('nama_ibu')->nullable();
            $table->string('nama_ayah')->nullable();
            $table->string('pekerjaan_ibu')->nullable();
            $table->string('pekerjaan_ayah')->nullable();
            $table->string('pend_terakhir_ibu')->nullable();
            $table->string('pend_terakhir_ayah')->nullable();
            $table->string('phone')->nullable();
            $table->string('alamat')->nullable();
            $table->foreignId('siswa_id')->constrained('siswas', 'id')->cascadeOnDelete();
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('ortu_siswas');
    }
};
