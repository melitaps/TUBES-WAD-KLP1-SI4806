<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Models\OrderDetail;
use App\Models\Customer;
use App\Models\Wilayah;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Barryvdh\DomPDF\Facade\Pdf;

class CustomerController extends Controller
{
    /**
     * MENAMPILKAN DAFTAR PELANGGAN
     */
    public function index(Request $request)
    {
        $query = Customer::with('wilayah');
        if ($request->has('search')) {
            $search = $request->get('search');
            $query->where(function ($q) use ($search) {
                $q->where('nama', 'like', "%{$search}%")
                  ->orWhere('no_hp', 'like', "%{$search}%")
                  ->orWhere('id', 'like', "%{$search}%");
            });
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

 
        return view('customers.index', compact('customers', 'totalPelanggan'));
    }

    /**
     * MENYIMPAN DATA PELANGGAN BARU
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'nama' => 'required|string',
            'no_hp' => 'required|string',
            'alamat' => 'required|string',
            'wilayah_id' => 'required|exists:wilayah,id'
        ]);

        Customer::create([
            'nama' => $validated['nama'],
            'no_hp' => $validated['no_hp'],
            'alamat' => $validated['alamat'],
            'wilayah_id' => $validated['wilayah_id'],
            'total_pesanan' => 0, 
            'total_transaksi' => 0
        ]);

   
        return redirect()->route('customers.index')->with('success', 'Pelanggan berhasil ditambahkan');
    }

    public function update(Request $request, $id)
    {
        $customer = Customer::findOrFail($id);

        $request->validate([
            'nama' => 'sometimes|required|string',
            'no_hp' => 'sometimes|required|string',
            'wilayah_id' => 'sometimes|required|exists:wilayah,id',
            'alamat' => 'sometimes|required|string',
        ]);

        $customer->update($request->all());

        return redirect()->route('customers.index')
            ->with('success', 'Data customer berhasil diupdate');
    }


    public function destroy($id)
{
    $customer = Customer::find($id);

    if (!$customer) {
        return response()->json([
            'message' => 'Customer tidak ditemukan'
        ], 404);
    }

    $customer->delete();

    return response()->json([
        'message' => 'Customer berhasil dihapus'
    ], 200);
}


    public function export(Request $request)
    {
        $query = Customer::with('wilayah');

        if ($request->has('search') && $request->search != '') {
            $search = $request->get('search');
            $query->where(function ($q) use ($search) {
                $q->where('nama', 'like', "%{$search}%")
                  ->orWhere('no_hp', 'like', "%{$search}%");
            });
        }

        $customers = $query->latest()->get();

        if ($request->type == 'pdf' || $request->type == 'excel') { 
            
            $pdf = Pdf::loadView('customers.export-pdf', compact('customers'))
                      ->setPaper('a4', 'portrait');

            return $pdf->download(
                'data-customers-' . now()->format('d-m-Y') . '.pdf'
            );
        }

        return redirect()->back()->with('error', 'Tipe export tidak didukung');
    }
}