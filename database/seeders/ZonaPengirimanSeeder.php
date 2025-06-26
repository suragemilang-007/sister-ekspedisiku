<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Carbon\Carbon;
class ZonaPengirimanSeeder extends Seeder
{
    public function run(): void
    {
        $kecamatans = [
            'Purwokerto Utara',
            'Purwokerto Timur',
            'Purwokerto Selatan',
            'Purwokerto Barat',
            'Ajibarang',
            'Banyumas',
            'Baturraden',
            'Cilongok',
            'Gumelar',
            'Jatilawang',
            'Kalibagor',
            'Karanglewas',
            'Kebasen',
            'Kedungbanteng',
            'Bobotsari',
            'Bojongsari',
            'Bukateja',
            'Kaligondang',
            'Kalimanah',
            'Karanganyar',
            'Karangjambu',
            'Karangmoncol',
            'Karangreja',
            'Kejobong'
        ];

        $data = [];

        foreach ($kecamatans as $asal) {
            foreach ($kecamatans as $tujuan) {
                if ($asal === $tujuan)
                    continue; // skip jika asal == tujuan

                foreach ([1, 2, 3, 4, 5] as $idLayanan) {
                    // Tentukan rentang biaya berdasarkan id_layanan
                    switch ($idLayanan) {
                        case 1:
                            $minBiaya = 5000;
                            $maxBiaya = 15000;
                            break;
                        case 2:
                            $minBiaya = 10000;
                            $maxBiaya = 20000;
                            break;
                        case 3:
                            $minBiaya = 15000;
                            $maxBiaya = 30000;
                            break;
                        case 4:
                            $minBiaya = 20000;
                            $maxBiaya = 40000;
                            break;
                        case 5:
                            $minBiaya = 30000;
                            $maxBiaya = 60000;
                            break;
                    }

                    $data[] = [
                        'id_layanan' => $idLayanan,
                        'nama_zona' => "$asal - $tujuan",
                        'kecamatan_asal' => $asal,
                        'kecamatan_tujuan' => $tujuan,
                        'biaya_tambahan' => rand($minBiaya, $maxBiaya),

                    ];
                }
            }
        }

        DB::table('zona_pengiriman')->insert($data);
    }
}