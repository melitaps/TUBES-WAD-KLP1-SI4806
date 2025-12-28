<?php

namespace App\Http\Controllers;

Use App\Models\Order;
use Illuminate\Http\Request;
use App\Exports\OrdersReportExport;
use Maatwebsite\Excel\Facades\Excel;

class ReportController extends Controller
{
    public function index(Request $request)
    {
        $query = Order::with('customer');

        if ($request->filled('start_date') && $request->filled('end_date')) {
            $query->whereBetween('created_at', [
                $request->start_date,
                $request->end_date
            ]);
        }

        $orders = $query->get();

        // Statistik
        $totalOrder = $orders->count();
        $totalIncome = $orders->sum('total_harga');

        return view('reports.index', compact(
            'orders',
            'totalOrder',
            'totalIncome'
        ));
    }

    public function export(Request $request)
    {
        return Excel::download(
            new OrdersReportExport(
                $request->start_date,
                $request->end_date
            ),
            'laporan-orders.xlsx'
        );
    }
}
