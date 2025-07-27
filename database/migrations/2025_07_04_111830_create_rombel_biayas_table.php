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
        Schema::create('rombel_biayas', function (Blueprint $table) {
            $table->id();
            $table->foreignId('rombel_id')->constrained('rombels', 'id')->cascadeOnDelete();
            $table->string('semester_id')->nullable();
            $table->foreignId('biaya_id')->constrained('biayas', 'id')->cascadeOnDelete();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('rombel_biayas');
    }
};
