<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up()
    {
        Schema::create('pengiriman', function (Blueprint $table) {
            $table->id('id_pengiriman');
            $table->foreignId('id_pengirim')->constrained('pengguna', 'id_pengguna');
            $table->foreignId('id_alamat_tujuan')->constrained('alamat_tujuan', 'id_alamat_tujuan');
            $table->foreignId('id_alamat_penjemputan')->constrained('alamat_penjemputan', 'id_alamat_penjemputan');
            $table->decimal('total_biaya', 12, 2);
            $table->foreignId('id_zona')->constrained('zona_pengiriman', 'id_zona');
            $table->enum('status', ['MENUNGGU KONFIRMASI', 'DIPROSES', 'DIBAYAR', 'DIKIRIM', 'DITERIMA', 'DIBATALKAN']);
            $table->string('nomor_resi')->unique();
            $table->text('catatan_opsional')->nullable();
            $table->text('keterangan_batal')->nullable();
            $table->string('foto_barang')->nullable();
            $table->string('foto_bukti_sampai')->nullable();
            $table->timestamp('tanggal_sampai')->nullable();
            $table->timestamp('created_at');
        });
    }

    public function down()
    {
        Schema::dropIfExists('pengiriman');
    }
};