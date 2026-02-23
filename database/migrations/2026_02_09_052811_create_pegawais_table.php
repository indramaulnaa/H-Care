<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up()
    {
        Schema::create('pegawais', function (Blueprint $table) {
            $table->id(); // id (PK) [cite: 39]
            $table->string('nip')->unique(); // nip (Unik) [cite: 40]
            $table->string('nama_lengkap'); // [cite: 41]
            $table->date('tanggal_lahir'); // PENTING untuk rumus pensiun [cite: 42]
            $table->string('jabatan'); // [cite: 43]
            $table->string('unit_kerja'); // Relasi ke Puskesmas [cite: 44]
            $table->integer('batas_usia_pensiun')->default(58); // Default 58 tahun [cite: 45]
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('pegawais');
    }
};
