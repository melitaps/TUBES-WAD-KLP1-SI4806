<?php

namespace App\Http\Controllers;

Use App\Models\Order;
use Illuminate\Http\Request;
use App\Exports\OrdersReportExport;
use Maatwebsite\Excel\Facades\Excel;
use App\Http\Controllers\Controller;
use App\Models\Report;
use Carbon\Carbon;

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

    public function export(Request $request)
{
    $year  = $request->year;
    $month = $request->month;
    $date  = $request->date;

    return Excel::download(
        new OrdersReportExport($year, $month, $date),
        'laporan_pendapatan.xlsx'
    );
}
}
