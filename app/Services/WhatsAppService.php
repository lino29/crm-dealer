<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class WhatsAppService
{
    /**
     * Send a WhatsApp message using an external API (like Fonnte or Watzap).
     * If the API token is not configured, it logs the message instead (dummy mode).
     *
     * @param string $phone
     * @param string $message
     * @return bool
     */
    public function sendMessage(string $phone, string $message): bool
    {
        $token = env('WHATSAPP_API_TOKEN');

        if (empty($token)) {
            // Dummy mode
            Log::info("Dummy WA to {$phone}: {$message}");
            return true;
        }

        try {
            // Example using Fonnte API
            $response = Http::withHeaders([
                'Authorization' => $token,
            ])->post('https://api.fonnte.com/send', [
                'target' => $phone,
                'message' => $message,
                'countryCode' => '62',
            ]);

            return $response->successful();
        } catch (\Exception $e) {
            Log::error("Failed to send WA to {$phone}: " . $e->getMessage());
            return false;
        }
    }
}
