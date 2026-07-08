<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\FromView;
use Illuminate\Contracts\View\View;

class WhatsAppNotificationExport implements FromView
{
    protected $notifications;

    public function __construct($notifications)
    {
        $this->notifications = $notifications;
    }

    public function view(): View
    {
        return view('admin.reports.exports.whatsapp', [
            'notifications' => $this->notifications
        ]);
    }
}
