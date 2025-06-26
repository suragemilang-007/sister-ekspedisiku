import db from "../config/db.js";

export async function updateInfoHandler(data) {
    await db.execute(
        `
        UPDATE pengguna 
        SET nama=?, email=?, tgl_lahir=?, nohp=?, alamat=?, kelamin=? 
        WHERE uid=?
    `,
        [
            data.nama,
            data.email,
            data.tgl_lahir,
            data.nohp,
            data.alamat,
            data.kelamin,
            data.uid,
        ]
    );
    console.log("✅ Informasi pengguna diperbarui:", data.uid);
}
