<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Http;
use App\Models\Pelanggan;
use Barryvdh\DomPDF\Facade\Pdf;
use Maatwebsite\Excel\Facades\Excel;
use App\Exports\PelangganExport;

class PelangganController extends Controller
{
    // URL API Wilayah Indonesia dari GitHub
    private $apiWilayahUrl = 'https://raw.githubusercontent.com/farizdotid/DAFTAR-API-LOKAL-INDONESIA/master/data/location/id.json';

    /**
     * =========1===========
     * Tampilkan daftar semua pelanggan dengan fitur pencarian dan filter
     */
    public function index(Request $request)
    {
        $perPage = $request->input('per_page', 10);
        $search = $request->input('search');
        $startDate = $request->input('start_date');
        $endDate = $request->input('end_date');

        $pelanggan = Pelanggan::query()
            ->withCount('orders as total_pesanan')
            ->withSum('orders as total_transaksi', 'total_price')
            ->when($search, function ($query, $search) {
                return $query->where('nama', 'like', "%{$search}%")
                    ->orWhere('nomor_hp', 'like', "%{$search}%")
                    ->orWhere('alamat', 'like', "%{$search}%")
                    ->orWhere('province_name', 'like', "%{$search}%")
                    ->orWhere('city_name', 'like', "%{$search}%");
            })
            ->when($startDate && $endDate, function ($query) use ($startDate, $endDate) {
                return $query->whereBetween('created_at', [$startDate, $endDate]);
            })
            ->latest()
            ->paginate($perPage);

        return response()->json([
            'message' => 'Data pelanggan berhasil diambil',
            'data' => $pelanggan
        ], 200);
    }

    /**
     * =========2===========
     * Tampilkan detail pelanggan beserta riwayat pesanan
     */
    public function show($id)
    {
        $pelanggan = Pelanggan::with(['orders' => function ($query) {
            $query->latest()->limit(10);
        }])
            ->withCount('orders as total_pesanan')
            ->withSum('orders as total_transaksi', 'total_price')
            ->find($id);

        if (!$pelanggan) {
            return response()->json([
                'message' => 'Pelanggan tidak ditemukan'
            ], 404);
        }

        return response()->json([
            'message' => 'Detail pelanggan berhasil diambil',
            'data' => $pelanggan
        ], 200);
    }

    /**
     * =========3===========
     * Buat data pelanggan baru dengan integrasi API Wilayah Indonesia
     */
    public function store(Request $request)
    {
        // Validasi data pelanggan yang masuk
        $validator = Validator::make($request->all(), [
            'nama' => 'required|string|max:255',
            'nomor_hp' => 'required|string|max:20|unique:pelanggan,nomor_hp',
            'province_id' => 'required|string',
            'province_name' => 'required|string',
            'city_id' => 'required|string',
            'city_name' => 'required|string',
            'district_id' => 'nullable|string',
            'district_name' => 'nullable|string',
            'village_id' => 'nullable|string',
            'village_name' => 'nullable|string',
            'alamat' => 'required|string|max:500'
        ], [
            'nama.required' => 'Nama pelanggan wajib diisi',
            'nomor_hp.required' => 'Nomor HP wajib diisi',
            'nomor_hp.unique' => 'Nomor HP sudah terdaftar',
            'province_id.required' => 'Provinsi wajib dipilih',
            'city_id.required' => 'Kota/Kabupaten wajib dipilih',
            'alamat.required' => 'Alamat lengkap wajib diisi'
        ]);

        if ($validator->fails()) {
            return response()->json([
                'message' => 'Validasi gagal',
                'errors' => $validator->errors()
            ], 422);
        }

        // Buat pelanggan baru
        $pelanggan = Pelanggan::create([
            'nama' => $request->nama,
            'nomor_hp' => $request->nomor_hp,
            'province_id' => $request->province_id,
            'province_name' => $request->province_name,
            'city_id' => $request->city_id,
            'city_name' => $request->city_name,
            'district_id' => $request->district_id,
            'district_name' => $request->district_name,
            'village_id' => $request->village_id,
            'village_name' => $request->village_name,
            'alamat' => $request->alamat
        ]);

        // Kembalikan response sukses dengan data pelanggan
        return response()->json([
            'message' => 'Pelanggan berhasil ditambahkan',
            'data' => $pelanggan
        ], 201);
    }

    /**
     * =========4===========
     * Update data pelanggan
     */
    public function update(Request $request, $id)
    {
        // Cari pelanggan berdasarkan ID
        $pelanggan = Pelanggan::find($id);

        if (!$pelanggan) {
            return response()->json([
                'message' => 'Pelanggan tidak ditemukan'
            ], 404);
        }

        // Validasi data update yang masuk
        $validator = Validator::make($request->all(), [
            'nama' => 'sometimes|required|string|max:255',
            'nomor_hp' => 'sometimes|required|string|max:20|unique:pelanggan,nomor_hp,' . $id,
            'province_id' => 'sometimes|required|string',
            'province_name' => 'sometimes|required|string',
            'city_id' => 'sometimes|required|string',
            'city_name' => 'sometimes|required|string',
            'district_id' => 'nullable|string',
            'district_name' => 'nullable|string',
            'village_id' => 'nullable|string',
            'village_name' => 'nullable|string',
            'alamat' => 'sometimes|required|string|max:500'
        ]);

        if ($validator->fails()) {
            return response()->json([
                'message' => 'Validasi gagal',
                'errors' => $validator->errors()
            ], 422);
        }

        // Update data pelanggan
        $pelanggan->update($request->all());

        // Kembalikan response sukses
        return response()->json([
            'message' => 'Data pelanggan berhasil diupdate',
            'data' => $pelanggan
        ], 200);
    }

    /**
     * =========5===========
     * Hapus data pelanggan
     */
    public function destroy($id)
    {
        // Cari pelanggan berdasarkan ID
        $pelanggan = Pelanggan::find($id);

        if (!$pelanggan) {
            return response()->json([
                'message' => 'Pelanggan tidak ditemukan'
            ], 404);
        }

        // Cek apakah pelanggan memiliki pesanan aktif
        $hasActiveOrders = $pelanggan->orders()
            ->whereIn('status', ['menunggu', 'diproses'])
            ->exists();

        if ($hasActiveOrders) {
            return response()->json([
                'message' => 'Tidak dapat menghapus pelanggan yang memiliki pesanan aktif'
            ], 422);
        }

        // Hapus pelanggan
        $pelanggan->delete();

        // Kembalikan response sukses
        return response()->json([
            'message' => 'Pelanggan berhasil dihapus'
        ], 200);
    }

    /**
     * =========6===========
     * Export data pelanggan ke PDF
     */
    public function exportPdf(Request $request)
    {
        $search = $request->input('search');
        $startDate = $request->input('start_date');
        $endDate = $request->input('end_date');

        $pelanggan = Pelanggan::query()
            ->withCount('orders as total_pesanan')
            ->withSum('orders as total_transaksi', 'total_price')
            ->when($search, function ($query, $search) {
                return $query->where('nama', 'like', "%{$search}%")
                    ->orWhere('nomor_hp', 'like', "%{$search}%");
            })
            ->when($startDate && $endDate, function ($query) use ($startDate, $endDate) {
                return $query->whereBetween('created_at', [$startDate, $endDate]);
            })
            ->get();

        $pdf = Pdf::loadView('exports.pelanggan-pdf', [
            'pelanggan' => $pelanggan,
            'tanggal_export' => now()->format('d-m-Y H:i')
        ]);

        return $pdf->download('data-pelanggan-' . date('Y-m-d') . '.pdf');
    }

    /**
     * =========7===========
     * Export data pelanggan ke Excel
     */
    public function exportExcel(Request $request)
    {
        $search = $request->input('search');
        $startDate = $request->input('start_date');
        $endDate = $request->input('end_date');

        return Excel::download(
            new PelangganExport($search, $startDate, $endDate),
            'data-pelanggan-' . date('Y-m-d') . '.xlsx'
        );
    }

    /**
     * =========8===========
     * Ambil seluruh data wilayah Indonesia (provinces, regencies, districts, villages)
     */
    public function getWilayah()
    {
        try {
            $response = Http::timeout(10)->get($this->apiWilayahUrl);

            if ($response->successful()) {
                $data = $response->json();
                
                return response()->json([
                    'message' => 'Data wilayah berhasil diambil',
                    'data' => $data
                ], 200);
            }

            return response()->json([
                'message' => 'Gagal mengambil data wilayah'
            ], 500);

        } catch (\Exception $e) {
            return response()->json([
                'message' => 'Gagal mengambil data wilayah',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * =========9===========
     * Ambil data provinsi saja
     */
    public function getProvinces()
    {
        try {
            $response = Http::timeout(10)->get($this->apiWilayahUrl);

            if ($response->successful()) {
                $data = $response->json();
                
                // Extract provinces only
                $provinces = [];
                foreach ($data as $province) {
                    $provinces[] = [
                        'id' => $province['id'],
                        'name' => $province['name']
                    ];
                }

                return response()->json([
                    'message' => 'Data provinsi berhasil diambil',
                    'data' => $provinces
                ], 200);
            }

            return response()->json([
                'message' => 'Gagal mengambil data provinsi'
            ], 500);

        } catch (\Exception $e) {
            return response()->json([
                'message' => 'Gagal mengambil data provinsi',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * =========10===========
     * Ambil data kota/kabupaten berdasarkan province_id
     */
    public function getCities($provinceId)
    {
        try {
            $response = Http::timeout(10)->get($this->apiWilayahUrl);

            if ($response->successful()) {
                $data = $response->json();
                
                // Find province and get regencies
                $cities = [];
                foreach ($data as $province) {
                    if ($province['id'] == $provinceId) {
                        foreach ($province['regencies'] as $regency) {
                            $cities[] = [
                                'id' => $regency['id'],
                                'name' => $regency['name']
                            ];
                        }
                        break;
                    }
                }

                return response()->json([
                    'message' => 'Data kota/kabupaten berhasil diambil',
                    'data' => $cities
                ], 200);
            }

            return response()->json([
                'message' => 'Gagal mengambil data kota/kabupaten'
            ], 500);

        } catch (\Exception $e) {
            return response()->json([
                'message' => 'Gagal mengambil data kota/kabupaten',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * =========11===========
     * Ambil data kecamatan berdasarkan city_id
     */
    public function getDistricts($provinceId, $cityId)
    {
        try {
            $response = Http::timeout(10)->get($this->apiWilayahUrl);

            if ($response->successful()) {
                $data = $response->json();
                
                // Find city and get districts
                $districts = [];
                foreach ($data as $province) {
                    if ($province['id'] == $provinceId) {
                        foreach ($province['regencies'] as $regency) {
                            if ($regency['id'] == $cityId) {
                                foreach ($regency['districts'] as $district) {
                                    $districts[] = [
                                        'id' => $district['id'],
                                        'name' => $district['name']
                                    ];
                                }
                                break 2;
                            }
                        }
                    }
                }

                return response()->json([
                    'message' => 'Data kecamatan berhasil diambil',
                    'data' => $districts
                ], 200);
            }

            return response()->json([
                'message' => 'Gagal mengambil data kecamatan'
            ], 500);

        } catch (\Exception $e) {
            return response()->json([
                'message' => 'Gagal mengambil data kecamatan',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * =========12===========
     * Ambil data kelurahan/desa berdasarkan district_id
     */
    public function getVillages($provinceId, $cityId, $districtId)
    {
        try {
            $response = Http::timeout(10)->get($this->apiWilayahUrl);

            if ($response->successful()) {
                $data = $response->json();
                
                // Find district and get villages
                $villages = [];
                foreach ($data as $province) {
                    if ($province['id'] == $provinceId) {
                        foreach ($province['regencies'] as $regency) {
                            if ($regency['id'] == $cityId) {
                                foreach ($regency['districts'] as $district) {
                                    if ($district['id'] == $districtId) {
                                        foreach ($district['villages'] as $village) {
                                            $villages[] = [
                                                'id' => $village['id'],
                                                'name' => $village['name']
                                            ];
                                        }
                                        break 3;
                                    }
                                }
                            }
                        }
                    }
                }

                return response()->json([
                    'message' => 'Data kelurahan/desa berhasil diambil',
                    'data' => $villages
                ], 200);
            }

            return response()->json([
                'message' => 'Gagal mengambil data kelurahan/desa'
            ], 500);

        } catch (\Exception $e) {
            return response()->json([
                'message' => 'Gagal mengambil data kelurahan/desa',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * =========13===========
     * Ambil statistik pelanggan
     */
    public function statistics()
    {
        $totalPelanggan = Pelanggan::count();
        $pelangganBulanIni = Pelanggan::whereMonth('created_at', now()->month)
            ->whereYear('created_at', now()->year)
            ->count();
        
        $topPelanggan = Pelanggan::withCount('orders')
            ->withSum('orders as total_transaksi', 'total_price')
            ->orderBy('total_transaksi', 'desc')
            ->limit(5)
            ->get();

        // Statistik per wilayah
        $pelangganPerProvinsi = Pelanggan::selectRaw('province_name, COUNT(*) as total')
            ->groupBy('province_name')
            ->orderBy('total', 'desc')
            ->limit(5)
            ->get();

        return response()->json([
            'message' => 'Statistik pelanggan berhasil diambil',
            'data' => [
                'total_pelanggan' => $totalPelanggan,
                'pelanggan_bulan_ini' => $pelangganBulanIni,
                'top_pelanggan' => $topPelanggan,
                'pelanggan_per_provinsi' => $pelangganPerProvinsi
            ]
        ], 200);
    }

    /**
     * Method create & edit tidak digunakan karena ini API
     * Biasanya digunakan untuk return view form (web route)
     */
    public function create()
    {
        // Tidak digunakan untuk API
        return response()->json([
            'message' => 'Method not available for API'
        ], 405);
    }

    public function edit($id)
    {
        // Tidak digunakan untuk API
        return response()->json([
            'message' => 'Method not available for API'
        ], 405);
    }
}