<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\FromView;
use Illuminate\Contracts\View\View;

class CustomersExport implements FromView
{
    protected $customers;

    public function __construct($customers)
    {
        $this->customers = $customers;
    }

    public function view(): View
    {
        return view('admin.reports.exports.customers', [
            'customers' => $this->customers
        ]);
    }
}
