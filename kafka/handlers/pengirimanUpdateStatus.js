import db from "../config/db.js";

export async function handlePengirimanUpdateStatus(data) {
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
    console.log("Pesanan Dibatalkan:", data.id_pengiriman, data.keterangan_batal || "Tidak ada keterangan");
}

