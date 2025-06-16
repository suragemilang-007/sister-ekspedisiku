<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('layanan_paket', function (Blueprint $table) {
            $table->id('id_layanan');
            $table->string('nama_layanan');
            $table->text('deskripsi');
            $table->decimal('min_berat', 10, 2);
            $table->decimal('max_berat', 10, 2);
            $table->decimal('harga_dasar', 12, 2);
        });
    }

    public function down()
    {
        Schema::dropIfExists('layanan_paket');
    }
};