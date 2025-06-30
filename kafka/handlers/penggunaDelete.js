import db from "../config/db.js";

export async function penggunaDeleteHandler(data) {
    await db.execute(`DELETE FROM pengguna WHERE uid=?`, [
        data.uid,
    ]);
    console.log("🗑️ Pengguna dihapus:", data.uid);
}