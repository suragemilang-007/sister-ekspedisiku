<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up()
    {
        Schema::create('kurir', function (Blueprint $table) {
            $table->id('id_kurir');
            $table->string('nama');
            $table->string('email')->unique();
            $table->string('nohp');
            $table->text('alamat');
            $table->string('foto')->nullable();
            $table->string('sandi_hash');
            $table->enum('status', ['AKTIF', 'NONAKTIF']);
            $table->enum('kendaraan', ['MOTOR', 'MOBIL'])->default('MOTOR');
            $table->timestamp('created_at');
        });
    }

    public function down()
    {
        Schema::dropIfExists('kurir');
    }
};