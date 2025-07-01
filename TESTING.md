# Testing Guide - SISTER Ekspedisiku Real-time System

Dokumentasi untuk testing sistem real-time menggunakan Apache Kafka dan WebSocket.

## Prerequisites

Pastikan semua service berjalan:
- Laravel server (port 8000)
- WebSocket server (port 3001)
- Apache Kafka (port 9092)

## 1. Testing Kafka Producer (Laravel)

### Test Kafka Connection

```bash
# Test Kafka producer service
php artisan tinker
```

```php
// Di dalam tinker
use App\Services\KafkaProducerService;

$kafka = new KafkaProducerService();
$result = $kafka->sendStatusUpdate('RESI0017', 'DALAM_PENGIRIMAN', '2025-01-28', 'Testing dari Laravel');
var_dump($result); // Should return true
```

### Test via API

```bash
# Test update status via API
curl -X POST http://localhost:8000/kurir/update-status \
  -H "Content-Type: application/json" \
  -H "X-CSRF-TOKEN: your-csrf-token" \
  -d '{
    "id_penugasan": 1,
    "status": "DALAM_PENGIRIMAN",
    "catatan": "Testing via API"
  }'
```

## 2. Testing Kafka Consumer (Node.js)

### Monitor Kafka Messages

```bash
# Monitor messages in Kafka topic
bin/kafka-console-consumer.sh --topic status-pengiriman --bootstrap-server localhost:9092 --from-beginning
```

### Test WebSocket Server Health

```bash
# Health check
curl http://localhost:3001/health

# Expected response:
{
  "status": "OK",
  "timestamp": "2025-01-28T10:30:00.000Z",
  "connections": 0
}

# Get stats
curl http://localhost:3001/stats

# Expected response:
{
  "connectedClients": 0,
  "rooms": []
}
```

## 3. Testing WebSocket Client

### Test WebSocket Connection

Buka browser console di dashboard kurir atau admin:

```javascript
// Test WebSocket connection
const socket = io('http://localhost:3001');

socket.on('connect', () => {
    console.log('Connected to WebSocket server');
    console.log('Socket ID:', socket.id);
});

socket.on('disconnect', () => {
    console.log('Disconnected from WebSocket server');
});

// Join room
socket.emit('join-room', 'admin'); // or 'kurir'

// Listen for status updates
socket.on('update-status', (data) => {
    console.log('Received status update:', data);
});
```

### Test Manual Message Broadcasting

```bash
# Send test message via curl
curl -X POST http://localhost:3001/test-message \
  -H "Content-Type: application/json" \
  -d '{
    "resi": "RESI0017",
    "status": "SELESAI",
    "tanggal": "2025-01-28",
    "timestamp": "2025-01-28T10:30:00.000Z",
    "catatan": "Testing manual broadcast"
  }'
```

## 4. End-to-End Testing

### Scenario 1: Kurir Update Status

1. **Login sebagai kurir**
   ```bash
   # Access dashboard kurir
   http://localhost:8000/kurir/dashboard
   ```

2. **Update status pengiriman**
   - Klik tombol "Update Status" pada salah satu tugas
   - Pilih status baru (misal: "DALAM_PENGIRIMAN")
   - Tambahkan catatan
   - Klik "Update Status"

3. **Verify real-time updates**
   - Buka dashboard admin di tab lain
   - Status harus berubah secara real-time
   - Toast notification harus muncul

### Scenario 2: Multiple Clients

1. **Open multiple browser tabs/windows**
   - Tab 1: Dashboard kurir
   - Tab 2: Dashboard admin
   - Tab 3: Dashboard admin (user lain)

2. **Update status from kurir**
   - Update status di tab kurir

3. **Verify all clients receive updates**
   - Semua tab harus menerima update real-time
   - Connection status harus menunjukkan "Terhubung"

### Scenario 3: Network Disconnection

1. **Disconnect network temporarily**
   - Matikan WiFi atau unplug network cable

2. **Verify connection status**
   - Status harus berubah menjadi "Terputus" atau "Error Koneksi"

3. **Reconnect network**
   - Status harus kembali menjadi "Terhubung"
   - Real-time updates harus berfungsi kembali

## 5. Performance Testing

### Load Testing WebSocket

```bash
# Install artillery for load testing
npm install -g artillery

# Create test scenario
cat > websocket-test.yml << EOF
config:
  target: 'http://localhost:3001'
  phases:
    - duration: 60
      arrivalRate: 10
scenarios:
  - name: "WebSocket connections"
    engine: "socketio"
    flow:
      - connect:
          headers:
            upgrade: "websocket"
      - emit:
          channel: "join-room"
          data: "admin"
      - think: 30
      - disconnect: {}
EOF

# Run load test
artillery run websocket-test.yml
```

### Kafka Message Throughput

```bash
# Test Kafka producer performance
php artisan tinker
```

```php
// Performance test
use App\Services\KafkaProducerService;

$kafka = new KafkaProducerService();
$start = microtime(true);

for ($i = 1; $i <= 100; $i++) {
    $kafka->sendStatusUpdate(
        "RESI" . str_pad($i, 4, '0', STR_PAD_LEFT),
        'DALAM_PENGIRIMAN',
        date('Y-m-d'),
        "Performance test message $i"
    );
}

$end = microtime(true);
$duration = $end - $start;
echo "Sent 100 messages in {$duration} seconds\n";
echo "Rate: " . (100 / $duration) . " messages/second\n";
```

## 6. Error Handling Testing

### Test Invalid Data

```bash
# Test with invalid status
curl -X POST http://localhost:8000/kurir/update-status \
  -H "Content-Type: application/json" \
  -H "X-CSRF-TOKEN: your-csrf-token" \
  -d '{
    "id_penugasan": 999,
    "status": "INVALID_STATUS",
    "catatan": "Testing invalid data"
  }'
```

### Test Kafka Connection Failure

1. **Stop Kafka server**
   ```bash
   # Stop Kafka
   bin/kafka-server-stop.sh
   ```

2. **Try to update status**
   - Update status di dashboard kurir
   - Should show error message
   - Check Laravel logs for Kafka errors

3. **Restart Kafka**
   ```bash
   # Restart Kafka
   bin/kafka-server-start.sh config/server.properties
   ```

4. **Verify recovery**
   - Update status should work again

## 7. Browser Compatibility Testing

### Test di berbagai browser:
- Chrome (latest)
- Firefox (latest)
- Safari (latest)
- Edge (latest)

### Test di mobile browser:
- Chrome Mobile
- Safari Mobile
- Firefox Mobile

## 8. Monitoring dan Logging

### Laravel Logs

```bash
# Monitor Laravel logs
tail -f storage/logs/laravel.log

# Filter Kafka related logs
grep "Kafka" storage/logs/laravel.log
```

### WebSocket Server Logs

```bash
# Monitor WebSocket server logs
# Check console output where npm start is running
```

### Kafka Logs

```bash
# Monitor Kafka logs
tail -f logs/server.log
```

## 9. Troubleshooting

### Common Issues

1. **WebSocket connection failed**
   - Check if WebSocket server is running on port 3001
   - Check CORS settings in server.js
   - Check firewall settings

2. **Kafka connection failed**
   - Check if Kafka is running on port 9092
   - Check topic exists: `bin/kafka-topics.sh --list --bootstrap-server localhost:9092`
   - Check php-rdkafka extension is installed

3. **Real-time updates not working**
   - Check browser console for JavaScript errors
   - Check WebSocket connection status
   - Verify Kafka messages are being sent and received

### Debug Commands

```bash
# Check all running services
netstat -an | grep -E "(8000|3001|9092)"

# Check PHP extensions
php -m | grep rdkafka

# Check Node.js version
node --version

# Check npm packages
cd kafka && npm list
```

## 10. Test Checklist

- [ ] Kafka producer can send messages
- [ ] Kafka consumer receives messages
- [ ] WebSocket server broadcasts messages
- [ ] Dashboard kurir receives real-time updates
- [ ] Dashboard admin receives real-time updates
- [ ] Connection status shows correctly
- [ ] Toast notifications work
- [ ] Error handling works
- [ ] Network disconnection/reconnection works
- [ ] Multiple clients can connect simultaneously
- [ ] Performance is acceptable under load
- [ ] Works across different browsers
- [ ] Mobile compatibility
- [ ] Logging and monitoring work correctly 