<?php

namespace App\Jobs;

use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Queue\Queueable;

class SendWhatsAppReminderJob implements ShouldQueue
{
    use Queueable;

    protected $notification;

    /**
     * Create a new job instance.
     */
    public function __construct(\App\Models\WhatsAppNotification $notification)
    {
        $this->notification = $notification;
    }

    /**
     * Execute the job.
     */
    public function handle(\App\Services\WhatsAppService $waService): void
    {
        $this->notification->loadMissing(['schedule.vehicle.customer', 'customer']);
        $schedule = $this->notification->schedule;
        $customer = $this->notification->customer;
        
        $phone = $this->notification->phone;
        $date = $schedule ? $schedule->scheduled_date->format('d M Y') : 'segera';
        $vehicleInfo = $schedule ? ($schedule->vehicle->police_number . ' (' . $schedule->vehicle->brand . ')') : 'kendaraan Anda';

        $template = \App\Models\Setting::getWaTemplate();
        
        // If message is already generated in notification, use it. Otherwise generate it.
        $message = $this->notification->message ?? str_replace(
            ['{customer_name}', '{vehicle_info}', '{date}'],
            [$customer->customer_name ?? 'Pelanggan', $vehicleInfo, $date],
            $template
        );

        $result = $waService->sendMessage($phone, $message);

        if ($result['success']) {
            $this->notification->update([
                'send_status' => 'sent',
                'sent_at' => now(),
                'gateway_response' => $result['response'],
                'api_response_id' => $result['message_id'] ?? null
            ]);
            
            if ($schedule) {
                $schedule->update(['notification_status' => 'sent']);
            }
        } else {
            $this->notification->update([
                'send_status' => 'failed',
                'gateway_response' => $result['response']
            ]);
        }
    }
}
