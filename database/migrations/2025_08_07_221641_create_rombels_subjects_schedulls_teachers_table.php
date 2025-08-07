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
        Schema::create('rombels_subjects_schedulls_teachers', function (Blueprint $table) {
            $table->id();
            $table->string('kode')->unique();
            $table->foreignId('rombels_subjects_id')->nullable()->constrained('rombelsSubjects', 'id')->nullOnDelete();
            $table->foreignId('rombel_id')->nullable()->constrained('rombels', 'id')->nullOnDelete();
            $table->foreignId('subject_id')->nullable()->constrained('subjects', 'id')->nullOnDelete();
            $table->foreignId('schedull_id')->nullable()->constrained('schedulls', 'id')->nullOnDelete();
            $table->foreignId('teacher_id')->nullable()->constrained('teachers', 'id')->nullOnDelete();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('rombels_subjects_schedulls_teachers');
    }
};
