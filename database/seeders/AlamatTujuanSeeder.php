<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class AlamatTujuanSeeder extends Seeder
{
    public function run(): void
    {
        DB::table('alamat_tujuan')->insert([
            [
                'nama_penerima' => 'Budi Santoso',
                'id_pengirim' => 8,
                'no_hp' => '081234567891',
                'alamat_lengkap' => 'Jl. Jenderal Sudirman No. 45, Purwokerto',
                'kecamatan' => 'Purwokerto Selatan',
                'kode_pos' => '53145',
                'created_at' => Carbon::now(),
            ],
            [
                'nama_penerima' => 'Siti Aminah',
                'id_pengirim' => 8,
                'no_hp' => '082233445566',
                'alamat_lengkap' => 'Perumahan Griya Indah Blok C3, Purbalingga',
                'kecamatan' => 'Kalimanah',
                'kode_pos' => '53371',
                'created_at' => Carbon::now(),
            ],
            [
                'nama_penerima' => 'Agus Supriyadi',
                'id_pengirim' => 8,
                'no_hp' => '081345678901',
                'alamat_lengkap' => 'Jl. HR Bunyamin, Kampus Unsoed, Purwokerto',
                'kecamatan' => 'Purwokerto Utara',
                'kode_pos' => '53122',
                'created_at' => Carbon::now(),
            ],
            [
                'nama_penerima' => 'Lestari Widya',
                'id_pengirim' => 8,
                'no_hp' => '085677889900',
                'alamat_lengkap' => 'Jl. Raya Bobotsari No. 123, Purbalingga',
                'kecamatan' => 'Bobotsari',
                'kode_pos' => '53353',
                'created_at' => Carbon::now(),
            ],
            [
                'nama_penerima' => 'Andi Saputra',
                'id_pengirim' => 8,
                'no_hp' => '089912345678',
                'alamat_lengkap' => 'Komplek Taman Hijau, Purwokerto Timur',
                'kecamatan' => 'Purwokerto Timur',
                'kode_pos' => '53141',
                'created_at' => Carbon::now(),
            ],
        ]);
    }
}