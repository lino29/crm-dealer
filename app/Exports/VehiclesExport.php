<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\FromView;
use Illuminate\Contracts\View\View;

class VehiclesExport implements FromView
{
    protected $vehicles;

    public function __construct($vehicles)
    {
        $this->vehicles = $vehicles;
    }

    public function view(): View
    {
        return view('admin.reports.exports.vehicles', [
            'vehicles' => $this->vehicles
        ]);
    }
}
