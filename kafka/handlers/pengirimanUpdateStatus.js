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
        `Status pengiriman ${data.nomor_resi} diperbarui ke ${data.status}`
    );
}
