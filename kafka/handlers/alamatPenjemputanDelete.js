import db from "../config/db.js";

export async function alamatPenjemputanDeleteHandler(data) {
    await db.execute(
        `DELETE FROM alamat_penjemputan WHERE id_alamat_penjemputan=?`,
        [data.id_alamat_penjemputan]
    );
    console.log("🗑️ Alamat dihapus:", data.id_alamat_penjemputan);
}
