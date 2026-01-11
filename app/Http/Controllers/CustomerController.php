<?php

namespace App\Http\Controllers;

// Import Model yang dibutuhkan
use App\Models\Order;
use App\Models\OrderDetail;
use App\Models\Customer;
use App\Models\Wilayah;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
// Import Facade DomPDF untuk proses pembuatan PDF
use Barryvdh\DomPDF\Facade\Pdf;

class CustomerController extends Controller
{
    /**
     * MENAMPILKAN DAFTAR PELANGGAN
     */
    public function index(Request $request)
    {
        // Mengambil data customer beserta relasi wilayahnya (Eager Loading)
        $query = Customer::with('wilayah');

        // Fitur Pencarian: Jika ada input 'search', cari di kolom nama, no_hp, atau ID
        if ($request->has('search')) {
            $search = $request->get('search');
            $query->where(function ($q) use ($search) {
                $q->where('nama', 'like', "%{$search}%")
                  ->orWhere('no_hp', 'like', "%{$search}%")
                  ->orWhere('id', 'like', "%{$search}%");
            });
        }

        // Urutkan dari yang terbaru dan ambil datanya
        $customers = $query->latest()->get();
        // Menghitung total seluruh pelanggan untuk ditampilkan di dashboard
        $totalPelanggan = Customer::count();

        // Cek jika request meminta format JSON (biasanya untuk API)
        if ($request->expectsJson()) {
            return response()->json([
                'status' => 'success',
                'total' => $totalPelanggan,
                'data' => $customers
            ]);
        }

        // Tampilkan halaman web dengan mengirimkan data customers dan totalPelanggan
        return view('customers.index', compact('customers', 'totalPelanggan'));
    }

    /**
     * MENYIMPAN DATA PELANGGAN BARU
     */
    public function store(Request $request)
    {
        // Validasi input: memastikan semua data wajib diisi dan sesuai aturan
        $validated = $request->validate([
            'nama' => 'required|string',
            'no_hp' => 'required|string',
            'alamat' => 'required|string',
            'wilayah_id' => 'required|exists:wilayah,id' // Harus ada di tabel wilayah
        ]);

        // Proses input data ke database menggunakan Mass Assignment
        Customer::create([
            'nama' => $validated['nama'],
            'no_hp' => $validated['no_hp'],
            'alamat' => $validated['alamat'],
            'wilayah_id' => $validated['wilayah_id'],
            'total_pesanan' => 0, // Default awal 0
            'total_transaksi' => 0
        ]);

        // Redirect kembali ke halaman index dengan pesan sukses
        return redirect()->route('customers.index')->with('success', 'Pelanggan berhasil ditambahkan');
    }

    /**
     * MENGUBAH DATA PELANGGAN
     */
    public function update(Request $request, $id)
    {
        // Cari data pelanggan berdasarkan ID, jika tidak ada maka otomatis error 404
        $customer = Customer::findOrFail($id);

        // Validasi data yang akan diubah (sometimes berarti divalidasi jika datanya dikirim)
        $request->validate([
            'nama' => 'sometimes|required|string',
            'no_hp' => 'sometimes|required|string',
            'wilayah_id' => 'sometimes|required|exists:wilayah,id',
            'alamat' => 'sometimes|required|string',
        ]);

        // Update data di database
        $customer->update($request->all());

        return redirect()->route('customers.index')
            ->with('success', 'Data customer berhasil diupdate');
    }

    /**
     * MENGHAPUS DATA PELANGGAN
     */
    public function destroy($id)
    {
        // Cari data lalu hapus
        Customer::findOrFail($id)->delete();
        return redirect()->back()->with('success', 'Customer berhasil dihapus');
    }

    /**
     * FITUR EXPORT PDF
     */
    public function export(Request $request)
    {
        $query = Customer::with('wilayah');

        // Pastikan hasil export sesuai dengan pencarian yang sedang aktif
        if ($request->has('search') && $request->search != '') {
            $search = $request->get('search');
            $query->where(function ($q) use ($search) {
                $q->where('nama', 'like', "%{$search}%")
                  ->orWhere('no_hp', 'like', "%{$search}%");
            });
        }

        $customers = $query->latest()->get();

        // Validasi tipe export: mendownload PDF jika parameternya sesuai
        if ($request->type == 'pdf' || $request->type == 'excel') { 
            
            // 1. Memuat file view 'export-pdf' dan mengirimkan data $customers
            $pdf = Pdf::loadView('customers.export-pdf', compact('customers'))
                      // 2. Mengatur ukuran kertas menjadi A4 dan posisi Portrait
                      ->setPaper('a4', 'portrait');

            // 3. Memberikan perintah download dengan nama file yang mengandung tanggal hari ini
            return $pdf->download(
                'data-customers-' . now()->format('d-m-Y') . '.pdf'
            );
        }

        return redirect()->back()->with('error', 'Tipe export tidak didukung');
    }
}