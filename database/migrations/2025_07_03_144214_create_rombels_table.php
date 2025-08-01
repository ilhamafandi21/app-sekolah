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
        Schema::create('rombels', function (Blueprint $table) {
            $table->id();
            $table->string('kode')->unique();
            $table->foreignId('tahun_ajaran_id')->constrained('tahun_ajarans', 'id')->nullOnDelete();
            $table->foreignId('tingkat_id')->constrained('tingkats', 'id')->nullOnDelete();
            $table->foreignId('jurusan_id')->constrained('jurusans', 'id')->nullOnDelete();
            $table->string('divisi')->nullable();
            $table->boolean('status');
            $table->string('keterangan')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('rombels');
    }
};
