<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up()
    {
        Schema::create('kepala_notifications', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('kepala_keluarga_id')->index();
            $table->string('title');
            $table->text('body')->nullable();
            $table->string('url')->nullable();
            $table->json('data')->nullable();
            $table->timestamp('read_at')->nullable();
            $table->timestamps();

            $table->foreign('kepala_keluarga_id')->references('id')->on('kepala_keluargas')->onDelete('cascade');
        });
    }

    public function down()
    {
        Schema::dropIfExists('kepala_notifications');
    }
};
