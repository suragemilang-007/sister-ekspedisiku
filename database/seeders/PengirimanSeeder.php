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
        $data = [
            [
                'id_pengirim' => 8,
                'id_alamat_tujuan' => 1,
                'total_biaya' => 20000,
                'id_layanan' => 1,
                'status' => 'DIPROSES',
                'nomor_resi' => 'RESI0001',
                'catatan_opsional' => 'Antar sampai depan rumah',
                'keterangan_batal' => null,
                'created_at' => Carbon::now()
            ],
            [
                'id_pengirim' => 6,
                'id_alamat_tujuan' => 2,
                'total_biaya' => 25000,
                'id_layanan' => 2,
                'status' => 'DIBAYAR',
                'nomor_resi' => 'RESI0002',
                'catatan_opsional' => null,
                'keterangan_batal' => null,
                'created_at' => Carbon::now()
            ],
            [
                'id_pengirim' => 6,
                'id_alamat_tujuan' => 3,
                'total_biaya' => 22000,
                'id_layanan' => 3,
                'status' => 'DIKIRIM',
                'nomor_resi' => 'RESI0003',
                'catatan_opsional' => 'Isi rapuh, mohon hati-hati',
                'keterangan_batal' => null,
                'created_at' => Carbon::now()
            ],
            [
                'id_pengirim' => 7,
                'id_alamat_tujuan' => 4,
                'total_biaya' => 21000,
                'id_layanan' => 1,
                'status' => 'DITERIMA',
                'nomor_resi' => 'RESI0004',
                'catatan_opsional' => null,
                'keterangan_batal' => null,
                'created_at' => Carbon::now()
            ],
            [
                'id_pengirim' => 7,
                'id_alamat_tujuan' => 5,
                'total_biaya' => 23000,
                'id_layanan' => 2,
                'status' => 'DIBATALKAN',
                'nomor_resi' => 'RESI0005',
                'catatan_opsional' => 'Pembatalan karena alamat tidak ditemukan',
                'keterangan_batal' => 'Alamat tidak valid',
                'created_at' => Carbon::now()
            ],
            [
                'id_pengirim' => 8,
                'id_alamat_tujuan' => 1,
                'total_biaya' => 24000,
                'id_layanan' => 3,
                'status' => 'DIPROSES',
                'nomor_resi' => 'RESI0006',
                'catatan_opsional' => 'Mohon konfirmasi saat sampai',
                'keterangan_batal' => null,
                'created_at' => Carbon::now()
            ],
            [
                'id_pengirim' => 8,
                'id_alamat_tujuan' => 1,
                'total_biaya' => 26000,
                'id_layanan' => 1,
                'status' => 'DIKIRIM',
                'nomor_resi' => 'RESI0007',
                'catatan_opsional' => 'Barang elektronik',
                'keterangan_batal' => null,
                'created_at' => Carbon::now()
            ],
            [
                'id_pengirim' => 8,
                'id_alamat_tujuan' => 2,
                'total_biaya' => 28000,
                'id_layanan' => 2,
                'status' => 'DITERIMA',
                'nomor_resi' => 'RESI0008',
                'catatan_opsional' => null,
                'keterangan_batal' => null,
                'created_at' => Carbon::now()
            ],
            [
                'id_pengirim' => 8,
                'id_alamat_tujuan' => 4,
                'total_biaya' => 29000,
                'id_layanan' => 3,
                'status' => 'DIBAYAR',
                'nomor_resi' => 'RESI0009',
                'catatan_opsional' => 'Cepat sampai ya!',
                'keterangan_batal' => null,
                'created_at' => Carbon::now()
            ],
            [
                'id_pengirim' => 6,
                'id_alamat_tujuan' => 4,
                'total_biaya' => 30000,
                'id_layanan' => 1,
                'status' => 'DIBATALKAN',
                'nomor_resi' => 'RESI0010',
                'catatan_opsional' => null,
                'keterangan_batal' => 'Pengirim membatalkan pesanan',
                'created_at' => Carbon::now()
            ],
            [
                'id_pengirim' => 6,
                'id_alamat_tujuan' => 3,
                'total_biaya' => 22000,
                'id_layanan' => 2,
                'status' => 'DIPROSES',
                'nomor_resi' => 'RESI0011',
                'catatan_opsional' => 'Harap hubungi penerima sebelum sampai',
                'keterangan_batal' => null,
                'created_at' => Carbon::now()
            ],
            [
                'id_pengirim' => 8,
                'id_alamat_tujuan' => 2,
                'total_biaya' => 24500,
                'id_layanan' => 3,
                'status' => 'DITERIMA',
                'nomor_resi' => 'RESI0012',
                'catatan_opsional' => null,
                'keterangan_batal' => null,
                'created_at' => Carbon::now()
            ],
            [
                'id_pengirim' => 8,
                'id_alamat_tujuan' => 1,
                'total_biaya' => 26000,
                'id_layanan' => 1,
                'status' => 'DITERIMA',
                'nomor_resi' => 'RESI0013',
                'catatan_opsional' => 'Jangan dititipkan ke tetangga',
                'keterangan_batal' => null,
                'created_at' => Carbon::now()
            ],
            [
                'id_pengirim' => 8,
                'id_alamat_tujuan' => 2,
                'total_biaya' => 23000,
                'id_layanan' => 2,
                'status' => 'DITERIMA',
                'nomor_resi' => 'RESI0014',
                'catatan_opsional' => null,
                'keterangan_batal' => null,
                'created_at' => Carbon::now()
            ],
            [
                'id_pengirim' => 7,
                'id_alamat_tujuan' => 3,
                'total_biaya' => 25500,
                'id_layanan' => 3,
                'status' => 'DIBATALKAN',
                'nomor_resi' => 'RESI0015',
                'catatan_opsional' => 'Penerima tidak di rumah',
                'keterangan_batal' => 'Penerima tidak dapat dihubungi',
                'created_at' => Carbon::now()
            ],
            [
                'id_pengirim' => 7,
                'id_alamat_tujuan' => 4,
                'total_biaya' => 27000,
                'id_layanan' => 1,
                'status' => 'DIPROSES',
                'nomor_resi' => 'RESI0016',
                'catatan_opsional' => null,
                'keterangan_batal' => null,
                'created_at' => Carbon::now()
            ],
            [
                'id_pengirim' => 8,
                'id_alamat_tujuan' => 2,
                'total_biaya' => 25000,
                'id_layanan' => 2,
                'status' => 'DIKIRIM',
                'nomor_resi' => 'RESI0017',
                'catatan_opsional' => 'Paket makanan',
                'keterangan_batal' => null,
                'created_at' => Carbon::now()
            ],
            [
                'id_pengirim' => 8,
                'id_alamat_tujuan' => 2,
                'total_biaya' => 27500,
                'id_layanan' => 3,
                'status' => 'DITERIMA',
                'nomor_resi' => 'RESI0018',
                'catatan_opsional' => 'Terima kasih',
                'keterangan_batal' => null,
                'created_at' => Carbon::now()
            ],
            [
                'id_pengirim' => 8,
                'id_alamat_tujuan' => 1,
                'total_biaya' => 29000,
                'id_layanan' => 1,
                'status' => 'DIKIRIM',
                'nomor_resi' => 'RESI0019',
                'catatan_opsional' => null,
                'keterangan_batal' => null,
                'created_at' => Carbon::now()
            ],
            [
                'id_pengirim' => 8,
                'id_alamat_tujuan' => 1,
                'total_biaya' => 31000,
                'id_layanan' => 2,
                'status' => 'DIBATALKAN',
                'nomor_resi' => 'RESI0020',
                'catatan_opsional' => null,
                'keterangan_batal' => 'Kurir mengalami kendala teknis',
                'created_at' => Carbon::now()
            ],
            [
                'id_pengirim' => 8,
                'id_alamat_tujuan' => 1,
                'total_biaya' => 29000,
                'id_layanan' => 2,
                'status' => 'DIKIRIM',
                'nomor_resi' => 'RESI0021',
                'catatan_opsional' => null,
                'keterangan_batal' => null,
                'created_at' => Carbon::now()
            ],
            [
                'id_pengirim' => 6,
                'id_alamat_tujuan' => 1,
                'total_biaya' => 29000,
                'id_layanan' => 2,
                'status' => 'DIKIRIM',
                'nomor_resi' => 'RESI0022',
                'catatan_opsional' => null,
                'keterangan_batal' => null,
                'created_at' => Carbon::now()
            ],
            [
                'id_pengirim' => 7,
                'id_alamat_tujuan' => 2,
                'total_biaya' => 29000,
                'id_layanan' => 3,
                'status' => 'DIKIRIM',
                'nomor_resi' => 'RESI0023',
                'catatan_opsional' => null,
                'keterangan_batal' => null,
                'created_at' => Carbon::now()
            ],

        ];

        DB::table('pengiriman')->insert($data);
    }
}