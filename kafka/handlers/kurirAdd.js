// app/handlers/kurirHandler.js

import db from "../config/db.js";

/**
 * Menangani penambahan kurir baru ke database.
 * @param {object} data - Data kurir yang diterima dari Kafka.
 * @param {string} data.nama - Nama lengkap kurir.
 * @param {string} data.email - Email unik kurir.
 * @param {string} data.nohp - Nomor HP kurir.
 * @param {string} data.alamat - Alamat lengkap kurir.
 * @param {string} data.sandi_hash - Password yang sudah di-hash (bcrypt).
 * @param {string} data.status - Status AKTIF atau NONAKTIF.
 */
export async function kurirCreateHandler(data) {
    await db.execute(
        `
            INSERT INTO kurir (
                nama, email, nohp, alamat, foto, sandi_hash, status, created_at
            ) VALUES (?, ?, ?, ?, ?, ?, ?, NOW())
            `,
        [data.nama, data.email, data.nohp, data.alamat, data.foto || null, data.sandi_hash, data.status]
    );

    console.log(`🚚 Kurir baru ditambahkan: ${data.nama}`);
}

