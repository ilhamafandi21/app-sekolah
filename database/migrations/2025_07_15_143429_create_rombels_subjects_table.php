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
        Schema::create('rombels_subjects', function (Blueprint $table) {
            $table->id();
            $table->foreignId('rombel_id')->constrained('rombels', 'id')->cascadeOnDelete();
            $table->foreignId('subject_id')->constrained('subjects', 'id')->cascadeOnDelete();
            $table->foreignId('teacher_id')->constrained('teachers', 'id')->cascadeOnDelete()->nullable();
            $table->string('semester_id')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('rombels_subjects');
    }
};
