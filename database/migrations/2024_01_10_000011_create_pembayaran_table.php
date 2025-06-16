<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('pembayaran', function (Blueprint $table) {
            $table->id('id_pembayaran');
            $table->foreignId('id_pengiriman')->constrained('pengiriman', 'id_pengiriman');
            $table->decimal('jumlah', 12, 2);
            $table->enum('metode', ['TRANSFER_BANK', 'E_WALLET', 'CASH']);
            $table->enum('status', ['PENDING', 'BERHASIL', 'GAGAL']);
            $table->string('bukti_pembayaran')->nullable();
            $table->timestamp('created_at');
            $table->timestamp('confirmed_at')->nullable();
        });
    }

    public function down()
    {
        Schema::dropIfExists('pembayaran');
    }
};