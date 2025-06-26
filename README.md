<p align="center">
    <img src="https://img.shields.io/badge/Laravel-F55247?style=for-the-badge&logo=laravel&logoColor=white" alt="Laravel" />
    <img src="https://img.shields.io/badge/Node.js-339933?style=for-the-badge&logo=node.js&logoColor=white" alt="Node.js" />
    <img src="https://img.shields.io/badge/Express.js-000000?style=for-the-badge&logo=express&logoColor=white" alt="Express.js" />
    <img src="https://img.shields.io/badge/Apache%20Kafka-231F20?style=for-the-badge&logo=apache-kafka&logoColor=white" alt="Apache Kafka" />
    <img src="https://img.shields.io/badge/MySQL-4479A1?style=for-the-badge&logo=mysql&logoColor=white" alt="MySQL" />
</p>

# Ekspedisiku

Aplikasi **Ekspedisiku** adalah sistem ekspedisi berbasis web yang dibangun menggunakan framework Laravel.

## Langkah Instalasi

### 1. Setting Koneksi Database

Edit file `.env` dan sesuaikan konfigurasi database MySQL Anda:

```env
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=ekspedisiku
DB_USERNAME=root
DB_PASSWORD=your_password
```

### 2. Generate Application Key

```bash
php artisan key:generate
```

### 3. Migrasi dan Seed Database

```bash
php artisan migrate
php artisan db:seed
```

## Menjalankan Kafka Service

Untuk menjalankan service Kafka (Node.js), install dependencies berikut:

```bash
npm install mysql2 kafkajs cors bcrypt express
```

### Konfigurasi Kafka

- Edit file `db.js` untuk menyesuaikan koneksi database.
- Edit file `config.js` untuk mengatur:
    - Alamat broker Kafka (`brokers`)
    - `groupId`
    - `consumerId`

Contoh konfigurasi di `config.js`:

```js
module.exports = {
    kafka: {
        brokers: ['localhost:9092'],
        groupId: 'ekspedisiku-group',
        consumerId: 'ekspedisiku-consumer'
    }
};
```

## Menjalankan Aplikasi

- Jalankan Laravel:  
    ```bash
    php artisan serve
    ```
- Jalankan service Kafka:  
    jalankan konsumer Kafka:  
    ```bash
    node kafka/consumer/consumer.js
    ```
    jalankan produser Kafka:  
    ```bash
    node kafka/producer/producer.js
    ```

---

Silakan sesuaikan konfigurasi sesuai kebutuhan aplikasi Anda.
