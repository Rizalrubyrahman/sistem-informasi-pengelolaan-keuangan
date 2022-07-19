<?php

namespace App\Exports;

use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\FromView;
use Maatwebsite\Excel\Concerns\WithEvents;
use Maatwebsite\Excel\Events\AfterSheet;
use PhpOffice\PhpSpreadsheet\Style\NumberFormat;
use Maatwebsite\Excel\Concerns\WithColumnFormatting;
use App\SaleTransaction;

class SaleTransactionExport implements FromView, ShouldAutoSize, WithEvents
{
    /**
    * @return \Illuminate\Support\Collection
    */
    protected $fromDate;
    protected $toDate;

    function __construct($fromDate, $toDate) {
            $this->fromDate = $fromDate;
            $this->toDate = $toDate;
    }
    public function view(): View
    {
        $saleTransactions = SaleTransaction::orderBy('date')->whereBetween('date',[$this->fromDate,$this->toDate])->get();
        return view('admin.transaction.sale.export_excel_transaction',[
            'saleTransactions' => $saleTransactions,
            'fromDate' => $this->fromDate,
            'toDate' => $this->toDate,
        ]);
    }

    public function registerEvents(): array
    {
        return [
            AfterSheet::class    => function(AfterSheet $event) {
 // All headers
                $event->sheet->getDelegate();

            },
        ];
    }
}
