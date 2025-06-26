import db from "../config/db.js";

export async function updateZonaHandler(data) {
    await db.execute(
        `
        UPDATE zona_pengiriman 
        SET id_layanan=?, nama_zona=?, kecamatan_asal=?, kecamatan_tujuan=?, biaya_tambahan=?
        WHERE id_zona=?
    `,
        [
            data.id_layanan,
            data.nama_zona,
            data.kecamatan_asal,
            data.kecamatan_tujuan,
            data.biaya_tambahan,
            data.id_zona,
        ]
    );

    console.log("✅ Informasi Zona Pengiriman diperbarui:", data.id_zona);
}
