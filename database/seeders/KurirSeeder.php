<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
class KurirSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $data = [
            ['nama' => 'Arbi Restandi', 'email' => 'arbi@email.com', 'nohp' => '081234567891', 'alamat' => 'Jl. Sudirman No.10, Purwokerto', 'foto' => null, 'sandi_hash' => Hash::make('password123'), 'status' => 'AKTIF'],
            ['nama' => 'Ridho Armansyah', 'email' => 'ridho@email.com', 'nohp' => '081234567892', 'alamat' => 'Jl. Gerilya Timur No.5, Purwokerto', 'foto' => null, 'sandi_hash' => Hash::make('password123'), 'status' => 'NONAKTIF'],
            ['nama' => 'Adit Nur Setiawan', 'email' => 'adit@email.com', 'nohp' => '081234567893', 'alamat' => 'Jl. MT Haryono No.7, Purbalingga', 'foto' => null, 'sandi_hash' => Hash::make('password123'), 'status' => 'AKTIF'],
            ['nama' => 'Putra Gilang', 'email' => 'putra@email.com', 'nohp' => '081234567894', 'alamat' => 'Jl. Kolonel Sugiri No.3, Purbalingga', 'foto' => null, 'sandi_hash' => Hash::make('password123'), 'status' => 'AKTIF'],
            ['nama' => 'Yoga Adi Nugraha', 'email' => 'yoga@email.com', 'nohp' => '081234567895', 'alamat' => 'Jl. Gatot Subroto No.1, Purwokerto', 'foto' => null, 'sandi_hash' => Hash::make('password123'), 'status' => 'NONAKTIF'],
            ['nama' => 'Rafi Hadadi', 'email' => 'rafi@email.com', 'nohp' => '081234567896', 'alamat' => 'Jl. Mayjen Sungkono No.8, Purwokerto', 'foto' => null, 'sandi_hash' => Hash::make('password123'), 'status' => 'AKTIF'],
            ['nama' => 'Asep Ong', 'email' => 'asep@email.com', 'nohp' => '081234567897', 'alamat' => 'Jl. Letjen Suprapto No.2, Purbalingga', 'foto' => null, 'sandi_hash' => Hash::make('password123'), 'status' => 'NONAKTIF'],
            ['nama' => 'Suwa Miku', 'email' => 'suwa@email.com', 'nohp' => '081234567898', 'alamat' => 'Jl. Beji Timur No.6, Purwokerto', 'foto' => null, 'sandi_hash' => Hash::make('password123'), 'status' => 'AKTIF'],
            ['nama' => 'Novan Bagus', 'email' => 'novan@email.com', 'nohp' => '081234567899', 'alamat' => 'Jl. Raya Kalimanah No.9, Purbalingga', 'foto' => null, 'sandi_hash' => Hash::make('password123'), 'status' => 'AKTIF'],
            ['nama' => 'Aruran Deisu', 'email' => 'aruran@email.com', 'nohp' => '081234567800', 'alamat' => 'Jl. Karangwangkal No.4, Purwokerto', 'foto' => null, 'sandi_hash' => Hash::make('password123'), 'status' => 'NONAKTIF'],
        ];

        foreach ($data as &$kurir) {
            $kurir['created_at'] = now();
        }

        DB::table('kurir')->insert($data);
    }
}