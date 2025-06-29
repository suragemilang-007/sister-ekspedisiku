import db from "../config/db.js";

export async function kurirUpdateInfoHandler(data) {
    await db.execute(
        `
        UPDATE kurir 
        SET nama = ?, email = ?, nohp = ?, alamat = ?, foto = ?, status = ? 
        WHERE id_kurir = ?
    `,
        [
            data.nama,
            data.email,
            data.nohp,
            data.alamat,
            data.foto || null,
            data.status || 'AKTIF',
            data.id_kurir,
        ]
    );
    console.log("✅ Informasi kurir diperbarui:", data.id_kurir);
}
