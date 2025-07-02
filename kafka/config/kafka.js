import { Kafka } from "kafkajs";

/**
 * Cara pakae
 * import { createKafka } from '../config/kafka.js';
 * const kafka = createKafka("producer-kirim-paket");
 */

export const createKafka = (clientId) => {
    return new Kafka({
        clientId,
        brokers: ["localhost:9092"],
    });
};
