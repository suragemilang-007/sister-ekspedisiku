import db from "../config/db.js";

export async function updateLayananHandler(data) {
    await db.execute(
        `
        UPDATE layanan_paket
        SET nama_layanan=?, deskripsi=?, min_berat=?, max_berat=?, harga_dasar=?
        WHERE id_layanan=?
    `,
        [
            data.nama_layanan,
            data.deskripsi,
            data.min_berat,
            data.max_berat,
            data.harga_dasar,
            data.id_layanan,
        ]
    );

    console.log("✅ Informasi Layanan Paket diperbarui:", data.id_layanan);
}
