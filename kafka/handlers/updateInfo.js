import db from "../config/db.js";

export async function updateInfoHandler(data) {
    await db.execute(
        `
        UPDATE pengguna 
        SET nama=?, email=?, tgl_lahir=?, nohp=?, alamat=?, kelamin=? 
        WHERE id_pengguna=?
    `,
        [
            data.nama,
            data.email,
            data.tgl_lahir,
            data.nohp,
            data.alamat,
            data.kelamin,
            data.id_pengguna,
        ]
    );
    console.log("✅ Informasi pengguna diperbarui:", data.id_pengguna);
}
