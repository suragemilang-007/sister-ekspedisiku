import db from "../config/db.js";

export async function alamatDeleteHandler(data) {
    console.log(data);
    await db.execute(`DELETE FROM alamat_tujuan WHERE uid=?`, [data.uid]);
    console.log("🗑️ Alamat dihapus:", data.uid);
}
