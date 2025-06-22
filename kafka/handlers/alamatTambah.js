import db from "../config/db.js";

export async function alamatTambahHandler(data) {
    const now = new Date();
    await db.execute(
        `
        INSERT INTO alamat_tujuan (
            id_pengirim, nama_penerima, no_hp, alamat_lengkap, kecamatan, kode_pos, keterangan_alamat, created_at
        ) VALUES (?, ?, ?, ?, ?, ?, ?, ?)
    `,
        [
            data.id_pengirim,
            data.nama_penerima,
            data.no_hp,
            data.alamat_lengkap,
            data.kecamatan,
            data.kode_pos,
            data.keterangan_alamat || null,
            now,
        ]
    );
    console.log("📦 Alamat tujuan ditambahkan:", data.nama_penerima);
}
