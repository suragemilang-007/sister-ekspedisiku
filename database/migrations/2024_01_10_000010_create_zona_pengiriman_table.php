<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up()
    {
        Schema::create('zona_pengiriman', function (Blueprint $table) {
            $table->id('id_zona');
            $table->foreignId('id_layanan')->constrained('layanan_paket', 'id_layanan');
            $table->string('nama_zona');
            $table->string('kecamatan_asal');
            $table->string('kecamatan_tujuan');
            $table->decimal('biaya_tambahan', 12, 2);
        });
    }

    public function down()
    {
        Schema::dropIfExists('zona_pengiriman');
    }
};