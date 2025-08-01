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
        Schema::create('jurusans', function (Blueprint $table) {
            $table->id();
            $table->foreignId('tingkat_id')->constrained('tingkats', 'id'); // Contoh: TKJ, RPL, IPA, IPS
            $table->string('kode')->unique();           // Nama lengkap jurusan
            $table->string('nama_jurusan');           // Nama lengkap jurusan
            $table->text('keterangan')->nullable(); // Opsional
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('jurusans');
    }
};
