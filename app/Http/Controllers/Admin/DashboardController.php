<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\WorkOrder;
use App\Models\Technician;
use Illuminate\Support\Facades\Http; // WAJIB ditambahkan

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
            ->orderByPriority()
            ->orderByDesc('created_at')
            ->limit(5)
            ->get();

        $totalTechnicians = Technician::active()->count();

        return view('admin.dashboard', compact('stats', 'urgentOrders', 'totalTechnicians'));
    }

    public function qr()
{
    $response = Http::withHeaders([
        'Authorization' => env('FONNTE_TOKEN')
    ])->post(env('FONNTE_QR'));

    return response()->json($response->json());
}
}