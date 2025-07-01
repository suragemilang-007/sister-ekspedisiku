<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up()
    {
        Schema::create('pengiriman', function (Blueprint $table) {
            $table->id('id_pengiriman');
            $table->uuid('id_pengirim');
            $table->foreign('id_pengirim')->references('uid')->on('pengguna')->onDelete('cascade');
            $table->uuid('id_alamat_tujuan');
            $table->foreign('id_alamat_tujuan')->references('uid')->on('alamat_tujuan')->onDelete('cascade');
            $table->uuid('id_alamat_penjemputan');
            $table->foreign('id_alamat_penjemputan')->references('uid')->on('alamat_penjemputan')->onDelete('cascade');
            $table->decimal('total_biaya', 12, 2);
            $table->foreignId('id_zona')->constrained('zona_pengiriman', 'id_zona');
            $table->enum('status', ['MENUNGGU KONFIRMASI', 'DIPROSES', 'DIKIRIM', 'DITERIMA', 'SELESAI', 'DIBATALKAN']);
            $table->string('nomor_resi')->unique();
            $table->text('catatan_opsional')->nullable();
            $table->text('keterangan_batal')->nullable();
            $table->longText('foto_barang')->nullable();
            $table->longText('foto_bukti_sampai')->nullable();
            $table->timestamp('tanggal_sampai')->nullable();
            $table->timestamp('created_at');
        });
    }

    public function down()
    {
        Schema::dropIfExists('pengiriman');
    }
};