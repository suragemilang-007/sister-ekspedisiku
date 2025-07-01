import db from "../config/db.js";

export async function kurirDeleteHandler(data) {
    await db.execute(`DELETE FROM kurir WHERE id_kurir=?`, [
        data.id_kurir,
    ]);
    console.log("🗑️ Kurir dihapus:", data.id_kurir);
}