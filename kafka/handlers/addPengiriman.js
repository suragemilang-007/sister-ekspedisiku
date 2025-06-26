import db from "../config/db.js";

export async function addPengirimanHandler(data) {
    console.log(
        data?.id_pengirim,
        data?.id_alamat_tujuan,
        data?.id_alamat_penjemputan,
        data?.total_biaya,
        data?.id_zona,
        data?.status,
        data?.nomor_resi,
        data?.catatan_opsional || null,
        data?.foto_barang || null,
        data?.created_at
    );
    await db.execute(
        `
        INSERT INTO pengiriman 
        (id_pengirim, id_alamat_tujuan, id_alamat_penjemputan, total_biaya, id_zona, status, nomor_resi, catatan_opsional, foto_barang, created_at)
        VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?)
      `,
        [
            data.id_pengirim,
            data.id_alamat_tujuan,
            data.id_alamat_penjemputan,
            data.total_biaya,
            data.id_zona,
            data.status,
            data.nomor_resi,
            data.catatan_opsional || null,
            data.foto_barang || null,
            data.created_at,
        ]
    );
    console.log("✅ Pengiriman ditambahkan:", data.nomor_resi);
}
