<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up()
    {
        Schema::create('feedback', function (Blueprint $table) {
            $table->id('id_feedback');
            $table->uuid('uid')->unique();
            $table->uuid('id_pengirim');
            $table->foreign('id_pengirim')->references('uid')->on('pengguna')->onDelete('cascade');
            $table->foreignId('id_pengiriman')->constrained('pengiriman', 'id_pengiriman');
            $table->integer('rating');
            $table->text('komentar')->nullable();
            $table->timestamp('created_at');
        });
    }

    public function down()
    {
        Schema::dropIfExists('feedback');
    }
};