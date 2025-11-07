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
            if (!Schema::hasColumn('penduduks', 'agama')) {
                $table->string('agama')->nullable()->after('tanggal_lahir');
            }
            if (!Schema::hasColumn('penduduks', 'nomor_telepon')) {
                $table->string('nomor_telepon')->nullable()->after('agama');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('penduduks', function (Blueprint $table) {
            if (Schema::hasColumn('penduduks', 'nomor_telepon')) {
                $table->dropColumn('nomor_telepon');
            }
            if (Schema::hasColumn('penduduks', 'agama')) {
                $table->dropColumn('agama');
            }
        });
    }
};
