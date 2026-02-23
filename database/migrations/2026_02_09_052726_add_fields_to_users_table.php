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
        Schema::table('users', function (Blueprint $table) {
            // Menambah kolom sesuai Blueprint [cite: 34, 36, 37]
            $table->string('username')->unique()->after('email'); // Tambahan username
            $table->enum('role', ['admin_dinkes', 'admin_puskesmas'])->default('admin_puskesmas'); // Role pengguna
            $table->string('nama_unit')->nullable(); // Contoh: "Puskesmas Bandar"
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            //
        });
    }
};
