<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class WhatsAppService
{
    protected $url;
    protected $apiKey;
    protected $sessionName;

    /**
     * Create a new service instance.
     */
    public function __construct()
    {
        $this->url = env('OPENWA_URL', 'http://localhost:2785');
        $this->apiKey = env('OPENWA_API_KEY');
        $this->sessionName = env('OPENWA_SESSION_NAME', 'default');
    }

    /**
     * Send a WhatsApp message using OpenWA REST API.
     * Falls back to dummy logging if no API Key is configured.
     *
     * @param string $phone
     * @param string $message
     * @return array
     */
    public function sendMessage(string $phone, string $message): array
    {
        if (empty($this->apiKey)) {
            // Dummy mode
            Log::info("Dummy WA to {$phone}: {$message}");
            return [
                'success' => true, 
                'response' => 'Dummy mode: Message logged to Laravel log',
                'message_id' => 'dummy_' . uniqid()
            ];
        }

        $chatId = $this->formatChatId($phone);

        try {
            $response = Http::withHeaders([
                'X-API-Key' => $this->apiKey,
                'Content-Type' => 'application/json',
            ])->post("{$this->url}/api/sessions/{$this->sessionName}/messages/send-text", [
                'chatId' => $chatId,
                'text' => $message,
            ]);

            if ($response->successful()) {
                $body = $response->body();
                $data = $response->json();
                
                // Extract messageId if present
                $messageId = null;
                if (is_array($data)) {
                    $messageId = $data['messageId'] ?? $data['response'] ?? null;
                } elseif (is_string($body) && str_starts_with($body, 'true_')) {
                    $messageId = $body;
                }

                return [
                    'success' => true,
                    'response' => $body,
                    'message_id' => $messageId
                ];
            } else {
                return [
                    'success' => false,
                    'response' => 'HTTP ' . $response->status() . ': ' . $response->body()
                ];
            }
        } catch (\Exception $e) {
            Log::error("Failed to send WA to {$phone} (chatId: {$chatId}) via OpenWA: " . $e->getMessage());
            return [
                'success' => false,
                'response' => 'Exception: ' . $e->getMessage()
            ];
        }
    }

    /**
     * Format raw phone number into OpenWA chatId (628xxxxxxxx@c.us).
     *
     * @param string $phone
     * @return string
     */
    public function formatChatId(string $phone): string
    {
        // Extract numeric part
        $number = preg_replace('/[^0-9]/', '', $phone);
        
        // Convert leading 0 to 62
        if (str_starts_with($number, '0')) {
            $number = '62' . substr($number, 1);
        }
        
        // If it starts with 8, prepend 62 (e.g. 812... -> 62812...)
        if (str_starts_with($number, '8')) {
            $number = '62' . $number;
        }

        return $number . '@c.us';
    }

    /**
     * Get the connection status of the OpenWA Gateway.
     *
     * @return array
     */
    public function getConnectionStatus(): array
    {
        if (empty($this->apiKey)) {
            return [
                'connected' => false,
                'status' => 'dummy',
                'message' => 'WhatsApp Gateway is in Dummy Mode (API key not configured).'
            ];
        }

        try {
            // Check health endpoint
            $healthResponse = Http::timeout(3)->get("{$this->url}/api/health");
            if (!$healthResponse->successful()) {
                return [
                    'connected' => false,
                    'status' => 'offline',
                    'message' => 'OpenWA server is offline or unreachable.'
                ];
            }

            // Check session details
            $sessionResponse = Http::timeout(3)->withHeaders([
                'X-API-Key' => $this->apiKey,
            ])->get("{$this->url}/api/sessions/{$this->sessionName}");

            if ($sessionResponse->successful()) {
                $data = $sessionResponse->json();
                $status = $data['status'] ?? 'unknown';

                if ($status === 'ready') {
                    return [
                        'connected' => true,
                        'status' => 'ready',
                        'message' => 'WhatsApp Gateway is connected and ready to send messages.'
                    ];
                } else {
                    return [
                        'connected' => false,
                        'status' => $status,
                        'message' => "WhatsApp Gateway session is in '{$status}' state."
                    ];
                }
            } else {
                return [
                    'connected' => false,
                    'status' => 'auth_error',
                    'message' => 'Failed to authenticate with OpenWA server (check API Key).'
                ];
            }
        } catch (\Exception $e) {
            return [
                'connected' => false,
                'status' => 'error',
                'message' => 'Error connecting to OpenWA: ' . $e->getMessage()
            ];
        }
    }
}
