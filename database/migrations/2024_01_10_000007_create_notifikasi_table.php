<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('notifikasi', function (Blueprint $table) {
            $table->id('id_notifikasi');
            $table->foreignId('id_pengguna')->constrained('pengguna', 'id_pengguna');
            $table->string('judul');
            $table->text('pesan');
            $table->enum('tipe', ['INFO', 'WARNING', 'SUCCESS', 'ERROR']);
            $table->boolean('dibaca')->default(false);
            $table->timestamp('created_at');
        });
    }

    public function down()
    {
        Schema::dropIfExists('notifikasi');
    }
};