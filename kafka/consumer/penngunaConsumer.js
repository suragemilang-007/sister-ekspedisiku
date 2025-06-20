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

                default:
                    console.warn("📭 Topik tidak dikenal:", topic);
                    break;
            }
        } catch (err) {
            console.error(`❌ Gagal memproses pesan dari topik ${topic}:`, err);
        }
    },
});
