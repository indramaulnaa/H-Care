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
            // 0 = Tidak minta, 1 = Sedang minta akses
            $table->boolean('is_request_open_access')->default(0)->after('is_pensiun_open');
        });
    }

    public function down()
    {
        Schema::table('pegawais', function (Blueprint $table) {
            $table->dropColumn('is_request_open_access');
        });
    }
};
