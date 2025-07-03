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
        Schema::create('rombel_siswas', function (Blueprint $table) {
            $table->id();
            $table->foreignId('rombel_id')->constrained('rombels', 'id')->cascadeOnDelete();
            $table->foreignId('siswa_id')->constrained('siswas','id')->cascadeOnDelete();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('rombel_siswas');
    }
};
