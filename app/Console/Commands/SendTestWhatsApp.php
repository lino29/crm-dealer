<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Services\WhatsAppService;

class SendTestWhatsApp extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:send-test-wa {phone} {message}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Send a test WhatsApp message directly to verify gateway connectivity';

    /**
     * Execute the console command.
     */
    public function handle(WhatsAppService $waService)
    {
        $phone = $this->argument('phone');
        $message = $this->argument('message');

        $this->info("Sending message to {$phone}...");
        
        $result = $waService->sendMessage($phone, $message);

        if ($result['success']) {
            $this->info("✓ Message sent successfully!");
            $this->line("Gateway Response: " . $result['response']);
            if (isset($result['message_id'])) {
                $this->line("Message ID: " . $result['message_id']);
            }
        } else {
            $this->error("✗ Message sending failed!");
            $this->line("Error: " . $result['response']);
        }
    }
}
