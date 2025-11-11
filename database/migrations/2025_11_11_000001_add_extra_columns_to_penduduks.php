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
        Schema::table('penduduks', function (Blueprint $table) {
            if (! Schema::hasColumn('penduduks', 'pendidikan')) {
                $table->string('pendidikan')->nullable()->after('agama');
            }
            if (! Schema::hasColumn('penduduks', 'kewarganegaraan')) {
                $table->string('kewarganegaraan')->nullable()->after('pendidikan');
            }
            if (! Schema::hasColumn('penduduks', 'kode_pos')) {
                $table->string('kode_pos')->nullable()->after('kelurahan');
            }
            if (! Schema::hasColumn('penduduks', 'provinsi')) {
                $table->string('provinsi')->nullable()->after('alamat');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('penduduks', function (Blueprint $table) {
            if (Schema::hasColumn('penduduks', 'kode_pos')) $table->dropColumn('kode_pos');
            if (Schema::hasColumn('penduduks', 'kewarganegaraan')) $table->dropColumn('kewarganegaraan');
            if (Schema::hasColumn('penduduks', 'pendidikan')) $table->dropColumn('pendidikan');
            if (Schema::hasColumn('penduduks', 'provinsi')) $table->dropColumn('provinsi');
        });
    }
};
