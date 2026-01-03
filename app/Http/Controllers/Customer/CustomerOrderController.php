<?php

namespace App\Http\Controllers\Customer;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Models\OrderDetail;
use App\Models\Menu;
use App\Models\Kategori;
use App\Models\Customer;
use App\Models\Wilayah;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class CustomerOrderController extends Controller
{
    /**
     * Show menu page for customers
     */
    public function menu()
    {
        $menu = Menu::with('kategori')->get();
        $kategori = Kategori::all();
        
        return view('customer.menu', compact('menu', 'kategori'));
    }
    
    /**
     * Show cart page
     */
    public function cart()
    {
        return view('customer.cart');
    }
    
    /**
     * Show checkout page
     */
    public function checkout()
    {
        return view('customer.checkout');
    }

    /**
     * Create or update customer record in "Manajemen Pelanggan"
     */
    private function createOrUpdateCustomer($orderData, $order)
    {
        // Find customer by phone number (unique identifier)
        $customer = Customer::where('no_hp', $orderData['no_hp'])->first();
        
        if ($customer) {
            // Update existing customer
            $customer->update([
                'total_pesanan' => $customer->total_pesanan + 1,
                'total_transaksi' => $customer->total_transaksi + $orderData['total_harga']
            ]);
        } else {
            // Create new customer record
            // Try to find or create a default wilayah
            $wilayah = $this->getOrCreateWilayah($orderData['alamat']);
            
            Customer::create([
                'nama' => $orderData['nama_pemesan'],
                'no_hp' => $orderData['no_hp'],
                'alamat' => $orderData['alamat'],
                'wilayah_id' => $wilayah->id,
                'total_pesanan' => 1,
                'total_transaksi' => $orderData['total_harga']
            ]);
        }
    }
    
    /**
     * Store new order
     * AUTO-CREATE CUSTOMER RECORD if first order
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'nama_pemesan' => 'required|string',
            'no_hp' => 'required|string',
            'alamat' => 'required|string',
            'metode_pembayaran' => 'required|string',
            'catatan_tambahan' => 'nullable|string',
            'total_harga' => 'required|numeric',
            'items' => 'required|json'
        ]);
        
        DB::beginTransaction();
        
        try {
            // Create order
            $order = Order::create([
                'nama_pemesan' => $validated['nama_pemesan'],
                'no_hp' => $validated['no_hp'],
                'alamat' => $validated['alamat'],
                'metode_pembayaran' => $validated['metode_pembayaran'],
                'catatan_tambahan' => $validated['catatan_tambahan'],
                'total_harga' => $validated['total_harga'],
                'status' => 'menunggu'
            ]);
            
            // Create order details
            $items = json_decode($validated['items'], true);
            foreach ($items as $item) {
                OrderDetail::create([
                    'order_id' => $order->id,
                    'menu_id' => $item['id'],
                    'jumlah' => $item['quantity'],
                    'harga' => $item['price'],
                    'subtotal' => $item['price'] * $item['quantity']
                ]);
            }
            
            // ============================================
            // AUTO-CREATE OR UPDATE CUSTOMER RECORD
            // ============================================
            $this->createOrUpdateCustomer($validated, $order);
            
            DB::commit();
            
            return response()->json([
                'success' => true,
                'order_no' => $order->no_order,
                'message' => 'Pesanan berhasil dibuat'
            ]);
            
        } catch (\Exception $e) {
            DB::rollBack();
            
            return response()->json([
                'success' => false,
                'message' => 'Gagal membuat pesanan: ' . $e->getMessage()
            ], 500);
        }
    }
    
    /**
     * Get or create wilayah (region) for customer
     * You can improve this with address parsing or let admin update later
     */
    private function getOrCreateWilayah($alamat)
    {
        // Try to find existing "Tidak Diketahui" wilayah
        $wilayah = Wilayah::where('kota_kabupaten', 'Belum Diketahui')->first();
        
        if (!$wilayah) {
            // Create default wilayah if not exists
            $wilayah = Wilayah::create([
                'provinsi' => 'Indonesia',
                'kota_kabupaten' => 'Belum Diketahui'
            ]);
        }
        
        return $wilayah;
    }
    
    /**
     * Show customer orders
     */
    public function orders()
    {
        $orders = Order::with(['orderDetails.menu'])
            ->where('nama_pemesan', auth()->user()->name)
            ->latest()
            ->get();
        
        return view('customer.orders', compact('orders'));
    }
    
    /**
     * Show order detail
     */
    public function show($id)
    {
        $order = Order::with(['orderDetails.menu'])->findOrFail($id);
        
        // Check if order belongs to current user (for security)
        if ($order->nama_pemesan !== auth()->user()->name && auth()->user()->role !== 'admin') {
            abort(403, 'Unauthorized access to this order');
        }
        
        return view('customer.order-detail', compact('order'));
    }
}
