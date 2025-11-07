<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('surat_requests', function (Blueprint $table) {
            $table->id();
            $table->foreignId('kepala_keluarga_id')->constrained('kepala_keluargas')->cascadeOnDelete();
            $table->string('jenis_surat');
            $table->string('tujuan')->nullable();
            $table->text('keterangan')->nullable();
            $table->json('payload')->nullable();
            $table->string('status')->default('pending');
            $table->unsignedBigInteger('reviewed_by')->nullable();
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('surat_requests');
    }
};
