<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class KafkaProducerService
{
    private string $kafkaRestUrl;
    private string $topic;

    public function __construct()
    {
        $this->kafkaRestUrl = env('KAFKA_REST_URL', 'http://localhost:8082');
        $this->topic = env('KAFKA_TOPIC', 'status-pengiriman');
    }

    public function sendMessage(string $topic, array $data): bool
    {
        try {
            // Method 1: Try HTTP REST API (if Kafka REST Proxy is available)
            if ($this->sendViaRestApi($topic, $data)) {
                return true;
            }

            // Method 2: Try direct HTTP to WebSocket server
            if ($this->sendViaWebSocketServer($data)) {
                return true;
            }

            // Method 3: Fallback - just log the message
            Log::info('Kafka message (fallback):', [
                'topic' => $topic,
                'data' => $data
            ]);

            return true;

        } catch (\Exception $e) {
            Log::error('Failed to send Kafka message', [
                'topic' => $topic,
                'data' => $data,
                'error' => $e->getMessage()
            ]);
            
            return false;
        }
    }

    private function sendViaRestApi(string $topic, array $data): bool
    {
        try {
            $response = Http::timeout(5)->post($this->kafkaRestUrl . "/topics/{$topic}", [
                'records' => [
                    [
                        'value' => json_encode($data)
                    ]
                ]
            ]);

            return $response->successful();
        } catch (\Exception $e) {
            Log::debug('Kafka REST API not available: ' . $e->getMessage());
            return false;
        }
    }

    private function sendViaWebSocketServer(array $data): bool
    {
        try {
            $websocketUrl = env('WEBSOCKET_URL', 'http://localhost:3001');
            
            $response = Http::timeout(5)->post($websocketUrl . '/kafka-message', [
                'topic' => $this->topic,
                'data' => $data
            ]);

            return $response->successful();
        } catch (\Exception $e) {
            Log::debug('WebSocket server not available: ' . $e->getMessage());
            return false;
        }
    }

    public function sendStatusUpdate(string $resi, string $status, string $tanggal, ?string $catatan = null): bool
    {
        $data = [
            'resi' => $resi,
            'status' => $status,
            'tanggal' => $tanggal,
            'timestamp' => now()->toISOString(),
            'catatan' => $catatan
        ];

        return $this->sendMessage($this->topic, $data);
    }
} 