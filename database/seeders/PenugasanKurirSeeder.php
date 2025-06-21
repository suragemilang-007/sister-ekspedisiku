<?php

namespace Database\Seeders;

use Carbon\Carbon;
use DB;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class PenugasanKurirSeeder extends Seeder
{
    public function run(): void
    {
        $now = Carbon::now();

        DB::table('penugasan_kurir')->insert([
            [
                'id_pengiriman' => 1,
                'id_kurir' => 1,
                'status' => 'MENUJU PENGIRIM',
                'catatan' => 'Segera menuju lokasi pengirim',
                'created_at' => $now,
                'updated_at' => null,
            ],
            [
                'id_pengiriman' => 2,
                'id_kurir' => 3,
                'status' => 'DITERIMA KURIRI',
                'catatan' => 'Siap diambil dari pengirim',
                'created_at' => $now,
                'updated_at' => null,
            ],
            [
                'id_pengiriman' => 3,
                'id_kurir' => 6,
                'status' => 'DALAM_PENGIRIMAN',
                'catatan' => 'Sedang dalam perjalanan',
                'created_at' => $now,
                'updated_at' => null,
            ],
            [
                'id_pengiriman' => 6,
                'id_kurir' => 4,
                'status' => 'MENUJU PENGIRIM',
                'catatan' => 'Barang elektronik, hati-hati',
                'created_at' => $now,
                'updated_at' => null,
            ],
            [
                'id_pengiriman' => 7,
                'id_kurir' => 9,
                'status' => 'DALAM_PENGIRIMAN',
                'catatan' => 'Barang fragile',
                'created_at' => $now,
                'updated_at' => null,
            ],
            [
                'id_pengiriman' => 9,
                'id_kurir' => 8,
                'status' => 'DITERIMA KURIRI',
                'catatan' => 'Pengiriman di luar kota',
                'created_at' => $now,
                'updated_at' => null,
            ],
            [
                'id_pengiriman' => 11,
                'id_kurir' => 6,
                'status' => 'MENUJU PENGIRIM',
                'catatan' => null,
                'created_at' => $now,
                'updated_at' => null,
            ],
            [
                'id_pengiriman' => 16,
                'id_kurir' => 1,
                'status' => 'MENUJU PENGIRIM',
                'catatan' => null,
                'created_at' => $now,
                'updated_at' => null,
            ],
            [
                'id_pengiriman' => 17,
                'id_kurir' => 3,
                'status' => 'DALAM_PENGIRIMAN',
                'catatan' => 'Makanan, jangan terlalu lama',
                'created_at' => $now,
                'updated_at' => null,
            ],
            [
                'id_pengiriman' => 19,
                'id_kurir' => 4,
                'status' => 'DALAM_PENGIRIMAN',
                'catatan' => null,
                'created_at' => $now,
                'updated_at' => null,
            ],
            [
                'id_pengiriman' => 21,
                'id_kurir' => 9,
                'status' => 'DIANTAR',
                'catatan' => 'Customer menunggu di depan rumah',
                'created_at' => $now,
                'updated_at' => null,
            ],
            [
                'id_pengiriman' => 22,
                'id_kurir' => 6,
                'status' => 'DALAM_PENGIRIMAN',
                'catatan' => null,
                'created_at' => $now,
                'updated_at' => null,
            ],
            [
                'id_pengiriman' => 23,
                'id_kurir' => 8,
                'status' => 'MENUJU PENGIRIM',
                'catatan' => null,
                'created_at' => $now,
                'updated_at' => null,
            ],
        ]);
    }
}