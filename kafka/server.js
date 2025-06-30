const express = require('express');
const http = require('http');
const socketIo = require('socket.io');
const cors = require('cors');
const { Kafka } = require('kafkajs');
require('dotenv').config();

const app = express();
const server = http.createServer(app);
const io = socketIo(server, {
    cors: {
        origin: "*",
        methods: ["GET", "POST"]
    }
});

// Middleware
app.use(cors());
app.use(express.json());

// Kafka Configuration
const kafka = new Kafka({
    clientId: 'websocket-server',
    brokers: [process.env.KAFKA_BROKERS || 'localhost:9092']
});

const consumer = kafka.consumer({ groupId: 'websocket-consumer-group' });
const producer = kafka.producer();

// WebSocket connection handling
io.on('connection', (socket) => {
    console.log('Client connected:', socket.id);

    // Join room based on user role
    socket.on('join-room', (room) => {
        socket.join(room);
        console.log(`Client ${socket.id} joined room: ${room}`);
    });

    socket.on('disconnect', () => {
        console.log('Client disconnected:', socket.id);
    });
});

// HTTP endpoint to receive messages from Laravel
app.post('/kafka-message', async (req, res) => {
    try {
        const { topic, data } = req.body;
        
        console.log('Received message from Laravel:', { topic, data });

        // Send to Kafka if available
        try {
            await producer.connect();
            await producer.send({
                topic: topic || 'status-pengiriman',
                messages: [
                    { value: JSON.stringify(data) }
                ]
            });
            console.log('Message sent to Kafka successfully');
        } catch (kafkaError) {
            console.log('Kafka not available, broadcasting directly:', kafkaError.message);
        }

        // Broadcast to all connected clients
        io.emit('update-status', data);

        // Also emit to specific rooms if needed
        io.to('admin').emit('update-status', data);
        io.to('kurir').emit('update-status', data);
        io.to('tracking').emit('update-status', data);

        res.json({ success: true, message: 'Message broadcasted successfully' });

    } catch (error) {
        console.error('Error processing message:', error);
        res.status(500).json({ success: false, error: error.message });
    }
});

// Test endpoint for manual message sending
app.post('/test-message', async (req, res) => {
    try {
        const data = req.body;
        
        console.log('Test message received:', data);

        // Send to Kafka if available
        try {
            await producer.connect();
            await producer.send({
                topic: 'status-pengiriman',
                messages: [
                    { value: JSON.stringify(data) }
                ]
            });
            console.log('Test message sent to Kafka successfully');
        } catch (kafkaError) {
            console.log('Kafka not available for test message:', kafkaError.message);
        }

        // Broadcast to all connected clients
        io.emit('update-status', data);

        res.json({ success: true, message: 'Test message broadcasted successfully' });

    } catch (error) {
        console.error('Error processing test message:', error);
        res.status(500).json({ success: false, error: error.message });
    }
});

// Kafka Consumer
async function startKafkaConsumer() {
    try {
        await consumer.connect();
        await consumer.subscribe({ topic: 'status-pengiriman', fromBeginning: false });
        
        console.log('Kafka consumer connected and subscribed to status-pengiriman topic');

        await consumer.run({
            eachMessage: async ({ topic, partition, message }) => {
                try {
                    const data = JSON.parse(message.value.toString());
                    console.log('Received message from Kafka:', data);

                    // Broadcast to all connected clients
                    io.emit('update-status', data);

                    // Also emit to specific rooms if needed
                    io.to('admin').emit('update-status', data);
                    io.to('kurir').emit('update-status', data);
                    io.to('tracking').emit('update-status', data);

                } catch (error) {
                    console.error('Error processing Kafka message:', error);
                }
            },
        });
    } catch (error) {
        console.error('Error starting Kafka consumer:', error);
        console.log('Continuing without Kafka consumer...');
    }
}

// Health check endpoint
app.get('/health', (req, res) => {
    res.json({ 
        status: 'OK', 
        timestamp: new Date().toISOString(),
        connections: io.engine.clientsCount
    });
});

// Get connected clients count
app.get('/stats', (req, res) => {
    res.json({
        connectedClients: io.engine.clientsCount,
        rooms: Object.keys(io.sockets.adapter.rooms)
    });
});

// Start server
const PORT = process.env.PORT || 3001;

server.listen(PORT, () => {
    console.log(`WebSocket server running on port ${PORT}`);
    console.log(`Health check: http://localhost:${PORT}/health`);
    console.log(`Stats: http://localhost:${PORT}/stats`);
});

// Start Kafka consumer
startKafkaConsumer();

// Graceful shutdown
process.on('SIGINT', async () => {
    console.log('Shutting down gracefully...');
    try {
        await consumer.disconnect();
        await producer.disconnect();
    } catch (error) {
        console.log('Error disconnecting from Kafka:', error.message);
    }
    server.close(() => {
        console.log('Server closed');
        process.exit(0);
    });
});

process.on('SIGTERM', async () => {
    console.log('SIGTERM received, shutting down gracefully...');
    try {
        await consumer.disconnect();
        await producer.disconnect();
    } catch (error) {
        console.log('Error disconnecting from Kafka:', error.message);
    }
    server.close(() => {
        console.log('Server closed');
        process.exit(0);
    });
}); 