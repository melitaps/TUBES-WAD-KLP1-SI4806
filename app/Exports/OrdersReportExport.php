<?php

namespace App\Exports;

use App\Models\Report;



class OrdersReportExport 
{
    protected $year;
    protected $month;
    protected $date;

    public function __construct($year, $month, $date = null)
    {
        $this->year = $year;
        $this->month = $month;
        $this->date = $date;
    }

    public function collection()
    {
        return $this->date
            ? Report::dailyDetail($this->date)
            : Report::monthlyDetail($this->year, $this->month);
    }

    public function headings(): array
    {
        return [
            'ID Pesanan',
            'Nama Pemesan',
            'Pesanan',
            'Total Harga',
            'Status'
        ];
    }

    public function map($order): array
    {
        $items = Report::orderItems($order->id)
            ->map(fn($i) => $i->nama_menu.' ('.$i->qty.')')
            ->implode(', ');

        return [
            $order->id,
            $order->nama_pemesan,
            $items,
            $order->total_harga,
            $order->status
        ];
    }
}
