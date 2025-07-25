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
            $table->string('name')->nukllable();
            $table->foreignId('semester_id')->constrained('semesters', 'id')->cascadeOnDelete();
            $table->string('tingkat_id');
            $table->foreignId('jurusan_id')->constrained('jurusans', 'id')->cascadeOnDelete();
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
