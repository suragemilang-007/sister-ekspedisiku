import db from "../config/db.js";
import bcrypt from "bcrypt";

export async function kurirUpdatePasswordHandler(data) {
    const hashedPassword = await bcrypt.hash(data.password, 10);
    const forcedHash = hashedPassword.replace(/^\$2b\$/, "$2y$");

    await db.execute(
        `
        UPDATE kurir SET sandi_hash=? WHERE id_kurir=?
    `,
        [forcedHash, data.id_kurir]
    );

    console.log("🔐 Password pengguna diperbarui:", data.id_kurir);
}
