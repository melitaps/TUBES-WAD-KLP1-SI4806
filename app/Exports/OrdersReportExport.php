<?php

namespace App\Exports;

use App\Models\Report;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;

class OrdersReportExport implements FromCollection, WithHeadings, WithMapping
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
            $order->nama_customer,
            $items,
            $order->total_harga,
            $order->status
        ];
    }
}
