<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Carbon;

class AlamatPenjemputanSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('alamat_penjemputan')->insert([
            [
                'uid' => '00000000-0000-0000-0000-000000000001',
                'id_pengirim' => 'USR00000-0000-0000-0000-000000000008',
                'nama_pengirim' => 'Budi Santoso',
                'no_hp' => '081234567890',
                'alamat_lengkap' => 'Jl. Melati No. 12, Jakarta Selatan',
                'kecamatan' => 'Ajibarang',
                'kode_pos' => '12140',
                'keterangan_alamat' => 'Rumah warna putih pagar besi',
                'created_at' => Carbon::now(),
            ],
            [
                'uid' => '00000000-0000-0000-0000-000000000002',
                'id_pengirim' => 'USR00000-0000-0000-0000-000000000008',
                'nama_pengirim' => 'Ani Lestari',
                'no_hp' => '081987654321',
                'alamat_lengkap' => 'Jl. Kenanga No. 45, Bandung',
                'kecamatan' => 'Purwokerto Barat',
                'kode_pos' => '40172',
                'keterangan_alamat' => 'Dekat warung Bu Sari',
                'created_at' => Carbon::now(),
            ],
            [
                'uid' => '00000000-0000-0000-0000-000000000003',
                'id_pengirim' => 'USR00000-0000-0000-0000-000000000007',
                'nama_pengirim' => 'Dedi Kurniawan',
                'no_hp' => '082233445566',
                'alamat_lengkap' => 'Jl. Cempaka Raya No. 9, Surabaya',
                'kecamatan' => 'Purwokerto Barat',
                'kode_pos' => '60243',
                'keterangan_alamat' => 'Ruko depan Alfamart',
                'created_at' => Carbon::now(),
            ],
        ]);
    }
}