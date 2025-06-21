<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up()
    {
        Schema::create('penugasan_kurir', function (Blueprint $table) {
            $table->id('id_penugasan');
            $table->foreignId('id_pengiriman')->constrained('pengiriman', 'id_pengiriman');
            $table->foreignId('id_kurir')->constrained('kurir', 'id_kurir');
            $table->enum('status', ['MENUJU PENGIRIM', 'DITERIMA KURIRI', 'DIANTAR', 'DITERIMA', 'DALAM_PENGIRIMAN', 'SELESAI', 'DIBATALKAN']);
            $table->text('catatan')->nullable();
            $table->timestamp('created_at');
            $table->timestamp('updated_at')->nullable();
        });
    }

    public function down()
    {
        Schema::dropIfExists('penugasan_kurir');
    }
};