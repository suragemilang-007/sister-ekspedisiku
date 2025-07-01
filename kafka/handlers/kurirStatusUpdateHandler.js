export async function processKurirStatusUpdate(data, connection) {
    try {
        const { id_penugasan, status, catatan, id_kurir } = data;
        console.log(`[KURIR] Processing update for id_penugasan=${id_penugasan}`);

        await connection.execute(
            `UPDATE penugasan_kurir 
             SET status = ?, catatan = ?, updated_at = ? 
             WHERE id_penugasan = ? AND id_kurir = ?`,
            [status, catatan, new Date(), id_penugasan, id_kurir]
        );

        if (status === "SELESAI") {
            const [rows] = await connection.execute(
                "SELECT id_pengiriman FROM penugasan_kurir WHERE id_penugasan = ?",
                [id_penugasan]
            );

            if (rows.length > 0) {
                const id_pengiriman = rows[0].id_pengiriman;

                await connection.execute(
                    "UPDATE pengiriman SET status = ?, tanggal_sampai = ? WHERE id_pengiriman = ?",
                    ["DITERIMA", new Date(), id_pengiriman]
                );

                await connection.execute(
                    `INSERT INTO pelacakan 
                     (id_pengiriman, id_pengguna, status, keterangan, lokasi, created_at)
                     VALUES (?, ?, ?, ?, ?, ?)`,
                    [
                        id_pengiriman,
                        id_kurir,
                        "DITERIMA",
                        "Paket telah diterima oleh penerima",
                        "Alamat Tujuan",
                        new Date(),
                    ]
                );
                console.log(`📦 Pengiriman ${id_pengiriman} ditandai DITERIMA`);
            } else {
                console.warn(`[KURIR] id_pengiriman tidak ditemukan untuk id_penugasan=${id_penugasan}`);
            }
        }

        console.log(`✅ Penugasan ${id_penugasan} berhasil diupdate`);
    } catch (error) {
        console.error("❌ Gagal memproses status kurir:", error);
    }
} 