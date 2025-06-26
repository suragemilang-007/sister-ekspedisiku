// app/handlers/zonaHandler.js

import db from "../config/db.js";

/**
 * Menangani penambahan zona pengiriman baru ke database, disesuaikan dengan skema tabel.
 * @param {object} data - Data zona pengiriman yang diterima dari Kafka.
 * @param {number} data.nama_layanan - ID layanan terkait zona.
 * @param {string} data.deskripsi
 * @param {string} data.min_berat
 * @param {string} data.max_berat
 * @param {number} data.harga_dasar
 */
export async function layananCreateHandler(data) {
    const { nama_layanan, deskripsi, min_berat, max_berat, harga_dasar } = data;

    try {
        await db.execute(
            `
            INSERT INTO layanan_paket (
                nama_layanan, deskripsi, min_berat, max_berat, harga_dasar
            ) VALUES (?, ?, ?, ?, ?)
            `,
            [
                nama_layanan,
                deskripsi,
                min_berat,
                max_berat,
                harga_dasar
            ]
        );
        console.log(`🌍 Layanan paket baru ditambahkan: ${nama_layanan} (Deskripsi: ${deskripsi}, Min Berat: ${min_berat}, Max Berat: ${max_berat}, Harga Dasar: Rp${harga_dasar})`);
    } catch (error) {
        // Tangani error jika ada duplikasi atau masalah DB lainnya
        if (error.code === 'ER_DUP_ENTRY') { // Contoh MySQL duplicate entry error code
            console.error(`❌ Gagal menambahkan layanan: Kombinasi data sudah ada (Layanan: ${nama_layanan}, Deskripsi: ${deskripsi}, Min Berat: ${min_berat}, Max Berat: ${max_berat}, Harga Dasar: Rp${harga_dasar}).`);
        } else {
            console.error(`❌ Error saat menambahkan layanan '${nama_layanan}':`, error);
        }
        throw error; // Lemparkan kembali error agar bisa ditangkap di consumer utama
    }
}