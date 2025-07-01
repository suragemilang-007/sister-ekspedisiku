import db from "../config/db.js";

export async function penugasanKurirUpdateHandler(data) {
    await db.execute(
        `UPDATE penugasan_kurir SET status = ?, catatan = ?, updated_at = NOW() WHERE id_penugasan = ?`,
        [data.status, data.catatan || null, data.id_penugasan]
    );
    console.log(`Status penugasan_kurir ${data.id_penugasan} diperbarui ke ${data.status}`);
} 