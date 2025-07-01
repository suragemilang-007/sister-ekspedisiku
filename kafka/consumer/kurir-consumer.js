import dotenv from "dotenv";
import mysql from "mysql2/promise";
import { createKafka } from "../config/kafka.js";
import { TOPICS } from "../config/topics.js";
import { processKurirStatusUpdate } from "../handlers/kurirStatusUpdateHandler.js";

dotenv.config();

const kafka = createKafka("kurir-consumer");
const consumer = kafka.consumer({ groupId: "kurir-group" });

const dbConfig = {
    host: process.env.DB_HOST || "localhost",
    user: process.env.DB_USERNAME || "root",
    password: process.env.DB_PASSWORD || "",
    database: process.env.DB_DATABASE || "sister-ekspedisiku",
};

async function connectToDatabase() {
    try {
        const connection = await mysql.createConnection(dbConfig);
        console.log("✅ MySQL connected");
        return connection;
    } catch (error) {
        console.error("❌ Failed to connect to MySQL:", error);
        process.exit(1);
    }
}

async function run() {
    const connection = await connectToDatabase();

    try {
        await consumer.connect();
        console.log("✅ Kafka consumer connected");

        await consumer.subscribe({
            topic: TOPICS.KURIR_UPDATE_STATUS,
            fromBeginning: false,
        });
        console.log(`📡 Subscribed to topic: ${TOPICS.KURIR_UPDATE_STATUS}`);

        await consumer.run({
            eachMessage: async ({ topic, message }) => {
                const data = JSON.parse(message.value.toString());
                console.log(`📬 Received message from ${topic}:`, data);

                switch (topic) {
                    case TOPICS.KURIR_UPDATE_STATUS:
                        await processKurirStatusUpdate(data, connection);
                        break;
                    default:
                        console.warn(`⚠️ Unknown topic: ${topic}`);
                }
            },
        });
    } catch (error) {
        console.error("❌ Error in consumer:", error);
        await connection.end();
        process.exit(1);
    }
}

// Graceful shutdown
process.on("SIGINT", async () => {
    console.log("👋 Shutting down consumer...");
    await consumer.disconnect();
    process.exit(0);
});

run().catch(console.error);
