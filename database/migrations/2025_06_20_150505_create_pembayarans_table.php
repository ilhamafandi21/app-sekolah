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
        Schema::create('pembayarans', function (Blueprint $table) {
            $table->id();
            $table->string('siswa_id')->constrained('siswas', 'id')->cascadeOnDelete();
            $table->string('user_id')->constrained('users', 'id')->cascadeOnDelete();
            $table->string('jenis_pembayaran_id')->constrained('jenis_pembayarans', 'id')->cascadeOnDelete();
            $table->string('status')->nullable();
            $table->string('keterangan')->nullable();
            $table->timestamps();
            
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('pembayarans');
    }
};
