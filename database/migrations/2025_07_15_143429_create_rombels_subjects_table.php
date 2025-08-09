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
            $table->foreignId('day_id')->nullable()->constrained('days', 'id')->nullOnDelete();
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
        Schema::dropIfExists('rombels_subjects');
    }
};
