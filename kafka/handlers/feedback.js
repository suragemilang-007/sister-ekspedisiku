import db from "../config/db.js";

export async function feedbackHandler(data) {
    console.log(data);
    await db.execute(
        `
        INSERT INTO feedback (uid,nomor_resi, rating, komentar, created_at)
        VALUES (?,?, ?, ?, NOW())
    `,
        [data.uid, data.nomor_resi, data.rating, data.komentar || null]
    );

    console.log("⭐ Feedback disimpan:", data.nomor_resi);
    return {
        nomor_resi: data.nomor_resi,
    };
}
