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
            ['nama' => 'Arbi Restandi', 'email' => 'arbi@email.com', 'nohp' => '081234567891', 'alamat' => 'Jl. Sudirman No.10, Purwokerto', 'foto' => null, 'sandi_hash' => Hash::make('password123'), 'kendaraan' => 'MOBIL', 'status' => 'AKTIF'],
            ['nama' => 'Ridho Armansyah', 'email' => 'ridho@email.com', 'nohp' => '081234567892', 'alamat' => 'Jl. Gerilya Timur No.5, Purwokerto', 'foto' => null, 'sandi_hash' => Hash::make('password123'), 'kendaraan' => 'MOBIL', 'status' => 'NONAKTIF'],
            ['nama' => 'Adit Nur Setiawan', 'email' => 'adit@email.com', 'nohp' => '081234567893', 'alamat' => 'Jl. MT Haryono No.7, Purbalingga', 'foto' => null, 'sandi_hash' => Hash::make('password123'), 'kendaraan' => 'Motor', 'status' => 'AKTIF'],
            ['nama' => 'Putra Gilang', 'email' => 'putra@email.com', 'nohp' => '081234567894', 'alamat' => 'Jl. Kolonel Sugiri No.3, Purbalingga', 'foto' => null, 'sandi_hash' => Hash::make('password123'), 'kendaraan' => 'Motor', 'status' => 'AKTIF'],
            ['nama' => 'Zhu Ting', 'email' => 'zhuting@email.com', 'nohp' => '081234560031', 'alamat' => 'Jl. Raya Sokaraja No.5, Banyumas', 'foto' => null, 'sandi_hash' => Hash::make('password123'), 'kendaraan' => 'Motor', 'status' => 'AKTIF'],
            ['nama' => 'April Ross', 'email' => 'april@email.com', 'nohp' => '081234560032', 'alamat' => 'Jl. Karanglewas No.8, Purwokerto Barat', 'foto' => null, 'sandi_hash' => Hash::make('password123'), 'kendaraan' => 'MOBIL', 'status' => 'AKTIF'],
            ['nama' => 'Jordan Larson', 'email' => 'larson@email.com', 'nohp' => '081234560033', 'alamat' => 'Jl. Raya Kutasari No.3, Purbalingga', 'foto' => null, 'sandi_hash' => Hash::make('password123'), 'kendaraan' => 'Motor', 'status' => 'AKTIF'],
            ['nama' => 'Eko Fahrezi', 'email' => 'eko@email.com', 'nohp' => '081234560034', 'alamat' => 'Jl. Sunan Bonang No.4, Purwokerto Timur', 'foto' => null, 'sandi_hash' => Hash::make('password123'), 'kendaraan' => 'Motor', 'status' => 'AKTIF'],
            ['nama' => 'Farhan Halim', 'email' => 'farhan@email.com', 'nohp' => '081234560035', 'alamat' => 'Jl. Raya Kemangkon No.10, Purbalingga', 'foto' => null, 'sandi_hash' => Hash::make('password123'), 'kendaraan' => 'MOBIL', 'status' => 'AKTIF'],
            ['nama' => 'Giba de Godoy', 'email' => 'giba@email.com', 'nohp' => '081234560036', 'alamat' => 'Jl. Dr. Soetomo No.3, Banyumas', 'foto' => null, 'sandi_hash' => Hash::make('password123'), 'kendaraan' => 'Motor', 'status' => 'AKTIF'],
            ['nama' => 'Yolla Yuliana', 'email' => 'yolla@email.com', 'nohp' => '081234560037', 'alamat' => 'Jl. Sultan Agung No.7, Purwokerto Selatan', 'foto' => null, 'sandi_hash' => Hash::make('password123'), 'kendaraan' => 'MOBIL', 'status' => 'AKTIF'],
            ['nama' => 'Ricardo Lucarelli', 'email' => 'lucarelli@email.com', 'nohp' => '081234560038', 'alamat' => 'Jl. Raya Karanglewas No.2, Banyumas', 'foto' => null, 'sandi_hash' => Hash::make('password123'), 'kendaraan' => 'Motor', 'status' => 'AKTIF'],
            ['nama' => 'Megawati Hangestri', 'email' => 'megawati@email.com', 'nohp' => '081234560039', 'alamat' => 'Jl. Bumiayu No.15, Purbalingga', 'foto' => null, 'sandi_hash' => Hash::make('password123'), 'kendaraan' => 'MOBIL', 'status' => 'AKTIF'],
            ['nama' => 'Matt Anderson', 'email' => 'anderson@email.com', 'nohp' => '081234560040', 'alamat' => 'Jl. Purwanegara No.11, Purwokerto Utara', 'foto' => null, 'sandi_hash' => Hash::make('password123'), 'kendaraan' => 'Motor', 'status' => 'AKTIF'],
            ['nama' => 'Yoga Adi Nugraha', 'email' => 'yoga@email.com', 'nohp' => '081234567895', 'alamat' => 'Jl. Gatot Subroto No.1, Purwokerto', 'foto' => null, 'sandi_hash' => Hash::make('password123'), 'kendaraan' => 'MOBIL', 'status' => 'NONAKTIF'],
            ['nama' => 'Rafi Hadadi', 'email' => 'rafi@email.com', 'nohp' => '081234567896', 'alamat' => 'Jl. Mayjen Sungkono No.8, Purwokerto', 'foto' => null, 'sandi_hash' => Hash::make('password123'), 'kendaraan' => 'MOBIL', 'status' => 'AKTIF'],
            ['nama' => 'Asep Ong', 'email' => 'asep@email.com', 'nohp' => '081234567897', 'alamat' => 'Jl. Letjen Suprapto No.2, Purbalingga', 'foto' => null, 'sandi_hash' => Hash::make('password123'), 'kendaraan' => 'MOBIL', 'status' => 'NONAKTIF'],
            ['nama' => 'Suwa Miku', 'email' => 'suwa@email.com', 'nohp' => '081234567898', 'alamat' => 'Jl. Beji Timur No.6, Purwokerto', 'foto' => null, 'sandi_hash' => Hash::make('password123'), 'kendaraan' => 'MOBIL', 'status' => 'AKTIF'],
            ['nama' => 'Novan Bagus', 'email' => 'novan@email.com', 'nohp' => '081234567899', 'alamat' => 'Jl. Raya Kalimanah No.9, Purbalingga', 'foto' => null, 'sandi_hash' => Hash::make('password123'), 'kendaraan' => 'MOBIL', 'status' => 'AKTIF'],
            ['nama' => 'Aruran Deisu', 'email' => 'aruran@email.com', 'nohp' => '081234567800', 'alamat' => 'Jl. Karangwangkal No.4, Purwokerto', 'foto' => null, 'sandi_hash' => Hash::make('password123'), 'kendaraan' => 'Motor', 'status' => 'NONAKTIF'],
            ['nama' => 'Xanther Zulkair', 'email' => 'xanther@email.com', 'nohp' => '081234560001', 'alamat' => 'Jl. Merdeka Barat No.12, Jakarta', 'foto' => null, 'sandi_hash' => Hash::make('password123'), 'kendaraan' => 'MOBIL', 'status' => 'AKTIF'],
            ['nama' => 'Qalifz Hanindra', 'email' => 'qalifz@email.com', 'nohp' => '081234560002', 'alamat' => 'Jl. A. Yani No.21, Surabaya', 'foto' => null, 'sandi_hash' => Hash::make('password123'), 'kendaraan' => 'Motor', 'status' => 'AKTIF'],
            ['nama' => 'Zyvion Septa', 'email' => 'zyvion@email.com', 'nohp' => '081234560003', 'alamat' => 'Jl. Pemuda No.5, Bandung', 'foto' => null, 'sandi_hash' => Hash::make('password123'), 'kendaraan' => 'Motor', 'status' => 'AKTIF'],
            ['nama' => 'Tharez Mulyadi', 'email' => 'tharez@email.com', 'nohp' => '081234560004', 'alamat' => 'Jl. Gajah Mada No.19, Medan', 'foto' => null, 'sandi_hash' => Hash::make('password123'), 'kendaraan' => 'MOBIL', 'status' => 'AKTIF'],
            ['nama' => 'Zyraqul Anwari', 'email' => 'zyraqul@email.com', 'nohp' => '081234560005', 'alamat' => 'Jl. Diponegoro No.7, Makassar', 'foto' => null, 'sandi_hash' => Hash::make('password123'), 'kendaraan' => 'Motor', 'status' => 'AKTIF'],
            ['nama' => 'Lionel Messi', 'email' => 'messi@email.com', 'nohp' => '081234560011', 'alamat' => 'Jl. Pemuda No.2, Purwokerto', 'foto' => null, 'sandi_hash' => Hash::make('password123'), 'kendaraan' => 'Motor', 'status' => 'AKTIF'],
            ['nama' => 'Cristiano Ronaldo', 'email' => 'ronaldo@email.com', 'nohp' => '081234560012', 'alamat' => 'Jl. Letjen S. Parman No.3, Banyumas', 'foto' => null, 'sandi_hash' => Hash::make('password123'), 'kendaraan' => 'MOBIL', 'status' => 'AKTIF'],
            ['nama' => 'Kylian Mbappe', 'email' => 'mbappe@email.com', 'nohp' => '081234560013', 'alamat' => 'Jl. HR Soebrantas No.8, Purbalingga', 'foto' => null, 'sandi_hash' => Hash::make('password123'), 'kendaraan' => 'Motor', 'status' => 'AKTIF'],
            ['nama' => 'Erling Haaland', 'email' => 'haaland@email.com', 'nohp' => '081234560014', 'alamat' => 'Jl. Soekarno Hatta No.4, Purwokerto Selatan', 'foto' => null, 'sandi_hash' => Hash::make('password123'), 'kendaraan' => 'MOBIL', 'status' => 'AKTIF'],
            ['nama' => 'Neymar Jr', 'email' => 'neymar@email.com', 'nohp' => '081234560015', 'alamat' => 'Jl. Gunung Slamet No.5, Kalimanah, Purbalingga', 'foto' => null, 'sandi_hash' => Hash::make('password123'), 'kendaraan' => 'Motor', 'status' => 'AKTIF'],
            ['nama' => 'Mohamed Salah', 'email' => 'salah@email.com', 'nohp' => '081234560016', 'alamat' => 'Jl. Dr. Angka No.12, Purwokerto Barat', 'foto' => null, 'sandi_hash' => Hash::make('password123'), 'kendaraan' => 'MOBIL', 'status' => 'AKTIF'],
            ['nama' => 'Son Heung-min', 'email' => 'son@email.com', 'nohp' => '081234560017', 'alamat' => 'Jl. Raya Patikraja No.6, Banyumas', 'foto' => null, 'sandi_hash' => Hash::make('password123'), 'kendaraan' => 'Motor', 'status' => 'AKTIF'],
            ['nama' => 'Luka Modric', 'email' => 'modric@email.com', 'nohp' => '081234560018', 'alamat' => 'Jl. Karangpucung No.9, Purwokerto', 'foto' => null, 'sandi_hash' => Hash::make('password123'), 'kendaraan' => 'MOBIL', 'status' => 'AKTIF'],
            ['nama' => 'Marcus Rashford', 'email' => 'rashford@email.com', 'nohp' => '081234560019', 'alamat' => 'Jl. Raya Bobotsari No.13, Purbalingga', 'foto' => null, 'sandi_hash' => Hash::make('password123'), 'kendaraan' => 'Motor', 'status' => 'AKTIF'],
            ['nama' => 'LeBron James', 'email' => 'lebron@email.com', 'nohp' => '081234560021', 'alamat' => 'Jl. Diponegoro No.3, Purwokerto Timur', 'foto' => null, 'sandi_hash' => Hash::make('password123'), 'kendaraan' => 'MOBIL', 'status' => 'AKTIF'],
            ['nama' => 'Stephen Curry', 'email' => 'curry@email.com', 'nohp' => '081234560022', 'alamat' => 'Jl. Jenderal Sudirman No.18, Purbalingga', 'foto' => null, 'sandi_hash' => Hash::make('password123'), 'kendaraan' => 'Motor', 'status' => 'AKTIF'],
            ['nama' => 'Kevin Durant', 'email' => 'durant@email.com', 'nohp' => '081234560023', 'alamat' => 'Jl. Letjen Sutoyo No.4, Banyumas', 'foto' => null, 'sandi_hash' => Hash::make('password123'), 'kendaraan' => 'MOBIL', 'status' => 'AKTIF'],
            ['nama' => 'Giannis Antetokounmpo', 'email' => 'giannis@email.com', 'nohp' => '081234560024', 'alamat' => 'Jl. Raya Baturaden No.6, Purwokerto Utara', 'foto' => null, 'sandi_hash' => Hash::make('password123'), 'kendaraan' => 'Motor', 'status' => 'AKTIF'],
            ['nama' => 'Jayson Tatum', 'email' => 'tatum@email.com', 'nohp' => '081234560025', 'alamat' => 'Jl. Gunung Tugel No.5, Banyumas', 'foto' => null, 'sandi_hash' => Hash::make('password123'), 'kendaraan' => 'MOBIL', 'status' => 'AKTIF'],
            ['nama' => 'Luka Doncic', 'email' => 'luka@email.com', 'nohp' => '081234560026', 'alamat' => 'Jl. Mayjen Panjaitan No.11, Purbalingga', 'foto' => null, 'sandi_hash' => Hash::make('password123'), 'kendaraan' => 'Motor', 'status' => 'AKTIF'],
            ['nama' => 'Jimmy Butler', 'email' => 'butler@email.com', 'nohp' => '081234560027', 'alamat' => 'Jl. Kalikajar No.14, Purwokerto Barat', 'foto' => null, 'sandi_hash' => Hash::make('password123'), 'kendaraan' => 'MOBIL', 'status' => 'AKTIF'],
            ['nama' => 'Kyrie Irving', 'email' => 'kyrie@email.com', 'nohp' => '081234560028', 'alamat' => 'Jl. Raya Ajibarang No.17, Banyumas', 'foto' => null, 'sandi_hash' => Hash::make('password123'), 'kendaraan' => 'Motor', 'status' => 'AKTIF'],
            ['nama' => 'Joel Embiid', 'email' => 'embiid@email.com', 'nohp' => '081234560029', 'alamat' => 'Jl. Raya Karangmoncol No.7, Purbalingga', 'foto' => null, 'sandi_hash' => Hash::make('password123'), 'kendaraan' => 'MOBIL', 'status' => 'AKTIF'],
            ['nama' => 'Devin Booker', 'email' => 'booker@email.com', 'nohp' => '081234560030', 'alamat' => 'Jl. Martadireja No.19, Purwokerto Selatan', 'foto' => null, 'sandi_hash' => Hash::make('password123'), 'kendaraan' => 'Motor', 'status' => 'AKTIF'],
            ['nama' => 'Sadio Mane', 'email' => 'mane@email.com', 'nohp' => '081234560020', 'alamat' => 'Jl. S. Parman No.15, Banyumas', 'foto' => null, 'sandi_hash' => Hash::make('password123'), 'kendaraan' => 'MOBIL', 'status' => 'AKTIF'],
            ['nama' => 'Vhanel Drajat', 'email' => 'vhanel@email.com', 'nohp' => '081234560006', 'alamat' => 'Jl. Sudirman No.30, Yogyakarta', 'foto' => null, 'sandi_hash' => Hash::make('password123'), 'kendaraan' => 'MOBIL', 'status' => 'AKTIF'],
            ['nama' => 'Nhexon Rasyid', 'email' => 'nhexon@email.com', 'nohp' => '081234560007', 'alamat' => 'Jl. Ahmad Yani No.22, Semarang', 'foto' => null, 'sandi_hash' => Hash::make('password123'), 'kendaraan' => 'Motor', 'status' => 'AKTIF'],
            ['nama' => 'Ulzhy Farhan', 'email' => 'ulzhy@email.com', 'nohp' => '081234560008', 'alamat' => 'Jl. Teuku Umar No.11, Denpasar', 'foto' => null, 'sandi_hash' => Hash::make('password123'), 'kendaraan' => 'Motor', 'status' => 'AKTIF'],
            ['nama' => 'Jhufrain Hibatullah', 'email' => 'jhufrain@email.com', 'nohp' => '081234560009', 'alamat' => 'Jl. Jendral Sudirman No.8, Balikpapan', 'foto' => null, 'sandi_hash' => Hash::make('password123'), 'kendaraan' => 'MOBIL', 'status' => 'AKTIF'],
            ['nama' => 'Myqha Rezaldi', 'email' => 'myqha@email.com', 'nohp' => '081234560010', 'alamat' => 'Jl. Sisingamangaraja No.17, Palembang', 'foto' => null, 'sandi_hash' => Hash::make('password123'), 'kendaraan' => 'Motor', 'status' => 'AKTIF'],
            ['nama' => 'Patrick Mahomes', 'email' => 'mahomes@email.com', 'nohp' => '081234560041', 'alamat' => 'Jl. Raya Sumbang No.2, Banyumas', 'foto' => null, 'sandi_hash' => Hash::make('password123'), 'kendaraan' => 'MOBIL', 'status' => 'AKTIF'],
            ['nama' => 'Joe Burrow', 'email' => 'burrow@email.com', 'nohp' => '081234560042', 'alamat' => 'Jl. Dr. Angka No.8, Purwokerto Timur', 'foto' => null, 'sandi_hash' => Hash::make('password123'), 'kendaraan' => 'Motor', 'status' => 'AKTIF'],
            ['nama' => 'Aaron Donald', 'email' => 'donald@email.com', 'nohp' => '081234560043', 'alamat' => 'Jl. Raya Padamara No.10, Purbalingga', 'foto' => null, 'sandi_hash' => Hash::make('password123'), 'kendaraan' => 'MOBIL', 'status' => 'AKTIF'],
            ['nama' => 'Davante Adams', 'email' => 'davante@email.com', 'nohp' => '081234560044', 'alamat' => 'Jl. Letjen S. Parman No.5, Purwokerto Selatan', 'foto' => null, 'sandi_hash' => Hash::make('password123'), 'kendaraan' => 'Motor', 'status' => 'AKTIF'],
            ['nama' => 'Josh Allen', 'email' => 'allen@email.com', 'nohp' => '081234560045', 'alamat' => 'Jl. Raya Bobotsari No.12, Purbalingga', 'foto' => null, 'sandi_hash' => Hash::make('password123'), 'kendaraan' => 'MOBIL', 'status' => 'AKTIF'],
            ['nama' => 'Jalen Hurts', 'email' => 'hurts@email.com', 'nohp' => '081234560046', 'alamat' => 'Jl. Merdeka No.9, Banyumas', 'foto' => null, 'sandi_hash' => Hash::make('password123'), 'kendaraan' => 'Motor', 'status' => 'AKTIF'],
            ['nama' => 'Nick Bosa', 'email' => 'bosa@email.com', 'nohp' => '081234560047', 'alamat' => 'Jl. Sawangan No.3, Purwokerto Barat', 'foto' => null, 'sandi_hash' => Hash::make('password123'), 'kendaraan' => 'Motor', 'status' => 'AKTIF'],
            ['nama' => 'Travis Kelce', 'email' => 'kelce@email.com', 'nohp' => '081234560048', 'alamat' => 'Jl. Jatisaba No.6, Purbalingga', 'foto' => null, 'sandi_hash' => Hash::make('password123'), 'kendaraan' => 'MOBIL', 'status' => 'AKTIF'],
            ['nama' => 'Micah Parsons', 'email' => 'parsons@email.com', 'nohp' => '081234560049', 'alamat' => 'Jl. Sunan Kalijaga No.13, Purwokerto Timur', 'foto' => null, 'sandi_hash' => Hash::make('password123'), 'kendaraan' => 'Motor', 'status' => 'AKTIF'],
            ['nama' => 'Lamar Jackson', 'email' => 'lamar@email.com', 'nohp' => '081234560050', 'alamat' => 'Jl. Raya Kembaran No.7, Banyumas', 'foto' => null, 'sandi_hash' => Hash::make('password123'), 'kendaraan' => 'MOBIL', 'status' => 'AKTIF'],

        ];

        foreach ($data as &$kurir) {
            $kurir['created_at'] = now();
        }

        DB::table('kurir')->insert($data);
    }
}