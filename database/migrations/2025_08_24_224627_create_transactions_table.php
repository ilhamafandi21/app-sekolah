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
        Schema::create('transactions', function (Blueprint $table) {
            $table->id();
            $table->string('kode');
            $table->foreignId('siswa_id')->constrained('siswas', 'id')->nullOnDelete();
            $table->foreignId('biaya_id')->constrained('biayas', 'id')->nullOnDelete();
            $table->foreignId('rombel_id')->constrained('rombels', 'id')->nullOnDelete();
            $table->foreignId('tingkat_id')->constrained('tingkats', 'id')->nullOnDelete();
            $table->foreignId('jurusan_id')->constrained('jurusans', 'id')->nullOnDelete();
            $table->string('divisi')->nullOnDelete();
            $table->integer('nominal')->nullOnDelete();
            $table->string('semester')->nullOnDelete();
            $table->boolean('status')->default(false)->nullOnDelete();
            $table->string('keterangan')->nullOnDelete();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('transactions');
    }
};
