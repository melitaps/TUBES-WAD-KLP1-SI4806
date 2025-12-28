<?php

namespace App\Http\Controllers;

use App\Models\Customer;
use App\Models\Wilayah;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class CustomerController extends Controller
{
    public function index(Request $request)
    {
        // Query dasar dengan relasi wilayah
        $query = Customer::with('wilayah');

        // Logic fitur "Cari" sesuai UI
        if ($request->has('search')) {
            $search = $request->get('search');
            $query->where('nama', 'like', "%{$search}%")
                  ->orWhere('no_hp', 'like', "%{$search}%")
                  ->orWhere('id', 'like', "%{$search}%");
        }

        $customers = $query->latest()->get();
        
        // Menghitung total pelanggan untuk card di UI
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

    public function store(Request $request)
    {
        $rules = [
            'nama'       => 'required|string|max:255',
            'no_hp'      => 'required|numeric',
            'alamat'     => 'required|string',
            'wilayah_id' => 'required|exists:wilayah,id',
        ];

        $validator = Validator::make($request->all(), $rules);

        if ($validator->fails()) {
            if ($request->expectsJson()) {
                return response()->json(['errors' => $validator->errors()], 422);
            }
            return redirect()->back()->withErrors($validator)->withInput();
        }

        // Default nilai transaksi sesuai UI jika tidak diisi
        $data = $request->all();
        $data['total_pesanan'] = $request->total_pesanan ?? 0;
        $data['total_transaksi'] = $request->total_transaksi ?? 0;

        $customer = Customer::create($data);

        if ($request->expectsJson()) {
            return response()->json([
                'message' => 'Customer berhasil disimpan!',
                'data' => $customer
            ], 201);
        }

        return redirect()->route('customers.index')->with('success', 'Data pelanggan berhasil ditambahkan.');
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