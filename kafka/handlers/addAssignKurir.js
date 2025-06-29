import db from "../config/db.js";

export async function addAssignKurirHandler(data) {
    await db.execute(
        `INSERT INTO penugasan_kurir (id_pengiriman, id_kurir, status, created_at)
             VALUES (?, ?, ?, FROM_UNIXTIME(?))`,
        [
            data.id_pengiriman,
            data.id_kurir,
            data.status || 'MENUJU PENGIRIM',
            data.timestamp,
        ]
    );

    await db.execute(
        `UPDATE pengiriman SET status = ? WHERE id_pengiriman = ?`,
        ['DIPROSES', data.id_pengiriman]
    );

    console.log("✅ Assign Kurir Berhasil ditambahkan:", data.id_pengiriman);
}
