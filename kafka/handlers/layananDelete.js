import db from "../config/db.js";

export async function layananDeleteHandler(data) {
    await db.execute(`DELETE FROM layanan_paket WHERE id_layanan=?`, [
        data.id_layanan,
    ]);
    console.log("🗑️ Layanan Paket dihapus:", data.id_layanan);
}