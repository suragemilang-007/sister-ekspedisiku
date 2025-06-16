<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('pelacakan', function (Blueprint $table) {
            $table->id('id_pelacakan');
            $table->foreignId('id_pengiriman')->constrained('pengiriman', 'id_pengiriman');
            $table->foreignId('id_pengguna')->constrained('pengguna', 'id_pengguna');
            $table->string('status');
            $table->text('keterangan');
            $table->string('lokasi');
            $table->timestamp('created_at');
        });
    }

    public function down()
    {
        Schema::dropIfExists('pelacakan');
    }
};