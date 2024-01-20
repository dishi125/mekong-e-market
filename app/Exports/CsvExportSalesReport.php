<?php

namespace App\Exports;

use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\FromView;

class CsvExportSalesReport implements FromView
{
    protected $salesData;
    protected $total_amount;
    public function __construct($salesData, $total_amount)
    {
        $this->salesData = $salesData;
        $this->total_amount = $total_amount;
    }

    public function view(): View
    {
        return view('exports.salesData', [
            'creditManagements' => $this->salesData,
            'total_amount' => $this->total_amount
        ]);
    }
}
