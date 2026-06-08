<?php

namespace App\Jobs;

use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Queue\Queueable;

class SendWhatsAppReminderJob implements ShouldQueue
{
    use Queueable;

    protected $schedule;

    /**
     * Create a new job instance.
     */
    public function __construct(\App\Models\ServiceSchedule $schedule)
    {
        $this->schedule = $schedule;
    }

    /**
     * Execute the job.
     */
    public function handle(\App\Services\WhatsAppService $waService): void
    {
        $this->schedule->loadMissing('vehicle.customer');
        $customer = $this->schedule->vehicle->customer;
        
        $phone = $customer->phone;
        $date = $this->schedule->scheduled_date->format('d M Y');
        $vehicleInfo = $this->schedule->vehicle->police_number . ' (' . $this->schedule->vehicle->brand . ')';

        $template = \App\Models\Setting::getWaTemplate();
        
        $message = str_replace(
            ['{customer_name}', '{vehicle_info}', '{date}'],
            [$customer->customer_name, $vehicleInfo, $date],
            $template
        );

        $success = $waService->sendMessage($phone, $message);

        if ($success) {
            $this->schedule->update(['notification_status' => 'sent']);
        } else {
            $this->schedule->update(['notification_status' => 'failed']);
        }
    }
}
