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
        Schema::create('berkas_pensiuns', function (Blueprint $table) {
            $table->id();
            // Relasi ke tabel Pegawai
            $table->foreignId('id_pegawai')->constrained('pegawais')->onDelete('cascade');
            
            // 3 File Syarat Utama
            $table->string('file_sk_cpns')->nullable();
            $table->string('file_sk_pangkat')->nullable();
            $table->string('file_karpeg')->nullable();
            
            // Status Verifikasi (Menunggu, Disetujui, Ditolak)
            $table->enum('status', ['menunggu', 'disetujui', 'ditolak'])->default('menunggu');
            $table->text('catatan_admin')->nullable(); // Jika ada revisi dari Dinkes
            
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('berkas_pensiuns');
    }
};
