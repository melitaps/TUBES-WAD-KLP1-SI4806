<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Models\OrderDetail;
use Illuminate\Http\Request;

class OrderController extends Controller
{
    public function index()
    {
        return Order::with(['details.menu'])->get();
    }

    public function store(Request $request)
    {
        $order = Order::create([
            'nama_pemesan' => $request->nama_pemesan,
            'no_hp' => $request->no_hp,
            'alamat' => $request->alamat,
            'metode_pembayaran' => $request->metode_pembayaran,
            'catatan_tambahan' => $request->catatan,

            'total_harga' => $request->total_harga,
            'status' => 'menunggu'
        ]);

        foreach ($request->items as $item) {
            OrderDetail::create([
                'order_id' => $order->id,
                'menu_id' => $item['menu_id'],
                'jumlah' => $item['qty'],        
                'harga' => $item['harga'],
                'subtotal' => $item['qty'] * $item['harga']
    ]);
}

        return $order->load('details.menu');
    }
}
