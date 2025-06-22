import db from "../config/db.js";

export async function feedbackHandler(data) {
    await db.execute(
        `
        INSERT INTO feedback (id_pengiriman, rating, komentar, created_at)
        VALUES (?, ?, ?, NOW())
    `,
        [data.id_pengiriman, data.rating, data.komentar || null]
    );

    console.log("⭐ Feedback disimpan:", data.id_pengiriman);
}
