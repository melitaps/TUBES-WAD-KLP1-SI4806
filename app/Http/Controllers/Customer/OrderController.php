<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Order;
use App\Models\OrderDetail;
use App\Models\Menu;
use Illuminate\Support\Facades\DB;

class OrderController extends Controller
{
    /**
     * GET /api/orders
     */
    public function index()
    {
        $orders = Order::with('orderDetails.menu')
            ->orderBy('created_at', 'desc')
            ->get();

        return response()->json([
            'success' => true,
            'data' => $orders
        ]);
    }

    /**
     * POST /api/orders
     */
    public function store(Request $request)
    {
        // Max 20 pesanan aktif
        $aktif = Order::whereIn('status', ['pending', 'diproses'])->count();
        if ($aktif >= 20) {
            return response()->json([
                'success' => false,
                'message' => 'Pemesanan sedang penuh'
            ], 400);
        }

        $request->validate([
            'nama_pemesan' => 'required|string',
            'no_hp' => 'required|string',
            'alamat' => 'required|string',
            'metode_pembayaran' => 'required|in:cash,qris',
            'catatan_tambahan' => 'nullable|string',
            'items' => 'required|array|min:1',
            'items.*.menu_id' => 'required|exists:menus,id',
            'items.*.jumlah' => 'required|integer|min:1'
        ]);

        DB::beginTransaction();

        try {
            $order = Order::create([
                'nama_pemesan' => $request->nama_pemesan,
                'no_hp' => $request->no_hp,
                'alamat' => $request->alamat,
                'metode_pembayaran' => $request->metode_pembayaran,
                'catatan_tambahan' => $request->catatan_tambahan,
                'total_harga' => 0,
                'status' => 'pending'
            ]);

            $total = 0;

            foreach ($request->items as $item) {
                $menu = Menu::findOrFail($item['menu_id']);
                $subtotal = $menu->harga * $item['jumlah'];
                $total += $subtotal;

                OrderDetail::create([
                    'order_id' => $order->id,
                    'menu_id' => $menu->id,
                    'jumlah' => $item['jumlah'],
                    'harga' => $menu->harga,
                    'subtotal' => $subtotal
                ]);
            }

            $order->update(['total_harga' => $total]);

            DB::commit();

            return response()->json([
                'success' => true,
                'message' => 'Pesanan berhasil dibuat',
                'data' => $order->load('orderDetails.menu')
            ], 201);

        } catch (\Exception $e) {
            DB::rollBack();

            return response()->json([
                'success' => false,
                'message' => 'Gagal membuat pesanan',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * GET /api/orders/{id}
     */
    public function show($id)
    {
        $order = Order::with('orderDetails.menu')->find($id);

        if (!$order) {
            return response()->json([
                'success' => false,
                'message' => 'Pesanan tidak ditemukan'
            ], 404);
        }

        return response()->json([
            'success' => true,
            'data' => $order
        ]);
    }
}
