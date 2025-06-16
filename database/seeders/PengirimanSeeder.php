<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Carbon\Carbon;

class PengirimanSeeder extends Seeder
{
    public function run(): void
    {
        $statusList = ['MENUNGGU_PEMBAYARAN', 'DIBAYAR', 'DIPROSES', 'DIKIRIM', 'DITERIMA', 'DIBATALKAN'];

        for ($i = 1; $i <= 10; $i++) {
            DB::table('pengiriman')->insert([
                'id_pengirim' => rand(6, 8), // sesuaikan ID yang ada di tabel pengguna
                'id_alamat_tujuan' => rand(1, 4),
                'total_biaya' => rand(20000, 150000),
                'id_layanan' => rand(1, 3),
                'status' => $statusList[array_rand($statusList)],
                'nomor_resi' => 'EXP' . date('Ymd') . sprintf('%06d', $i),
                'catatan_opsional' => fake()->optional()->sentence(),
                'keterangan_batal' => fake()->optional(0.2)->sentence(),
                'created_at' => Carbon::now()->subDays(rand(0, 30)),
            ]);
        }
    }
}