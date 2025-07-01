import express from "express";
import { Kafka } from "kafkajs";
import bodyParser from "body-parser";
import cors from "cors";
import mysql from "mysql2/promise";
import dotenv from "dotenv";
import { createKafka } from "../config/kafka.js";// Assuming createKafka is defined in kafka.js

dotenv.config();

const app = express();
const port = process.env.PORT || 3003;

// Middleware
app.use(cors());
app.use(bodyParser.json());
app.use(bodyParser.urlencoded({ extended: true }));

// Konfigurasi Kafka
const kafka = createKafka("kurir-producer");
// const kafka = new Kafka({
//     clientId: "kurir-producer",
//     brokers: ["localhost:9092"],
// });

const producer = kafka.producer();

// Connect producer saat aplikasi dimulai
async function connectProducer() {
    try {
        await producer.connect();
        console.log("Producer connected to Kafka");
    } catch (error) {
        console.error("Failed to connect producer:", error);
        process.exit(1);
    }
}

connectProducer();

// Route untuk update status kurir
app.post("/kurir/update-status", async (req, res) => {
    try {
        const { id_penugasan, status, catatan, id_kurir } = req.body;

        if (!id_penugasan || !status || !id_kurir) {
            return res
                .status(400)
                .json({ success: false, message: "Data tidak lengkap" });
        }

        // Kirim pesan ke topic Kafka
        await producer.send({
            topic: "kurir-update-status",
            messages: [
                {
                    value: JSON.stringify({
                        id_penugasan,
                        status,
                        catatan,
                        id_kurir,
                        timestamp: new Date().toISOString(),
                    }),
                },
            ],
        });

        console.log(
            `Status update sent to Kafka: ${status} for penugasan ${id_penugasan}`
        );
        res.status(200).json({
            success: true,
            message: "Status berhasil dikirim ke Kafka",
        });
    } catch (error) {
        console.error("Error sending message to Kafka:", error);
        res.status(500).json({
            success: false,
            message: "Gagal mengirim pesan ke Kafka",
        });
    }
});

// Graceful shutdown
process.on("SIGTERM", async () => {
    try {
        await producer.disconnect();
        console.log("Producer disconnected");
        process.exit(0);
    } catch (error) {
        console.error("Error during producer disconnect:", error);
        process.exit(1);
    }
});

// Start server
app.listen(port, () => {
    console.log(`Kurir Producer running on port ${port}`);
});
