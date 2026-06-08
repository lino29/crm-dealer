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
        
        // Find schedules that are pending and due tomorrow
        $tomorrow = now()->addDay()->toDateString();
        
        $schedules = \App\Models\ServiceSchedule::where('status', 'pending')
            ->where('notification_status', 'pending')
            ->whereDate('scheduled_date', '<=', $tomorrow)
            ->get();

        foreach ($schedules as $schedule) {
            \App\Jobs\SendWhatsAppReminderJob::dispatch($schedule);
            $this->info("Dispatched reminder for schedule ID: {$schedule->schedule_id}");
        }

        $this->info('Done checking schedules.');
    }
}
