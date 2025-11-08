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
            // add indexes to improve ORDER BY and search performance
            if (!Schema::hasColumn('penduduks', 'nama')) return;
            $table->index('nama');
            if (Schema::hasColumn('penduduks', 'nik')) {
                $table->index('nik');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('penduduks', function (Blueprint $table) {
            if (Schema::hasColumn('penduduks', 'nama')) {
                $table->dropIndex(['nama']);
            }
            if (Schema::hasColumn('penduduks', 'nik')) {
                $table->dropIndex(['nik']);
            }
        });
    }
};
