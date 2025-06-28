import db from "../config/db.js";

export async function alamatPenjemputanDeleteHandler(data) {
    await db.execute(`DELETE FROM alamat_penjemputan WHERE uid=?`, [data.uid]);
    console.log("🗑️ Alamat dihapus:", data.uid);
}
