<?php

namespace Database\Seeders;

use App\Models\User;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;


class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // User::factory(10)->create();

        $this->call([
            PenggunaSeeder::class,
            KurirSeeder::class,
            AlamatTujuanSeeder::class,
            LayananPaketSeeder::class,
            ZonaPengirimanSeeder::class,
            AlamatPenjemputanSeeder::class,
            PengirimanSeeder::class,

            PenugasanKurirSeeder::class,
        ]);
    }
}