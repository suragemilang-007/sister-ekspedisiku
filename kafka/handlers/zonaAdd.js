// app/handlers/zonaHandler.js

import db from "../config/db.js";

/**
 * Menangani penambahan zona pengiriman baru ke database, disesuaikan dengan skema tabel.
 * @param {object} data - Data zona pengiriman yang diterima dari Kafka.
 * @param {number} data.id_layanan - ID layanan terkait zona.
 * @param {string} data.nama_zona
 * @param {string} data.kecamatan_asal
 * @param {string} data.kecamatan_tujuan
 * @param {number} data.biaya_tambahan
 */
export async function zonaCreateHandler(data) {
    const { id_layanan, nama_zona, kecamatan_asal, kecamatan_tujuan, biaya_tambahan } = data;

    try {
        await db.execute(
            `
            INSERT INTO zona_pengiriman (
                id_layanan, nama_zona, kecamatan_asal, kecamatan_tujuan, biaya_tambahan
            ) VALUES (?, ?, ?, ?, ?)
            `,
            [
                id_layanan,
                nama_zona,
                kecamatan_asal,
                kecamatan_tujuan,
                biaya_tambahan,
            ]
        );
        console.log(`🌍 Zona pengiriman baru ditambahkan: ${nama_zona} (Asal: ${kecamatan_asal}, Tujuan: ${kecamatan_tujuan}, Biaya: Rp${biaya_tambahan})`);
    } catch (error) {
        // Tangani error jika ada duplikasi atau masalah DB lainnya
        if (error.code === 'ER_DUP_ENTRY') { // Contoh MySQL duplicate entry error code
            console.error(`❌ Gagal menambahkan zona: Kombinasi data sudah ada (Zona: ${nama_zona}, Asal: ${kecamatan_asal}, Tujuan: ${kecamatan_tujuan}).`);
        } else {
            console.error(`❌ Error saat menambahkan zona pengiriman '${nama_zona}':`, error);
        }
        throw error; // Lemparkan kembali error agar bisa ditangkap di consumer utama
    }
}