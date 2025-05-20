<?php

namespace App\Http\Controllers;

use App\Models\Service;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use App\Jobs\ProcessServicesFromProvider;

class ServiceController extends Controller
{
    public function getService()
    {
        $services = Service::all();

        if ($services->isNotEmpty()) {
            return response()->json([
                'status' => true,
                'message' => 'Berhasil mendapatkan data.',
                'data' => $services,
            ]);
        }

        return response()->json([
            'status' => false,
            'message' => 'Tidak ada data layanan tersedia.',
            'data' => [],
        ]);
    }

    public function StoreOrUpdateService(Request $request)
    {
        $request->validate([
            'profit' => 'required|numeric|min:0',
        ]);


        ProcessServicesFromProvider::dispatch($request->profit);

        return response()->json([
            'status' => true,
            'message' => 'Proses import layanan sedang dijalankan di background.',
        ]);
    }
}
