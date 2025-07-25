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
        Schema::create('subjects_jurusans', function (Blueprint $table) {
            $table->id();
            $table->foreignId('subject_id')->constrained('subjects', 'id')->onDelete('cascade');
            $table->foreignId('jurusan_id')->constrained('jurusans', 'id')->onDelete('cascade');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('subjects_jurusans');
    }
};
