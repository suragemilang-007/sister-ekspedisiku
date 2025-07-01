import db from "../config/db.js";

export async function handlePengirimanUpdateStatusSelesai(data) {
    await db.execute(
        `
      UPDATE pengiriman 
      SET status = ?, keterangan_batal = ?
      WHERE nomor_resi = ?
    `,
        [data.status, data.keterangan_batal || null, data.nomor_resi]
    );

    console.log(
        `Status pengiriman ${data.nomor_resi} diperbarui ke ${data.status}`
    );
}
