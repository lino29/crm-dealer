<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\FromView;
use Illuminate\Contracts\View\View;

class ServicesExport implements FromView
{
    protected $services;

    public function __construct($services)
    {
        $this->services = $services;
    }

    public function view(): View
    {
        return view('admin.reports.exports.services', [
            'services' => $this->services
        ]);
    }
}
