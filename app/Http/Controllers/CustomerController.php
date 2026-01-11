<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Models\OrderDetail;
use App\Models\Menu;
use App\Models\Kategori;
use App\Models\Customer;
use App\Models\Wilayah;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class CustomerController extends Controller
{
    public function index(Request $request)
    {

        $query = Customer::with('wilayah');


        if ($request->has('search')) {
            $search = $request->get('search');
            $query->where('nama', 'like', "%{$search}%")
                  ->orWhere('no_hp', 'like', "%{$search}%")
                  ->orWhere('id', 'like', "%{$search}%");
        }

        $customers = $query->latest()->get();
        

        $totalPelanggan = Customer::count();

        if ($request->expectsJson()) {
            return response()->json([
                'status' => 'success',
                'total' => $totalPelanggan,
                'data' => $customers
            ]);
        }

        
    }

        public function indexWeb(Request $request)
    {

        $query = Customer::with('wilayah');


        if ($request->has('search')) {
            $search = $request->get('search');
            $query->where('nama', 'like', "%{$search}%")
                  ->orWhere('no_hp', 'like', "%{$search}%")
                  ->orWhere('id', 'like', "%{$search}%");
        }

        $customers = $query->latest()->get();
        

        $totalPelanggan = Customer::count();


        return view('customers.index', compact('customers', 'totalPelanggan'));
    }

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

    public function update(Request $request, $id)
    {
        $customer = Customer::findOrFail($id);

        $rules = [
            'nama'       => 'sometimes|required|string',
            'no_hp'      => 'sometimes|required|numeric',
            'wilayah_id' => 'sometimes|required|exists:wilayah,id',
        ];

        $request->validate($rules);
        $customer->update($request->all());

        if ($request->expectsJson()) {
            return response()->json(['message' => 'Data diperbarui', 'data' => $customer]);
        }

        return redirect()->route('customers.index')->with('success', 'Data berhasil diupdate.');
    }

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

    public function destroy($id)
    {
        $customer = Customer::findOrFail($id);
        $customer->delete();

        if (request()->expectsJson()) {
            return response()->json(['message' => 'Customer berhasil dihapus']);
        }

        return redirect()->back()->with('success', 'Customer dihapus.');
    }

    public function export(Request $request)
    {
        $type = $request->get('type');
        return response()->json(['message' => "Fitur export $type segera hadir!"]);
    }
}