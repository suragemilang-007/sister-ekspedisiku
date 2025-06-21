import { Kafka } from "kafkajs";
import mysql from "mysql2/promise";
import bcrypt from "bcrypt";
import { createKafka } from "../config/kafka.js";
import db from "../config/db.js";

const kafka = createKafka("producer-kirim-paket");

const consumer = kafka.consumer({ groupId: "pengguna-group" });
await consumer.connect();

await consumer.subscribe({
    topic: "pengguna-update-info",
    fromBeginning: false,
});
await consumer.subscribe({
    topic: "pengguna-update-password",
    fromBeginning: false,
});
await consumer.subscribe({ topic: "feedback-topic", fromBeginning: false });
await consumer.run({
    eachMessage: async ({ topic, message }) => {
        const data = JSON.parse(message.value.toString());

        try {
            switch (topic) {
                case "pengguna-update-info":
                    await db.execute(
                        `
                        UPDATE pengguna SET nama=?, email=?, tgl_lahir=?, nohp=?, alamat=?, kelamin=? 
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
                    console.log(
                        "✅ Informasi pengguna diperbarui:",
                        data.id_pengguna
                    );
                    break;

                case "pengguna-update-password":
                    const hash = await bcrypt.hash(data.password, 10);
                    const forcedHash = hash.replace(/^\$2b\$/, "$2y$");

                    await db.execute(
                        `
                        UPDATE pengguna SET sandi_hash=? WHERE id_pengguna=?
                    `,
                        [forcedHash, data.id_pengguna]
                    );
                    console.log(
                        "🔐 Password pengguna diperbarui:",
                        data.id_pengguna
                    );
                    break;

                case "feedback-topic":
                    await db.execute(
                        `
                        INSERT INTO feedback (id_pengiriman, rating, komentar, created_at)
                        VALUES (?, ?, ?, NOW())
                    `,
                        [data.id_pengiriman, data.rating, data.komentar || null]
                    );
                    console.log(
                        "⭐ Feedback disimpan untuk pengiriman:",
                        data.id_pengiriman
                    );
                    break;
                case "alamat-tujuan-topic":
                    const now = new Date();
                    await db.execute(
                        `
        INSERT INTO alamat_tujuan (
            id_pengirim, nama_penerima, no_hp, alamat_lengkap, kecamatan, kode_pos, keterangan_alamat, created_at
        ) VALUES (?, ?, ?, ?, ?, ?, ?, ?)`,
                        [
                            data.id_pengirim,
                            data.nama_penerima,
                            data.no_hp,
                            data.alamat_lengkap,
                            data.kecamatan,
                            data.kode_pos,
                            data.keterangan_alamat || null,
                            now,
                        ]
                    );
                    console.log(
                        "📦 Alamat tujuan ditambahkan:",
                        data.nama_penerima
                    );
                    break;

                case "alamat-tujuan-edit":
                    await db.execute(
                        `
        UPDATE alamat_tujuan SET nama_penerima=?, no_hp=?, alamat_lengkap=?, kecamatan=?, kode_pos=?, keterangan_alamat=?
        WHERE id_alamat_tujuan=?`,
                        [
                            data.nama_penerima,
                            data.no_hp,
                            data.alamat_lengkap,
                            data.kecamatan,
                            data.kode_pos,
                            data.keterangan_alamat,
                            data.id_alamat_tujuan,
                        ]
                    );
                    console.log("✏️ Alamat diperbarui:", data.id_alamat_tujuan);
                    break;

                case "alamat-tujuan-delete":
                    await db.execute(
                        `DELETE FROM alamat_tujuan WHERE id_alamat_tujuan=?`,
                        [data.id_alamat_tujuan]
                    );
                    console.log("🗑️ Alamat dihapus:", data.id_alamat_tujuan);
                    break;

                default:
                    console.warn("📭 Topik tidak dikenal:", topic);
                    break;
            }
        } catch (err) {
            console.error(`❌ Gagal memproses pesan dari topik ${topic}:`, err);
        }
    },
});
