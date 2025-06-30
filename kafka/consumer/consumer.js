import { createKafka } from "../config/kafka.js";
import { TOPICS } from "../config/topics.js";
import { Server } from "socket.io";
import http from "http";

import { updateInfoHandler } from "../handlers/updateInfo.js";
import { updatePasswordHandler } from "../handlers/updatePassword.js";
import { penggunaDeleteHandler } from "../handlers/penggunaDelete.js";
import { feedbackHandler } from "../handlers/feedback.js";
import { alamatTambahHandler } from "../handlers/alamatTambah.js";
import { alamatEditHandler } from "../handlers/alamatEdit.js";
import { alamatDeleteHandler } from "../handlers/alamatDelete.js";
import { alamatPenjemputanTambahHandler } from "../handlers/alamatPenjemputanTambah.js";
import { alamatPenjemputanEditHandler } from "../handlers/alamatPenjemputanEdit.js";
import { alamatPenjemputanDeleteHandler } from "../handlers/alamatPenjemputanDelete.js";
import { penggunaTambahHandler } from "../handlers/penggunaAdd.js";
import { addPengirimanHandler } from "../handlers/addPengiriman.js";
import { zonaCreateHandler } from "../handlers/zonaAdd.js";
import { updateZonaHandler } from "../handlers/updateZona.js";
import { zonaDeleteHandler } from "../handlers/zonaDelete.js";
import { layananCreateHandler } from "../handlers/layananAdd.js";
import { updateLayananHandler } from "../handlers/updateLayanan.js";
import { layananDeleteHandler } from "../handlers/layananDelete.js";
import { handlePengirimanUpdateStatus } from "../handlers/pengirimanUpdateStatus.js";
import { addAssignKurirHandler } from "../handlers/addAssignKurir.js";
import { kurirUpdateInfoHandler } from "../handlers/kurirUpdateInfo.js";
import { kurirUpdatePasswordHandler } from "../handlers/kurirUpdatePassword.js";
import { kurirCreateHandler } from "../handlers/kurirAdd.js";
import { kurirDeleteHandler } from "../handlers/kurirDelete.js";

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
    consumer.subscribe({
        topic: TOPICS.PENJEMPUTAN_TAMBAH,
        fromBeginning: false,
    }),
    consumer.subscribe({
        topic: TOPICS.PENJEMPUTAN_EDIT,
        fromBeginning: false,
    }),
    consumer.subscribe({
        topic: TOPICS.PENJEMPUTAN_DELETE,
        fromBeginning: false,
    }),
    consumer.subscribe({
        topic: TOPICS.DELETE_USER,
        fromBeginning: false,
    }),
    consumer.subscribe({ topic: TOPICS.ADD_USER, fromBeginning: false }),
    consumer.subscribe({ topic: TOPICS.ADD_PENGIRIMAN, fromBeginning: false }),
    consumer.subscribe({ topic: TOPICS.ADD_ZONA, fromBeginning: false }),
    consumer.subscribe({ topic: TOPICS.UPDATE_ZONA, fromBeginning: false }),
    consumer.subscribe({ topic: TOPICS.DELETE_ZONA, fromBeginning: false }),
    consumer.subscribe({ topic: TOPICS.ADD_LAYANAN, fromBeginning: false }),
    consumer.subscribe({ topic: TOPICS.UPDATE_LAYANAN, fromBeginning: false }),
    consumer.subscribe({ topic: TOPICS.DELETE_LAYANAN, fromBeginning: false }),
    consumer.subscribe({
        topic: TOPICS.PENGIRIMAN_UPDATE_STATUS,
        fromBeginning: false,
    }),
    consumer.subscribe({
        topic: TOPICS.ADD_ASSIGN_KURIR,
        fromBeginning: false,
    }),
    consumer.subscribe({ topic: TOPICS.KURIR_UPDATE_INFO, fromBeginning: false }),
    consumer.subscribe({ topic: TOPICS.KURIR_UPDATE_PASSWORD, fromBeginning: false }),
    consumer.subscribe({ topic: TOPICS.ADD_KURIR, fromBeginning: false }),
    consumer.subscribe({ topic: TOPICS.DELETE_KURIR, fromBeginning: false }),
]);

const httpServer = http.createServer();
const io = new Server(httpServer, {
    cors: {
        origin: "*",
    },
});

io.on("connection", (socket) => {
    console.log("🔗 Client terhubung ke WebSocket");
});

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
                    io.emit("update-sidebar", data);
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
                case TOPICS.PENJEMPUTAN_TAMBAH:
                    await alamatPenjemputanTambahHandler(data);
                    break;
                case TOPICS.PENJEMPUTAN_EDIT:
                    await alamatPenjemputanEditHandler(data);
                    break;
                case TOPICS.PENJEMPUTAN_DELETE:
                    await alamatPenjemputanDeleteHandler(data);
                    break;
                case TOPICS.ADD_USER:
                    await penggunaTambahHandler(data);
                    break;
                case TOPICS.DELETE_USER:
                    await penggunaDeleteHandler(data);
                    break;
                case TOPICS.ADD_PENGIRIMAN:
                    await addPengirimanHandler(data);
                    io.emit("update-data-pengiriman", data);
                    break;
                case TOPICS.ADD_ZONA:
                    await zonaCreateHandler(data);
                    break;
                case TOPICS.UPDATE_ZONA:
                    await updateZonaHandler(data);
                    break;
                case TOPICS.DELETE_ZONA:
                    await zonaDeleteHandler(data);
                    break;
                case TOPICS.ADD_LAYANAN:
                    await layananCreateHandler(data);
                    break;
                case TOPICS.UPDATE_LAYANAN:
                    await updateLayananHandler(data);
                    break;
                case TOPICS.DELETE_LAYANAN:
                    await layananDeleteHandler(data);
                    break;
                case TOPICS.PENGIRIMAN_UPDATE_STATUS:
                    await handlePengirimanUpdateStatus(data);
                    if (io) {
                        io.emit("update-data-pengiriman", data);
                        io.emit("update-data-pengiriman1", data);
                    }
                    break;
                case TOPICS.ADD_ASSIGN_KURIR:
                    await addAssignKurirHandler(data);
                    if (io) {
                        io.emit("update-data-pengiriman", data);
                    }
                    break;
                case TOPICS.KURIR_UPDATE_INFO:
                    await kurirUpdateInfoHandler(data);
                    break;
                case TOPICS.KURIR_UPDATE_PASSWORD:
                    await kurirUpdatePasswordHandler(data);
                    break;
                case TOPICS.ADD_KURIR:
                    await kurirCreateHandler(data);
                    break;
                case TOPICS.DELETE_KURIR:
                    await kurirDeleteHandler(data);
                    break;
                default:
                    console.warn("📭 Topik tidak dikenal:", topic);
            }
        } catch (err) {
            console.error(`❌ Gagal memproses pesan dari topik ${topic}:`, err);
        }
    },
});

const PORT = 4000;
httpServer.listen(PORT, () => {
    console.log(`🚀 WebSocket server berjalan di http://localhost:${PORT}`);
});
