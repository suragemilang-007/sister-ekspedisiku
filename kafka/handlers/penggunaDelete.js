import db from "../config/db.js";

export async function penggunaDeleteHandler(data) {
    await db.execute(`DELETE FROM pengguna WHERE id_pengguna=?`, [
        data.id_pengguna,
    ]);
    console.log("🗑️ Pengguna dihapus:", data.id_pengguna);
}