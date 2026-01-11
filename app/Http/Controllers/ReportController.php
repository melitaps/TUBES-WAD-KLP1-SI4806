<?php

namespace App\Http\Controllers;

use App\Models\Order;
use Illuminate\Http\Request;
use App\Exports\OrdersReportExport;
use App\Http\Controllers\Controller;
use App\Models\Report;
use Carbon\Carbon;
use Barryvdh\DomPDF\Facade\Pdf;

class ReportController extends Controller
{
    public function indexWeb(Request $request)
    {
        $now = Carbon::now();

        $year  = $request->year ?? $now->year;
        $month = $request->month ?? $now->month;
        $date  = $request->date;

        if ($date) {
            $orders = Report::dailyDetail($date);
            $total  = Report::total(null, null, $date);
        } else {
            $orders = Report::monthlyDetail($year, $month);
            $total  = Report::total($year, $month);
        }

        // Tambahin item pesanan
        foreach ($orders as $order) {
            $order->items = Report::orderItems($order->id);
        }

        return view('laporan.index', [
            'orders' => $orders,
            'totalPesanan' => $total['total_pesanan'],
            'totalPendapatan' => $total['total_pendapatan'],
            'year' => $year,
            'month' => $month,
            'date' => $date
        ]);
    }

    public function index(Request $request)
    {
        $now = Carbon::now();

        $year  = $request->year ?? $now->year;
        $month = $request->month ?? $now->month;
        $date  = $request->date;

        if ($date) {
            $data = Report::dailyDetail($date);
            $total = Report::total(null, null, $date);
        } else {
            $data = Report::monthlyDetail($year, $month);
            $total = Report::total($year, $month);
        }

        return response()->json([
            'filter' => $date ? 'harian' : 'bulanan',
            'data' => $data,
            'summary' => $total
        ]);
    }

    public function exportPDF(Request $request)
{
    $now   = Carbon::now();
    $year  = $request->year ?? $now->year;
    $month = $request->month ?? $now->month;
    $date  = $request->date;

    if ($date) {
        $orders = Report::dailyDetail($date);
        $total  = Report::total(null, null, $date);
        $title  = 'Laporan Harian - ' . date('d/m/Y', strtotime($date));
    } else {
        $orders = Report::monthlyDetail($year, $month);
        $total  = Report::total($year, $month);
        $title  = 'Laporan Bulanan - ' . date('F Y', mktime(0,0,0,$month,1,$year));
    }

    // Tambahkan item per order
    foreach ($orders as $order) {
        $order->items = Report::orderItems($order->id);
    }

    $pdf = Pdf::loadView('pdf.reports', [
        'title'            => $title,
        'orders'           => $orders,
        'totalPesanan'     => $total['total_pesanan'],
        'totalPendapatan'  => $total['total_pendapatan'],
        'exportDate'       => now()->format('d/m/Y H:i'),
        'period'           => $date ? 'Harian' : 'Bulanan'
    ]);

    $pdf->setPaper('A4', 'portrait');

    $filename = 'laporan-' . ($date ? $date : "$year-$month") . '.pdf';
    return $pdf->download($filename);
}

}
