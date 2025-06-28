import db from "../config/db.js";
import bcrypt from "bcrypt";

export async function updatePasswordHandler(data) {
    const hashedPassword = await bcrypt.hash(data.password, 10);
    const forcedHash = hashedPassword.replace(/^\$2b\$/, "$2y$");

    await db.execute(
        `
        UPDATE pengguna SET sandi_hash=? WHERE uid=?
    `,
        [forcedHash, data.uid]
    );

    console.log("🔐 Password pengguna diperbarui:", data.uid);
}
