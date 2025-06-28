import db from "../config/db.js";

export async function alamatPenjemputanEditHandler(data) {
    await db.execute(
        `
        UPDATE alamat_penjemputan 
        SET nama_pengirim=?, no_hp=?, alamat_lengkap=?, kecamatan=?, kode_pos=?, keterangan_alamat=?
        WHERE uid=?
    `,
        [
            data.nama_pengirim,
            data.no_hp,
            data.alamat_lengkap,
            data.kecamatan,
            data.kode_pos,
            data.keterangan_alamat,
            data.uid,
        ]
    );
    console.log("✏️ Alamat diperbarui:", data.id_alamat_penjemputan);
}
