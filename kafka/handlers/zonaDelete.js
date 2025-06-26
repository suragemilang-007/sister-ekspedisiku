import db from "../config/db.js";

export async function zonaDeleteHandler(data) {
    await db.execute(`DELETE FROM zona_pengiriman WHERE id_zona=?`, [
        data.id_zona,
    ]);
    console.log("🗑️ Zona Pengiriman dihapus:", data.id_zona);
}