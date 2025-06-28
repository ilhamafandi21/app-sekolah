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
        Schema::create('siswas', function (Blueprint $table) {
            $table->id();
            $table->string('nama');
            $table->string('tempat_lahir')->nullable();
            $table->string('ttl')->nullable();
            $table->string('jenis_kelamin');
            $table->string('alamat')->nullable();
            $table->string('email')->unique();
            $table->string('agama')->nullable();
            $table->string('nis')->nullable();;
            $table->string('nisn')->nullable();;
            $table->string('asal_sekolah')->nullable();
            $table->string('status_pendaftaran')->nullable();
            $table->string('status_siswa')->nullable();
            $table->string('waktu_pendaftaran')->nullable();
            $table->string('waktu_siswa_aktif')->nullable();
            $table->string('password');
            $table->string('role')->nullable();
            $table->foreignId('user_id')->constrained('users')->cascadeOnDelete();
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('siswas');
    }
};
