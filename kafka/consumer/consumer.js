import { createKafka } from "../config/kafka.js";
import { TOPICS } from "../config/topics.js";

import { updateInfoHandler } from "../handlers/updateInfo.js";
import { updatePasswordHandler } from "../handlers/updatePassword.js";
import { penggunaDeleteHandler } from "../handlers/penggunaDelete.js";
import { feedbackHandler } from "../handlers/feedback.js";
import { alamatTambahHandler } from "../handlers/alamatTambah.js";
import { alamatEditHandler } from "../handlers/alamatEdit.js";
import { alamatDeleteHandler } from "../handlers/alamatDelete.js";
import { penggunaTambahHandler } from "../handlers/penggunaAdd.js";
import { addPengirimanHandler } from "../handlers/addPengiriman.js";
import { zonaCreateHandler } from "../handlers/zonaAdd.js";
import { updateZonaHandler } from "../handlers/updateZona.js";

const kafka = createKafka("producer-kirim-paket");
const consumer = kafka.consumer({ groupId: "pengguna-group" });

await consumer.connect();

await Promise.all([
    consumer.subscribe({ topic: TOPICS.UPDATE_INFO, fromBeginning: false }),
    consumer.subscribe({ topic: TOPICS.UPDATE_PASSWORD, fromBeginning: false }),
    consumer.subscribe({ topic: TOPICS.FEEDBACK, fromBeginning: false }),
    consumer.subscribe({ topic: TOPICS.ALAMAT_TAMBAH, fromBeginning: false }),
    consumer.subscribe({ topic: TOPICS.ALAMAT_EDIT, fromBeginning: false }),
    consumer.subscribe({ topic: TOPICS.ALAMAT_DELETE, fromBeginning: false }),
    consumer.subscribe({ topic: TOPICS.DELETE_USER, fromBeginning: false }),
    consumer.subscribe({ topic: TOPICS.ADD_USER, fromBeginning: false }),
    consumer.subscribe({ topic: TOPICS.ADD_PENGIRIMAN, fromBeginning: false }),
    consumer.subscribe({ topic: TOPICS.ADD_ZONA, fromBeginning: false }),
    consumer.subscribe({ topic: TOPICS.UPDATE_ZONA, fromBeginning: false }),
    consumer.subscribe({ topic: TOPICS.DELETE_ZONA, fromBeginning: false }),
]);

await consumer.run({
    eachMessage: async ({ topic, message }) => {
        const data = JSON.parse(message.value.toString());

        try {
            switch (topic) {
                case TOPICS.UPDATE_INFO:
                    await updateInfoHandler(data);
                    break;
                case TOPICS.UPDATE_PASSWORD:
                    await updatePasswordHandler(data);
                    break;
                case TOPICS.FEEDBACK:
                    await feedbackHandler(data);
                    break;
                case TOPICS.ALAMAT_TAMBAH:
                    await alamatTambahHandler(data);
                    break;
                case TOPICS.ALAMAT_EDIT:
                    await alamatEditHandler(data);
                    break;
                case TOPICS.ALAMAT_DELETE:
                    await alamatDeleteHandler(data);
                    break;
                case TOPICS.ADD_USER:
                    await penggunaTambahHandler(data);
                    break;
                case TOPICS.DELETE_USER:
                    await penggunaDeleteHandler(data);
                    break;
                case TOPICS.ADD_PENGIRIMAN:
                    await addPengirimanHandler(data);
                    break;
                case TOPICS.ADD_ZONA:
                    await zonaCreateHandler(data);
                    break;
                case TOPICS.UPDATE_ZONA:
                    await updateZonaHandler(data);
                    break;
                default:
                    console.warn("📭 Topik tidak dikenal:", topic);
            }
        } catch (err) {
            console.error(`❌ Gagal memproses pesan dari topik ${topic}:`, err);
        }
    },
});
