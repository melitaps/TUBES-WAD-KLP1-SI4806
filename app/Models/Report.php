<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class Report
{
    // LIST PENDAPATAN BULANAN
    public static function monthlyDetail($year, $month)
    {
        return DB::table('orders')
            ->select(
                'orders.id',
                'orders.nama_pemesan',
                'orders.total_harga',
                'orders.status',
                'orders.created_at'
            )
            ->whereYear('orders.created_at', $year)
            ->whereMonth('orders.created_at', $month)
            ->orderBy('orders.created_at', 'desc')
            ->get();
    }

    // LIST PENDAPATAN HARIAN
    public static function dailyDetail($date)
    {
        return DB::table('orders')
            ->select(
                'orders.id',
                'orders.nama_pemesan',
                'orders.total_harga',
                'orders.status',
                'orders.created_at'
            )
            ->whereDate('orders.created_at', $date)
            ->orderBy('orders.created_at', 'desc')
            ->get();
    }

    // AMBIL ITEM PESANAN
    public static function orderItems($orderId)
    {
        return DB::table('order_details')
            ->join('menus', 'order_details.menu_id', '=', 'menus.id')
            ->select('menus.nama_menu', 'order_details.jumlah')
            ->where('order_details.order_id', $orderId)
            ->get();
    }

    // TOTAL DASHBOARD
    public static function total($year, $month, $date = null)
    {
        $query = DB::table('orders');

        if ($date) {
            $query->whereDate('created_at', $date);
        } else {
            $query->whereYear('created_at', $year)
                  ->whereMonth('created_at', $month);
        }

        return [
            'total_pesanan' => $query->count(),
            'total_pendapatan' => $query->sum('total_harga')
        ];
    }
}
