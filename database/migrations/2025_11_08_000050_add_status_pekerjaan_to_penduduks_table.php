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
            // Add columns only if they don't exist (idempotent)
            if (!Schema::hasColumn('penduduks', 'status_perkawinan')) {
                $table->string('status_perkawinan')->nullable()->after('agama');
            }
            if (!Schema::hasColumn('penduduks', 'pekerjaan')) {
                $table->string('pekerjaan')->nullable()->after('status_perkawinan');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('penduduks', function (Blueprint $table) {
            if (Schema::hasColumn('penduduks', 'pekerjaan')) {
                $table->dropColumn('pekerjaan');
            }
            if (Schema::hasColumn('penduduks', 'status_perkawinan')) {
                $table->dropColumn('status_perkawinan');
            }
        });
    }
};
