import db from "../config/db.js";

export async function alamatPenjemputanEditHandler(data) {
    await db.execute(
        `
        UPDATE alamat_penjemputan 
        SET nama_pengirim=?, no_hp=?, alamat_lengkap=?, kecamatan=?, kode_pos=?, keterangan_alamat=?
        WHERE id_alamat_penjemputan=?
    `,
        [
            data.nama_pengirim,
            data.no_hp,
            data.alamat_lengkap,
            data.kecamatan,
            data.kode_pos,
            data.keterangan_alamat,
            data.id_alamat_penjemputan,
        ]
    );
    console.log("✏️ Alamat diperbarui:", data.id_alamat_penjemputan);
}
