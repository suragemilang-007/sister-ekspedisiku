const { Kafka } = require('kafkajs');
const mysql = require('mysql2/promise');
require('dotenv').config();

// Konfigurasi Kafka
const kafka = new Kafka({
  clientId: 'kurir-consumer',
  brokers: ['localhost:9092']
});

// Konfigurasi MySQL
const dbConfig = {
  host: process.env.DB_HOST || 'localhost',
  user: process.env.DB_USERNAME || 'root',
  password: process.env.DB_PASSWORD || '',
  database: process.env.DB_DATABASE || 'sister_ekspedisiku'
};

// Inisialisasi consumer
const consumer = kafka.consumer({ groupId: 'kurir-group' });

// Fungsi untuk menghubungkan ke database
async function connectToDatabase() {
  try {
    const connection = await mysql.createConnection(dbConfig);
    console.log('Connected to MySQL database');
    return connection;
  } catch (error) {
    console.error('Failed to connect to MySQL:', error);
    process.exit(1);
  }
}

// Fungsi untuk memproses pesan update status kurir
async function processKurirStatusUpdate(message, connection) {
  try {
    const data = JSON.parse(message.value.toString());
    console.log('Processing kurir status update:', data);

    const { id_penugasan, status, catatan, id_kurir, timestamp } = data;

    // Update status penugasan kurir
    await connection.execute(
      'UPDATE penugasan_kurir SET status = ?, catatan = ?, updated_at = ? WHERE id_penugasan = ? AND id_kurir = ?',
      [status, catatan, new Date(), id_penugasan, id_kurir]
    );

    // Jika status adalah SELESAI, update juga status pengiriman
    if (status === 'SELESAI') {
      // Dapatkan id_pengiriman dari penugasan
      const [penugasanRows] = await connection.execute(
        'SELECT id_pengiriman FROM penugasan_kurir WHERE id_penugasan = ?',
        [id_penugasan]
      );

      if (penugasanRows.length > 0) {
        const id_pengiriman = penugasanRows[0].id_pengiriman;
        
        // Update status pengiriman menjadi DITERIMA
        await connection.execute(
          'UPDATE pengiriman SET status = ?, tanggal_sampai = ? WHERE id_pengiriman = ?',
          ['DITERIMA', new Date(), id_pengiriman]
        );

        // Tambahkan entri pelacakan baru
        await connection.execute(
          'INSERT INTO pelacakan (id_pengiriman, id_pengguna, status, keterangan, lokasi, created_at) VALUES (?, ?, ?, ?, ?, ?)',
          [id_pengiriman, id_kurir, 'DITERIMA', 'Paket telah diterima oleh penerima', 'Alamat Tujuan', new Date()]
        );
      }
    }

    console.log(`Successfully updated status for penugasan ${id_penugasan} to ${status}`);
  } catch (error) {
    console.error('Error processing kurir status update:', error);
  }
}

// Fungsi utama untuk menjalankan consumer
async function run() {
  const connection = await connectToDatabase();

  try {
    await consumer.connect();
    console.log('Consumer connected to Kafka');

    await consumer.subscribe({ topic: 'kurir-update-status', fromBeginning: false });
    console.log('Subscribed to topic: kurir-update-status');

    await consumer.run({
      eachMessage: async ({ topic, partition, message }) => {
        console.log(`Received message from topic ${topic}:`);
        await processKurirStatusUpdate(message, connection);
      },
    });
  } catch (error) {
    console.error('Error in consumer:', error);
    await connection.end();
    process.exit(1);
  }
}

// Graceful shutdown
process.on('SIGTERM', async () => {
  try {
    await consumer.disconnect();
    console.log('Consumer disconnected');
    process.exit(0);
  } catch (error) {
    console.error('Error during consumer disconnect:', error);
    process.exit(1);
  }
});

// Jalankan consumer
run().catch(console.error);