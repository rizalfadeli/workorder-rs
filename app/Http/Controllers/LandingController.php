<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\WorkOrder;

class LandingController extends Controller
{
    public function index()
    {
        $total = WorkOrder::count();
        $submitted = WorkOrder::where('status','submitted')->count();
        $progress = WorkOrder::where('status','in_progress')->count();
        $completed = WorkOrder::where('status','completed')->count();

        return view('landing', compact(
            'total',
            'submitted',
            'progress',
            'completed'
        ));
    }

    public function tracking(Request $request)
    {
        $request->validate([
            'code' => 'required'
        ]);

        $workOrder = WorkOrder::where('code', $request->code)->first();

        if (!$workOrder) {
            return back()->with('error','Nomor WO tidak ditemukan');
        }

        return view('result', compact('workOrder'));
    }
}