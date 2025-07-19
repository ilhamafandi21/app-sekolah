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
        Schema::create('nilais', function (Blueprint $table) {
            $table->id();
            $table->foreignId('siswa_id')->constrained('siswas', 'id')->cascadeOnDelete();
            $table->foreignId('jenis_nilai_id')->constrained('jenis_nilais', 'id')->cascadeOnDelete();
            $table->foreignId('rombel_id')->constrained('rombels', 'id')->cascadeOnDelete();
            $table->foreignId('teacher_id')->constrained('teachers', 'id')->cascadeOnDelete();
            $table->integer('skor')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('nilais');
    }
};
