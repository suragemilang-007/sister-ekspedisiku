import db from "../config/db.js";

export async function alamatEditHandler(data) {
    await db.execute(
        `
        UPDATE alamat_tujuan 
        SET nama_penerima=?, no_hp=?, alamat_lengkap=?, kecamatan=?, kode_pos=?, keterangan_alamat=?
        WHERE uid=?
    `,
        [
            data.nama_penerima,
            data.no_hp,
            data.alamat_lengkap,
            data.kecamatan,
            data.kode_pos,
            data.keterangan_alamat,
            data.uid,
        ]
    );
    console.log("✏️ Alamat diperbarui:", data.id_alamat_tujuan);
}
