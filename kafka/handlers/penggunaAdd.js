// app/handlers/penggunaHandler.js

import db from "../config/db.js";
import bcrypt from "bcrypt"; // Pastikan bcrypt diinstal: npm install bcrypt

/**
 * Menangani penambahan pengguna baru (admin) ke database.
 * @param {object} data - Data pengguna yang diterima dari Kafka.
 * @param {string} data.nama
 * @param {string} data.email
 * @param {string} data.sandi_hash - Password yang sudah di-hash dari Laravel.
 * @param {string} [data.tgl_lahir]
 * @param {string} [data.nohp]
 * @param {string} [data.alamat]
 * @param {string} data.kelamin
 * @param {string} data.peran - Contoh: 'admin'
 */
export async function penggunaTambahHandler(data) {
    const now = new Date();

    // Data.sandi_hash sudah di-hash dari Laravel, jadi langsung pakai
    // Jika Anda ingin melakukan hashing di sisi consumer (tidak direkomendasikan jika sudah di-hash di Laravel),
    // maka gunakan: const hashedPassword = await bcrypt.hash(data.password, 10);
    // Tetapi karena sudah di-hash di Laravel, kita pakai nama sandi_hash

    try {
        await db.execute(
            `
            INSERT INTO pengguna (
                uid, nama, email, sandi_hash, tgl_lahir, nohp, alamat, kelamin, peran, created_at
            ) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?)
            `,
            [
                data.uid,
                data.nama,
                data.email,
                data.sandi_hash, // Gunakan sandi_hash yang sudah di-hash dari Laravel
                data.tgl_lahir || null, // Tanggal lahir bisa null
                data.nohp || null,       // No HP bisa null
                data.alamat || null,     // Alamat bisa null
                data.kelamin,
                data.peran, // Harusnya 'admin' dari Laravel
                now,
            ]
        );
        console.log(`👤 Pengguna (Admin) baru ditambahkan: ${data.uid}`);
    } catch (error) {
        // Tangani error jika ada duplikasi email atau masalah DB lainnya
        if (error.code === 'ER_DUP_ENTRY') { // Contoh MySQL duplicate entry error code
            console.error(`❌ Gagal menambahkan pengguna: Email '${data.email}' sudah terdaftar.`);
        } else {
            console.error(`❌ Error saat menambahkan pengguna:`, error);
        }
        throw error; // Lemparkan kembali error agar bisa ditangkap di consumer utama
    }
}