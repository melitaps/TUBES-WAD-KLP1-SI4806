<?php

namespace App\Http\Controllers;

use App\Models\Order;
use Illuminate\Http\Request;

class OrderStatusController extends Controller
{
    /**
     * HALAMAN STATUS PESANAN
     */
    public function index()
    {
        $orders = Order::orderBy('created_at', 'desc')->get();
        $slotAktif = Order::whereIn('status', ['menunggu', 'diproses'])->count();

        return view('status', compact('orders', 'slotAktif'));
    }

    /**
     * UPDATE STATUS (SUPER SIMPLE)
     */
    public function updateStatus(Request $request, $id)
    {
        // PAKSA AMBIL STATUS DARI SEMUA SUMBER
        $status = $request->input('status')
               ?? $request->json('status')
               ?? $request->get('status');

        if (!$status) {
            return response()->json([
                'error' => 'STATUS TIDAK TERKIRIM',
                'debug' => [
                    'all' => $request->all(),
                    'raw' => $request->getContent()
                ]
            ], 400);
        }

        $order = Order::findOrFail($id);

        $current = strtolower(trim($order->status));
        $next    = strtolower(trim($status));

        if ($current === 'menunggu' && $next === 'diproses') {
            $order->status = 'diproses';
        } elseif ($current === 'diproses' && $next === 'selesai') {
            $order->status = 'selesai';
            $order->finished_at = now('Asia/Jakarta');
        } else {
            return response()->json([
                'error' => 'TRANSISI SALAH',
                'current' => $current,
                'next' => $next
            ], 400);
        }

        $order->save();

        return response()->json([
            'success' => true,
            'status' => $order->status
        ]);
    }

    public function show($id)
    {
        $order = Order::with(['orderDetails.menu'])->findOrFail($id);
        return view('orders.detail', compact('order'));
    }
}