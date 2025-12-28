<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Http;

class HolidayController extends Controller
{
    /**
     * Ambil data hari libur nasional Indonesia
     */
    public function index()
    {
        $response = Http::get(
            'https://raw.githubusercontent.com/farizdotid/DAFTAR-API-LOKAL-INDONESIA/master/data/general/id.json'
        );

        if ($response->failed()) {
            return response()->json([
                'success' => false,
                'message' => 'Gagal mengambil data hari libur nasional'
            ], 500);
        }

        return response()->json([
            'success' => true,
            'data' => $response->json()
        ]);
    }
}
