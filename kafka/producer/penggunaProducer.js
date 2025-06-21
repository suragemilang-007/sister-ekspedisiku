import express from "express";
import { Kafka } from "kafkajs";
import cors from "cors";
import { createKafka } from "../config/kafka.js";
const app = express();
app.use(express.json());
app.use(cors());
const kafka = createKafka("producer-kirim-paket");

const producer = kafka.producer();
await producer.connect();

app.post("/pengguna/update-info", async (req, res) => {
    await producer.send({
        topic: "pengguna-update-info",
        messages: [{ value: JSON.stringify(req.body) }],
    });
    res.json({ status: "ok" });
});

app.post("/pengguna/update-password", async (req, res) => {
    await producer.send({
        topic: "pengguna-update-password",
        messages: [{ value: JSON.stringify(req.body) }],
    });
    res.json({ status: "ok" });
});
app.post("/feedback", async (req, res) => {
    await producer.send({
        topic: "feedback-topic",
        messages: [{ value: JSON.stringify(req.body) }],
    });
    res.json({ status: "ok" });
});
app.post("/alamat-tujuan", async (req, res) => {
    await producer.send({
        topic: "alamat-tujuan-topic",
        messages: [{ value: JSON.stringify(req.body) }],
    });
    res.json({ status: "ok" });
});

app.post("/alamat-tujuan-edit", async (req, res) => {
    await producer.send({
        topic: "alamat-tujuan-edit",
        messages: [{ value: JSON.stringify(req.body) }],
    });
    res.json({ status: "ok" });
});

app.post("/alamat-tujuan-delete", async (req, res) => {
    await producer.send({
        topic: "alamat-tujuan-delete",
        messages: [{ value: JSON.stringify(req.body) }],
    });
    res.json({ status: "ok" });
});

app.listen(3001, () => console.log("Kafka producer running on port 3001"));
