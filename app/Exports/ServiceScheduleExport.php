<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\FromView;
use Illuminate\Contracts\View\View;

class ServiceScheduleExport implements FromView
{
    protected $schedules;

    public function __construct($schedules)
    {
        $this->schedules = $schedules;
    }

    public function view(): View
    {
        return view('admin.reports.exports.schedules', [
            'schedules' => $this->schedules
        ]);
    }
}
