<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('alamat_penjemputan', function (Blueprint $table) {
            $table->id('id_alamat_penjemputan');
            $table->foreignId('id_pengirim')->constrained('pengguna', 'id_pengguna');
            $table->string('nama_pengirim');
            $table->string('no_hp');
            $table->text('alamat_lengkap');
            $table->string('kecamatan');
            $table->string('kode_pos', 10);
            $table->string('keterangan_alamat')->nullable();
            $table->timestamp('created_at');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('alamat_penjemputan');
    }
};