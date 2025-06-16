<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class LayananPaketSeeder extends Seeder
{
    public function run(): void
    {
        DB::table('layanan_paket')->insert([
            [
                'nama_layanan' => 'Reguler',
                'deskripsi' => 'Pengiriman standar 2-3 hari kerja.',
                'min_berat' => 0.00,
                'max_berat' => 10.00,
                'harga_dasar' => 15000.00,
            ],
            [
                'nama_layanan' => 'Ekspres',
                'deskripsi' => 'Pengiriman cepat 1 hari sampai.',
                'min_berat' => 0.00,
                'max_berat' => 5.00,
                'harga_dasar' => 25000.00,
            ],
            [
                'nama_layanan' => 'Same Day',
                'deskripsi' => 'Pengiriman tiba di hari yang sama.',
                'min_berat' => 0.00,
                'max_berat' => 2.00,
                'harga_dasar' => 40000.00,
            ],
            [
                'nama_layanan' => 'Hemat',
                'deskripsi' => 'Pengiriman dengan biaya murah, estimasi 4-6 hari.',
                'min_berat' => 0.00,
                'max_berat' => 20.00,
                'harga_dasar' => 10000.00,
            ],
            [
                'nama_layanan' => 'Kargo',
                'deskripsi' => 'Layanan untuk pengiriman barang berat dan besar.',
                'min_berat' => 10.01,
                'max_berat' => 100.00,
                'harga_dasar' => 60000.00,
            ],
        ]);
    }
}