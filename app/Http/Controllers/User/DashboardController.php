<?php
namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Models\WorkOrder;

class DashboardController extends Controller
{
    public function index()
    {
        $userId = auth()->id();

        $stats = [
            'total'       => WorkOrder::forUser($userId)->count(),
            'submitted'   => WorkOrder::forUser($userId)->where('status', 'submitted')->count(),
            'in_progress' => WorkOrder::forUser($userId)->where('status', 'in_progress')->count(),
            'completed'   => WorkOrder::forUser($userId)->where('status', 'completed')->count(),
        ];

        $recentOrders = WorkOrder::forUser($userId)
            ->with('technician')
            ->orderByPriority()
            ->orderByDesc('created_at')
            ->limit(5)
            ->get();

        return view('user.dashboard', compact('stats', 'recentOrders'));
    }
}