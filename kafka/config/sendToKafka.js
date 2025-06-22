export async function sendToKafka(producer, topic, data, res) {
    try {
        await producer.send({
            topic,
            messages: [{ value: JSON.stringify(data) }],
        });
        res.json({ status: "ok" });
    } catch (err) {
        console.error(`❌ Gagal kirim ke Kafka topic ${topic}:`, err);
        res.status(500).json({ status: "error", message: err.message });
    }
}
