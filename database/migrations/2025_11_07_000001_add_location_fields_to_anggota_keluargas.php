<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::table('anggota_keluargas', function (Blueprint $table) {
            $table->string('provinsi')->nullable()->after('alamat');
            $table->string('kota')->nullable()->after('provinsi');
            $table->string('kecamatan')->nullable()->after('kota');
            $table->string('kelurahan')->nullable()->after('kecamatan');
            $table->string('kode_pos')->nullable()->after('kelurahan');
        });
    }

    public function down()
    {
        Schema::table('anggota_keluargas', function (Blueprint $table) {
            $table->dropColumn(['provinsi','kota','kecamatan','kelurahan','kode_pos']);
        });
    }
};
