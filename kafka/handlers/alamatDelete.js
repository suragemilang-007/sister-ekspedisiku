import db from "../config/db.js";

export async function alamatDeleteHandler(data) {
    await db.execute(`DELETE FROM alamat_tujuan WHERE id_alamat_tujuan=?`, [
        data.id_alamat_tujuan,
    ]);
    console.log("🗑️ Alamat dihapus:", data.id_alamat_tujuan);
}
