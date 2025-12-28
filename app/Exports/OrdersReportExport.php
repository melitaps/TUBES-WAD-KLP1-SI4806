<?php

namespace App\Exports;

use App\Models\Order;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\WithStyles;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;

class OrdersReportExport implements FromCollection, WithHeadings, WithMapping, WithStyles
{
    protected $startDate;
    protected $endDate;
    
    public function __construct($startDate, $endDate)
    {
        $this->startDate = $startDate;
        $this->endDate = $endDate;
    }
    
    public function collection()
    {
        return Order::with('pelanggan')
            ->whereBetween('created_at', [$this->startDate . ' 00:00:00', $this->endDate . ' 23:59:59'])
            ->orderBy('created_at', 'desc')
            ->get();
    }
    
    public function headings(): array
    {
        return [
            'Order ID',
            'Customer Name',
            'Order Date',
            'Status',
            'Total Amount',
            'Payment Method'
        ];
    }
    
    public function map($order): array
    {
        return [
            $order->id,
            $order->customer->name ?? 'N/A',
            $order->customer->email ?? 'N/A',
            $order->customer->phone ?? 'N/A',
            $order->created_at->format('Y-m-d H:i:s'),
            ucfirst($order->status),
            'Rp ' . number_format($order->total_amount, 0, ',', '.'),
            ucfirst($order->payment_method ?? 'N/A')
        ];
    }
    
    public function styles(Worksheet $sheet)
    {
        return [
            1 => ['font' => ['bold' => true]],
        ];
    }
}