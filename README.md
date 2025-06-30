<p align="center">
    <img src="https://img.shields.io/badge/Laravel-F55247?style=for-the-badge&logo=laravel&logoColor=white" alt="Laravel" />
    <img src="https://img.shields.io/badge/Node.js-339933?style=for-the-badge&logo=node.js&logoColor=white" alt="Node.js" />
    <img src="https://img.shields.io/badge/Express.js-000000?style=for-the-badge&logo=express&logoColor=white" alt="Express.js" />
    <img src="https://img.shields.io/badge/Apache%20Kafka-231F20?style=for-the-badge&logo=apache-kafka&logoColor=white" alt="Apache Kafka" />
    <img src="https://img.shields.io/badge/MySQL-4479A1?style=for-the-badge&logo=mysql&logoColor=white" alt="MySQL" />
</p>

# SISTER Ekspedisiku - Real-time Courier Dashboard

Sistem informasi ekspedisi dengan fitur real-time status tracking menggunakan Apache Kafka dan WebSocket.

## Fitur Utama

- **Dashboard Kurir**: Interface untuk kurir mengupdate status pengiriman
- **Dashboard Admin**: Monitoring real-time status pengiriman
- **Real-time Updates**: Status pengiriman diperbarui secara real-time menggunakan Kafka + WebSocket
- **Tracking System**: Sistem pelacakan pengiriman yang terintegrasi

## Teknologi yang Digunakan

### Backend
- **Laravel 10**: Framework PHP untuk backend
- **MySQL**: Database utama
- **Apache Kafka**: Message broker untuk real-time messaging
- **php-rdkafka**: PHP extension untuk Kafka producer

### Real-time Communication
- **Node.js**: Runtime untuk WebSocket server
- **Socket.IO**: WebSocket library untuk real-time communication
- **KafkaJS**: Node.js client untuk Kafka consumer

### Frontend
- **Bootstrap 5**: UI framework
- **Socket.IO Client**: JavaScript client untuk WebSocket
- **Font Awesome**: Icon library

## Setup dan Instalasi

### 1. Prerequisites

Pastikan Anda telah menginstall:
- PHP 8.1+
- Composer
- Node.js 16+
- MySQL 8.0+
- Apache Kafka 2.8+

### 2. Setup Laravel

```bash
# Clone repository
git clone <repository-url>
cd sister-ekspedisiku

# Install PHP dependencies
composer install

# Copy environment file
cp .env.example .env

# Generate application key
php artisan key:generate

# Configure database in .env
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=sister_ekspedisiku
DB_USERNAME=your_username
DB_PASSWORD=your_password

# Configure Kafka in .env
KAFKA_BROKERS=localhost:9092

# Run migrations
php artisan migrate

# Seed database
php artisan db:seed

# Start Laravel server
php artisan serve
```

### 3. Setup Apache Kafka

```bash
# Download and extract Kafka
wget https://downloads.apache.org/kafka/2.8.0/kafka_2.13-2.8.0.tgz
tar -xzf kafka_2.13-2.8.0.tgz
cd kafka_2.13-2.8.0

# Start Zookeeper
bin/zookeeper-server-start.sh config/zookeeper.properties

# Start Kafka (in new terminal)
bin/kafka-server-start.sh config/server.properties

# Create topic for status updates
bin/kafka-topics.sh --create --topic status-pengiriman --bootstrap-server localhost:9092 --partitions 1 --replication-factor 1
```

### 4. Setup Node.js WebSocket Server

```bash
# Navigate to kafka directory
cd kafka

# Install dependencies
npm install

# Copy environment file
cp config.env .env

# Configure .env file
KAFKA_BROKERS=localhost:9092
KAFKA_TOPIC=status-pengiriman
PORT=3001
NODE_ENV=development
LARAVEL_URL=http://localhost:8000

# Start WebSocket server
npm start
```

### 5. Install php-rdkafka Extension

```bash
# For Ubuntu/Debian
sudo apt-get install librdkafka-dev
pecl install rdkafka

# For macOS
brew install librdkafka
pecl install rdkafka

# Add to php.ini
echo "extension=rdkafka.so" | sudo tee -a /etc/php/8.1/cli/php.ini
echo "extension=rdkafka.so" | sudo tee -a /etc/php/8.1/fpm/php.ini

# Restart PHP-FPM
sudo systemctl restart php8.1-fpm
```

## Cara Kerja Sistem

### 1. Flow Update Status Kurir

1. **Kurir mengupdate status** di halaman `/kurir/update/{id_penugasan}`
2. **Laravel Controller** menerima request dan:
   - Update status di database
   - Kirim data ke Kafka topic `status-pengiriman`
3. **Kafka Consumer** (Node.js) menerima message dan:
   - Broadcast ke semua client via WebSocket
4. **Dashboard Admin & Kurir** menerima update real-time

### 2. Format Data Kafka

```json
{
  "resi": "RESI0017",
  "status": "DALAM_PENGIRIMAN",
  "tanggal": "2025-01-28",
  "timestamp": "2025-01-28T10:30:00.000Z",
  "catatan": "Paket sedang dalam perjalanan"
}
```

### 3. WebSocket Events

- **Client Connection**: `socket.on('connect')`
- **Join Room**: `socket.emit('join-room', 'admin'|'kurir')`
- **Status Update**: `socket.on('update-status', data)`

## Struktur File Penting

```
sister-ekspedisiku/
├── app/
│   ├── Http/Controllers/
│   │   └── KurirController.php          # Controller untuk kurir
│   ├── Services/
│   │   └── KafkaProducerService.php     # Service Kafka producer
│   └── Models/
│       ├── Pengiriman.php
│       ├── PenugasanKurir.php
│       └── Pelacakan.php
├── resources/views/
│   ├── kurir/
│   │   ├── dashboard.blade.php          # Dashboard kurir dengan WebSocket
│   │   └── update.blade.php             # Form update status
│   └── admin/
│       └── dashboard/
│           └── index.blade.php          # Dashboard admin dengan WebSocket
├── kafka/
│   ├── server.js                        # WebSocket + Kafka consumer server
│   ├── package.json
│   └── config.env
└── routes/
    └── web.php                          # Route definitions
```

## API Endpoints

### Kurir Endpoints

- `GET /kurir/dashboard` - Dashboard kurir
- `GET /kurir/update/{id_penugasan}` - Form update status
- `POST /kurir/update-status` - Update status pengiriman
- `GET /kurir/dashboard-data` - Data dashboard (AJAX)

### Admin Endpoints

- `GET /admin/dashboard` - Dashboard admin
- `GET /admin/pesanan/baru` - Daftar pesanan baru
- `GET /admin/pesanan/list` - Semua pesanan

## Monitoring dan Debugging

### 1. Kafka Monitoring

```bash
# List topics
bin/kafka-topics.sh --list --bootstrap-server localhost:9092

# Monitor messages
bin/kafka-console-consumer.sh --topic status-pengiriman --bootstrap-server localhost:9092 --from-beginning

# Check consumer groups
bin/kafka-consumer-groups.sh --bootstrap-server localhost:9092 --list
```

### 2. WebSocket Server Monitoring

```bash
# Health check
curl http://localhost:3001/health

# Get stats
curl http://localhost:3001/stats
```

### 3. Laravel Logs

```bash
# View Laravel logs
tail -f storage/logs/laravel.log

# Check Kafka producer logs
grep "Kafka" storage/logs/laravel.log
```

## Troubleshooting

### 1. Kafka Connection Issues

- Pastikan Kafka server berjalan: `bin/kafka-server-start.sh config/server.properties`
- Check topic exists: `bin/kafka-topics.sh --list --bootstrap-server localhost:9092`
- Verify broker address di `.env`: `KAFKA_BROKERS=localhost:9092`

### 2. WebSocket Connection Issues

- Pastikan Node.js server berjalan: `npm start` di folder `kafka/`
- Check port availability: `netstat -an | grep 3001`
- Verify CORS settings di `server.js`

### 3. php-rdkafka Issues

- Install librdkafka: `sudo apt-get install librdkafka-dev`
- Reinstall extension: `pecl uninstall rdkafka && pecl install rdkafka`
- Check PHP extension: `php -m | grep rdkafka`

## Development

### 1. Development Mode

```bash
# Laravel development
php artisan serve

# WebSocket development
cd kafka
npm run dev

# Kafka (keep running)
bin/kafka-server-start.sh config/server.properties
```

### 2. Testing

```bash
# Run Laravel tests
php artisan test

# Test WebSocket connection
curl http://localhost:3001/health
```

## Production Deployment

### 1. Environment Variables

```bash
# Production .env
APP_ENV=production
APP_DEBUG=false
KAFKA_BROKERS=your-kafka-broker:9092
```

### 2. Process Management

```bash
# Use PM2 for Node.js
npm install -g pm2
pm2 start kafka/server.js --name "kafka-websocket"

# Use Supervisor for Laravel
# Configure supervisor for Laravel queue workers
```

## Kontribusi

1. Fork repository
2. Create feature branch: `git checkout -b feature/new-feature`
3. Commit changes: `git commit -am 'Add new feature'`
4. Push branch: `git push origin feature/new-feature`
5. Submit pull request

## License

MIT License - see LICENSE file for details
