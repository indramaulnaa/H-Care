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
        Schema::create('pengajuan_cutis', function (Blueprint $table) {
            $table->id();
            $table->foreignId('id_pegawai')->constrained('pegawais')->onDelete('cascade');
            
            $table->string('jenis_cuti'); // Tahunan, Sakit, Melahirkan, dll
            $table->date('tanggal_mulai');
            $table->date('tanggal_selesai');
            
            // File dari Puskesmas
            $table->string('file_permohonan'); 
            
            // File Balasan dari Dinkes (Nanti diisi saat disetujui)
            $table->string('file_sk_resmi')->nullable(); 
            
            // Status: 0=Menunggu, 1=Disetujui, 2=Ditolak
            $table->enum('status', ['menunggu', 'disetujui', 'ditolak'])->default('menunggu');
            $table->text('keterangan_admin')->nullable(); // Jika ada revisi
            
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('pengajuan_cutis');
    }
};
