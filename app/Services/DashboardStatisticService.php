<?php

namespace App\Services;

use App\Models\Dealer;
use App\Models\Customer;
use App\Models\Vehicle;
use App\Models\ServiceSchedule;
use App\Models\ServiceHistory;
use App\Models\ScanLog;
use App\Models\WhatsAppNotification;

class DashboardStatisticService
{
    public function getAdminStats(): array
    {
        return [
            'total_dealers' => Dealer::count(),
            'total_customers' => Customer::count(),
            'total_vehicles' => Vehicle::count(),
            'pending_schedules' => ServiceSchedule::where('status', 'pending')->count(),
            'completed_schedules' => ServiceSchedule::where('status', 'completed')->count(),
            'today_services' => ServiceHistory::whereDate('service_date', today())->count(),
            'today_scans' => ScanLog::whereDate('scanned_at', today())->count(),
            'failed_notifications' => WhatsAppNotification::where('send_status', 'failed')->count(),
            // S6: Due schedules
            'due_schedules' => ServiceSchedule::with(['vehicle.customer'])
                ->where('status', 'pending')
                ->where('scheduled_date', '<=', now()->addDays(7))
                ->orderBy('scheduled_date')
                ->limit(10)
                ->get(),
            // S6: Recent scans
            'recent_scans' => ScanLog::with(['memberCard.customer', 'scanner'])
                ->latest('scanned_at')
                ->limit(10)
                ->get(),
        ];
    }

    public function getLeaderStats(): array
    {
        return [
            'total_customers' => Customer::where('status', 'active')->count(),
            'total_vehicles' => Vehicle::count(),
            'total_services' => ServiceHistory::count(),
            'services_this_month' => ServiceHistory::whereMonth('service_date', now()->month)
                ->whereYear('service_date', now()->year)->count(),
            'pending_schedules' => ServiceSchedule::where('status', 'pending')->count(),
            'notifications_sent' => WhatsAppNotification::where('send_status', 'sent')->count(),
            'total_scans' => ScanLog::count(),
            // S1: Chart.js data - services per month (last 6 months)
            'chart_services' => $this->getMonthlyServiceData(),
            'chart_notifications' => $this->getNotificationStatusData(),
        ];
    }

    private function getMonthlyServiceData(): array
    {
        $months = [];
        $counts = [];
        for ($i = 5; $i >= 0; $i--) {
            $date = now()->subMonths($i);
            $months[] = $date->format('M Y');
            $counts[] = ServiceHistory::whereMonth('service_date', $date->month)
                ->whereYear('service_date', $date->year)->count();
        }
        return ['labels' => $months, 'data' => $counts];
    }

    private function getNotificationStatusData(): array
    {
        return [
            'labels' => ['Sent', 'Failed', 'Pending'],
            'data' => [
                WhatsAppNotification::where('send_status', 'sent')->count(),
                WhatsAppNotification::where('send_status', 'failed')->count(),
                WhatsAppNotification::where('send_status', 'pending')->count(),
            ],
        ];
    }
}
