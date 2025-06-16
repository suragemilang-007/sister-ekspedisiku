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
                'no_hp' => '081234567891',
                'alamat_lengkap' => 'Jl. Jenderal Sudirman No. 45, Purwokerto',
                'kecematan' => 'Purwokerto Selatan',
                'kode_pos' => '53145',
                'telepon' => '0281-556677',
                'created_at' => Carbon::now(),
            ],
            [
                'nama_penerima' => 'Siti Aminah',
                'no_hp' => '082233445566',
                'alamat_lengkap' => 'Perumahan Griya Indah Blok C3, Purbalingga',
                'kecematan' => 'Kalimanah',
                'kode_pos' => '53371',
                'telepon' => null,
                'created_at' => Carbon::now(),
            ],
            [
                'nama_penerima' => 'Agus Supriyadi',
                'no_hp' => '081345678901',
                'alamat_lengkap' => 'Jl. HR Bunyamin, Kampus Unsoed, Purwokerto',
                'kecematan' => 'Purwokerto Utara',
                'kode_pos' => '53122',
                'telepon' => '0281-112233',
                'created_at' => Carbon::now(),
            ],
            [
                'nama_penerima' => 'Lestari Widya',
                'no_hp' => '085677889900',
                'alamat_lengkap' => 'Jl. Raya Bobotsari No. 123, Purbalingga',
                'kecematan' => 'Bobotsari',
                'kode_pos' => '53353',
                'telepon' => null,
                'created_at' => Carbon::now(),
            ],
            [
                'nama_penerima' => 'Andi Saputra',
                'no_hp' => '089912345678',
                'alamat_lengkap' => 'Komplek Taman Hijau, Purwokerto Timur',
                'kecematan' => 'Purwokerto Timur',
                'kode_pos' => '53141',
                'telepon' => '0281-778899',
                'created_at' => Carbon::now(),
            ],
        ]);
    }
}