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
        Schema::create('rombels_penilaians', function (Blueprint $table) {
            $table->id();
            $table->foreignId('rombel_id')->constrained('rombels', 'id')->nullOnDelete();
            $table->foreignId('siswa_id')->constrained('siswas', 'id')->nullOnDelete();
            $table->foreignId('subject_id')->nullable()->constrained('subjects', 'id')->nullOnDelete();
            $table->foreignId('indikatornilai_id')->nullable()->constrained('indikatornilais', 'id')->nullOnDelete();
            $table->foreignId('teacher_id')->nullable()->constrained('teachers', 'id')->nullOnDelete();
            $table->foreignId('semester_id')->nullable()->constrained('semesters', 'id')->nullOnDelete();
            $table->integer('nilai')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('rombels_penilaians');
    }
};
