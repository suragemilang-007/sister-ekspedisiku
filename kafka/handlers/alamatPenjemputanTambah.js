import db from "../config/db.js";

export async function alamatPenjemputanTambahHandler(data) {
    const now = new Date();
    await db.execute(
        `
        INSERT INTO alamat_penjemputan (
            id_pengirim, nama_pengirim, no_hp, alamat_lengkap, kecamatan, kode_pos, keterangan_alamat, created_at
        ) VALUES (?, ?, ?, ?, ?, ?, ?, ?)
    `,
        [
            data.id_pengirim,
            data.nama_pengirim,
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
