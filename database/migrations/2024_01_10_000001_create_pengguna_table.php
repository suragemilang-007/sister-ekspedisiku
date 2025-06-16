<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('pengguna', function (Blueprint $table) {
            $table->id('id_pengguna');
            $table->string('nama');
            $table->string('email')->unique();
            $table->date('tgl_lahir');
            $table->string('nohp');
            $table->text('alamat');
            $table->enum('kelamin', ['L', 'P']);
            $table->string('sandi_hash');
            $table->enum('peran', ['admin', 'pelanggan']);
            $table->timestamp('created_at');
        });
    }

    public function down()
    {
        Schema::dropIfExists('pengguna');
    }
};