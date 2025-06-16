<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Carbon\Carbon;

class PenggunaSeeder extends Seeder
{
    public function run()
    {
        $data = [
            [
                'nama' => 'Tri Anton',
                'email' => 'tri.anton@email.com',
                'tgl_lahir' => '1990-01-15',
                'nohp' => '081234567891',
                'alamat' => 'Jl. Merdeka No. 1',
                'kelamin' => 'L',
                'sandi_hash' => Hash::make('pagiseninmalas444'),
                'peran' => 'admin',
                'created_at' => Carbon::now(),
            ],
            [
                'nama' => 'Nova Ramadhan',
                'email' => 'nova.ramadhan@email.com',
                'tgl_lahir' => '1992-03-20',
                'nohp' => '081234567892',
                'alamat' => 'Jl. Sudirman No. 5',
                'kelamin' => 'L',
                'sandi_hash' => Hash::make('botaklicin123'),
                'peran' => 'admin',
                'created_at' => Carbon::now(),
            ],
            [
                'nama' => 'Ignas Surya',
                'email' => 'ignas.surya@email.com',
                'tgl_lahir' => '1988-07-09',
                'nohp' => '081234567893',
                'alamat' => 'Jl. Mawar No. 3',
                'kelamin' => 'L',
                'sandi_hash' => Hash::make('hotdog999'),
                'peran' => 'admin',
                'created_at' => Carbon::now(),
            ],
            [
                'nama' => 'Erika Setiana',
                'email' => 'erika.setiana@email.com',
                'tgl_lahir' => '1995-11-30',
                'nohp' => '081234567894',
                'alamat' => 'Jl. Anggrek No. 10',
                'kelamin' => 'P',
                'sandi_hash' => Hash::make('sayaganteng123'),
                'peran' => 'admin',
                'created_at' => Carbon::now(),
            ],
            [
                'nama' => 'Indana Zulfa',
                'email' => 'indana.zulfa@email.com',
                'tgl_lahir' => '1993-05-21',
                'nohp' => '081234567895',
                'alamat' => 'Jl. Melati No. 7',
                'kelamin' => 'P',
                'sandi_hash' => Hash::make('password123'),
                'peran' => 'admin',
                'created_at' => Carbon::now(),
            ],
            [
                'nama' => 'Dontol Maut',
                'email' => 'dontol.maut@email.com',
                'tgl_lahir' => '1985-09-12',
                'nohp' => '081234567896',
                'alamat' => 'Jl. Kenangan No. 13',
                'kelamin' => 'L',
                'sandi_hash' => Hash::make('password123'),
                'peran' => 'pelanggan',
                'created_at' => Carbon::now(),
            ],
            [
                'nama' => 'hakos baelz',
                'email' => 'baelz.hakos@email.com',
                'tgl_lahir' => '1985-09-12',
                'nohp' => '081234564123',
                'alamat' => 'Jl. Kenangan No. 13',
                'kelamin' => 'L',
                'sandi_hash' => Hash::make('password123'),
                'peran' => 'pelanggan',
                'created_at' => Carbon::now(),
            ],
            [
                'nama' => 'Ligma Boy',
                'email' => 'Ligma.Ligma@email.com',
                'tgl_lahir' => '1985-09-12',
                'nohp' => '081234567811',
                'alamat' => 'Jl. Kenangan No. 13',
                'kelamin' => 'L',
                'sandi_hash' => Hash::make('password123'),
                'peran' => 'pelanggan',
                'created_at' => Carbon::now(),
            ],
        ];

        DB::table('pengguna')->insert($data);
    }
}