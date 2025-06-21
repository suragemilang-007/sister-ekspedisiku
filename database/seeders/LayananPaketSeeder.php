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
                'nama_layanan' => 'Dokumen',
                'deskripsi' => 'Layanan khusus untuk surat dan dokumen penting.',
                'min_berat' => 0.00,
                'max_berat' => 2.00,
                'harga_dasar' => 10000.00,
            ],
            [
                'nama_layanan' => 'Makanan / Minuman',
                'deskripsi' => 'Cocok untuk pengiriman makanan, snack, atau minuman dalam kota (Same Day).',
                'min_berat' => 0.00,
                'max_berat' => 5.00,
                'harga_dasar' => 15000.00,
            ],
            [
                'nama_layanan' => 'Elektronik Ringan',
                'deskripsi' => 'Pengiriman barang elektronik kecil seperti gadget, aksesoris.',
                'min_berat' => 0.00,
                'max_berat' => 10.00,
                'harga_dasar' => 25000.00,
            ],
            [
                'nama_layanan' => 'Pakaian / Tekstil',
                'deskripsi' => 'Layanan untuk kirim baju, kain, perlengkapan fashion.',
                'min_berat' => 0.00,
                'max_berat' => 15.00,
                'harga_dasar' => 20000.00,
            ],
            [
                'nama_layanan' => 'Barang Berat / Kargo',
                'deskripsi' => 'Untuk pengiriman barang besar seperti mesin, furnitur, dll.',
                'min_berat' => 15.01,
                'max_berat' => 100.00,
                'harga_dasar' => 50000.00,
            ],
        ]);
    }
}