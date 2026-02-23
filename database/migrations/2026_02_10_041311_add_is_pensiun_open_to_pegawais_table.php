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
        Schema::table('pegawais', function (Blueprint $table) {
            // 0 = Tertutup (Puskesmas gak bisa upload)
            // 1 = Terbuka (Puskesmas bisa upload)
            $table->boolean('is_pensiun_open')->default(0)->after('batas_usia_pensiun');
        });
    }

    public function down()
    {
        Schema::table('pegawais', function (Blueprint $table) {
            $table->dropColumn('is_pensiun_open');
        });
    }
};
