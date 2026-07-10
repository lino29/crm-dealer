<?php

namespace App\Console\Commands;

use Illuminate\Console\Attributes\Description;
use Illuminate\Console\Attributes\Signature;
use Illuminate\Console\Command;

#[Signature('app:check-service-schedules')]
#[Description('Check service schedules and trigger WhatsApp reminders')]
class CheckServiceSchedules extends Command
{
    /**
     * Execute the console command.
     */
    public function handle()
    {
        $this->info('Checking upcoming service schedules...');
        
        // Find schedules that are pending and due in the next 3 days (H-3)
        $dueDate = now()->addDays(3)->toDateString();
        
        $schedules = \App\Models\ServiceSchedule::with(['vehicle.customer'])
            ->where('status', 'pending')
            ->where('notification_status', 'pending')
            ->whereDate('scheduled_date', '<=', $dueDate)
            ->whereDoesntHave('whatsappNotifications')
            ->get();

        foreach ($schedules as $schedule) {
            $customer = $schedule->vehicle->customer ?? null;
            if (!$customer || !$customer->phone) {
                continue;
            }

            // Create reminder message from template
            $template = \App\Models\Setting::getWaTemplate();
            $vehicleInfo = $schedule->vehicle ? ($schedule->vehicle->police_number . ' (' . $schedule->vehicle->brand . ')') : 'kendaraan Anda';
            $date = $schedule->scheduled_date ? $schedule->scheduled_date->format('d M Y') : 'segera';
            
            $message = str_replace(
                ['{customer_name}', '{vehicle_info}', '{date}'],
                [$customer->customer_name ?? 'Pelanggan', $vehicleInfo, $date],
                $template
            );

            // Create notification record
            $notification = \App\Models\WhatsAppNotification::create([
                'schedule_id' => $schedule->schedule_id,
                'customer_id' => $customer->customer_id,
                'phone' => $customer->phone,
                'message' => $message,
                'send_status' => 'pending',
                'created_by' => null, // Triggered by scheduler
            ]);

            // Dispatch job
            \App\Jobs\SendWhatsAppReminderJob::dispatch($notification);
            
            $this->info("Dispatched reminder for schedule ID: {$schedule->schedule_id}");
        }

        $this->info('Done checking schedules.');
    }
}
