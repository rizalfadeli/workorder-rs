<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\WorkOrder;
use App\Models\Technician;
use Illuminate\Support\Facades\Http;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function index()
    {
        $stats = [
            'total'         => WorkOrder::count(),
            'submitted'     => WorkOrder::where('status', 'submitted')->count(),
            'in_progress'   => WorkOrder::where('status', 'in_progress')->count(),
            'completed'     => WorkOrder::where('status', 'completed')->count(),
            'broken_total'  => WorkOrder::where('status', 'broken_total')->count(),
            'high_priority' => WorkOrder::where('priority', 'high')
                                ->whereNotIn('status', ['completed'])
                                ->count(),
        ];

        $urgentOrders = WorkOrder::with('user')
            ->where('priority', 'high')
            ->whereNotIn('status', ['completed', 'broken_total'])
            ->orderByDesc('created_at')
            ->limit(5)
            ->get();

        $totalTechnicians = Technician::active()->count();

        return view('admin.dashboard', compact('stats', 'urgentOrders', 'totalTechnicians'));
    }

    // ambil QR WhatsApp atau Cek Status
    public function qr(Request $request)
    {
        $token = config('services.fonnte.token');
        
        if (empty($token)) {
            return response()->json([
                'status' => false,
                'message' => 'Fonnte token belum diatur.'
            ], 400);
        }

        try {
            // 1. Cek status perangkat (Endpoint /device)
            $deviceResponse = Http::withHeaders(['Authorization' => $token])
                ->post(config('services.fonnte.device_endpoint'));
            
            $deviceData = $deviceResponse->json();
            $deviceStatus = $deviceData['device_status'] ?? ($deviceData['data']['device_status'] ?? 'disconnect');
            $isConnect = ($deviceStatus === 'connect' || $deviceStatus === 'connected');

            // Jika sudah terhubung, langsung beritahu FE
            if ($isConnect) {
                return response()->json([
                    'status' => true,
                    'device_status' => 'connect',
                    'message' => 'Perangkat sudah terhubung.'
                ]);
            }

            // Jika FE hanya minta cek status (polling), stop di sini agar tidak kena limit QR
            if ($request->has('status_only')) {
                return response()->json([
                    'status' => false,
                    'device_status' => 'disconnect',
                    'message' => 'Belum terhubung.'
                ]);
            }

            // 2. Jika BELUM terhubung dan FE minta QR (bukan status_only), baru ambil QR
            $qrResponse = Http::withHeaders(['Authorization' => $token])
                ->post(config('services.fonnte.qr_endpoint'));

            $qrData = $qrResponse->json();

            // Jika ternyata sudah connect saat minta QR
            if (isset($qrData['status']) && $qrData['status'] === false && 
                (str_contains($qrData['reason'] ?? '', 'already') || str_contains($qrData['reason'] ?? '', 'connected'))) {
                return response()->json([
                    'status' => true,
                    'device_status' => 'connect',
                    'message' => 'Perangkat sudah terhubung.'
                ]);
            }

            return response()->json(array_merge($qrData, ['device_status' => 'disconnect']));

        } catch (\Throwable $e) {
            \Log::error('Fonnte QR Error: ' . $e->getMessage());
            return response()->json([
                'status' => false,
                'message' => 'Gagal koneksi ke Fonnte.'
            ], 500);
        }
    }


}