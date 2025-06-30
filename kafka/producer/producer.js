import express from "express";
import cors from "cors";
import { createKafka } from "../config/kafka.js";
import { TOPICS } from "../config/topics.js";
import { sendToKafka } from "../config/sendToKafka.js";

const app = express();
app.use(express.json());
app.use(cors());

const kafka = createKafka("producer-kirim-paket");
const producer = kafka.producer();

await producer.connect();

app.post("/pengguna/update-info", (req, res) =>
    sendToKafka(producer, TOPICS.UPDATE_INFO, req.body, res)
);

app.post("/pengguna/update-password", (req, res) =>
    sendToKafka(producer, TOPICS.UPDATE_PASSWORD, req.body, res)
);

app.post("/pengguna/delete", (req, res) =>
    sendToKafka(producer, TOPICS.DELETE_USER, req.body, res)
);

app.post("/pengguna/add", (req, res) =>
    sendToKafka(producer, TOPICS.ADD_USER, req.body, res)
);

app.post("/feedback", (req, res) =>
    sendToKafka(producer, TOPICS.FEEDBACK, req.body, res)
);

app.post("/alamat-tujuan", (req, res) =>
    sendToKafka(producer, TOPICS.ALAMAT_TAMBAH, req.body, res)
);

app.post("/alamat-tujuan-edit", (req, res) =>
    sendToKafka(producer, TOPICS.ALAMAT_EDIT, req.body, res)
);

app.post("/alamat-tujuan-delete", (req, res) =>
    sendToKafka(producer, TOPICS.ALAMAT_DELETE, req.body, res)
);

app.post("/alamat-penjemputan", (req, res) =>
    sendToKafka(producer, TOPICS.PENJEMPUTAN_TAMBAH, req.body, res)
);

app.post("/alamat-penjemputan-edit", (req, res) =>
    sendToKafka(producer, TOPICS.PENJEMPUTAN_EDIT, req.body, res)
);

app.post("/alamat-penjemputan-delete", (req, res) =>
    sendToKafka(producer, TOPICS.PENJEMPUTAN_DELETE, req.body, res)
);

app.post("/pengiriman_add", (req, res) =>
    sendToKafka(producer, TOPICS.ADD_PENGIRIMAN, req.body, res)
);

app.post("/zona/create", (req, res) =>
    sendToKafka(producer, TOPICS.ADD_ZONA, req.body, res)
);

app.post("/zona/update", (req, res) =>
    sendToKafka(producer, TOPICS.UPDATE_ZONA, req.body, res)
);

app.post("/zona/delete", (req, res) =>
    sendToKafka(producer, TOPICS.DELETE_ZONA, req.body, res)
);

app.post("/layanan/add", (req, res) =>
    sendToKafka(producer, TOPICS.ADD_LAYANAN, req.body, res)
);

app.post("/layanan/update", (req, res) =>
    sendToKafka(producer, TOPICS.UPDATE_LAYANAN, req.body, res)
);

app.post("/layanan/delete", (req, res) =>
    sendToKafka(producer, TOPICS.DELETE_LAYANAN, req.body, res)
);
app.post("/pengiriman/update-status-pengiriman", (req, res) => {
    sendToKafka(producer, TOPICS.PENGIRIMAN_UPDATE_STATUS, req.body, res);
});
app.post("/assign/add", (req, res) => {
    sendToKafka(producer, TOPICS.ADD_ASSIGN_KURIR, req.body, res)
});
app.post("/kurir/update-profile", (req, res) => {
    sendToKafka(producer, TOPICS.KURIR_UPDATE_INFO, req.body, res);
});
app.post("/kurir/update-password", (req, res) => {
    sendToKafka(producer, TOPICS.KURIR_UPDATE_PASSWORD, req.body, res);
});
app.post("/kurir/add", (req, res) => {
    sendToKafka(producer, TOPICS.ADD_KURIR, req.body, res);
});
app.post("/kurir/delete", (req, res) => {
    sendToKafka(producer, TOPICS.DELETE_KURIR, req.body, res);
});

process.on("SIGINT", async () => {
    console.log("⛔ Menutup koneksi Kafka...");
    await producer.disconnect();
    process.exit(0);
});

app.listen(3001, () => {
    console.log("✅ Kafka producer running on port 3001");
});
