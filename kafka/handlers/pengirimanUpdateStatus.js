import db from "../config/db.js";

export async function handlePengirimanUpdateStatus(data) {
    try {
        await db.execute(
            `
      UPDATE pengiriman 
      SET status = ?, keterangan_batal = ?
      WHERE id_pengiriman = ?
    `,
            [data.status, data.keterangan_batal || null, data.id_pengiriman]
        );

        console.log(
            `Status pengiriman ${data.id_pengiriman} diperbarui ke ${data.status}`
        );
    } catch (err) {
        console.error("Gagal update status pengiriman:", err);
    }
    return {
        id_pengiriman: data.id_pengiriman,
    };
}
