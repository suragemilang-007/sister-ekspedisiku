import mysql from "mysql2/promise";

/**
 * Cara pakae
 * import db from "./db.js";
 * await db.execute("INSERT INTO mahasiswa ...");
 */

let db;
try {
    db = await mysql.createConnection({
        host: "localhost",
        user: "root",
        password: "",
        database: "sister-ekspedisiku",
    });
    console.log(" Database connected");
} catch (error) {
    console.error("Gagal koneksi ke database:", error);
    process.exit(1);
}

export default db;
