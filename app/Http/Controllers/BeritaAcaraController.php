<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\WorkOrder;
use App\Mail\BeritaAcaraMail;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Storage;

class BeritaAcaraController extends Controller
{   
    public function generateBeritaAcara(Request $request, WorkOrder $workOrder)
    {
        // 1. Simpan TTD Admin
        if ($request->has('signature')) {
            $dataUri = $request->input('signature');
            $encoded_image = explode(",", $dataUri)[1];
            $decoded_image = base64_decode($encoded_image);
            
            $filename = 'ttd_admin_' . $workOrder->code . '_' . time() . '.png';
            $path = 'signatures/' . $filename;
            
            Storage::disk('public')->put($path, $decoded_image);
            
            // Update database
            $workOrder->update([
                'ttd_admin' => $path
            ]);
        }

        // 2. Logika Generate PDF Anda (tetap sama)
        // ... proses PDF ...
        // $pdf = PDF::loadView('pdf.berita_acara', $data);
        // Storage::disk('public')->put($pdfPath, $pdf->output());

        return response()->json([
            'success' => true,
            'message' => 'Berita Acara dan Tanda Tangan berhasil disimpan!'
        ]);
    }
    public function kirim($id)
    {
        $workOrder = WorkOrder::findOrFail($id);

        // Pastikan email ada
        if (!$workOrder->email) {
            return back()->with('error', 'Email tidak ditemukan pada work order.');
        }

        // Pastikan file berita acara ada
        if (!$workOrder->berita_acara) {
            return back()->with('error', 'File berita acara tidak tersedia.');
        }

        $filePath = $workOrder->berita_acara;

        if (!Storage::disk('public')->exists($filePath)) {
            return back()->with('error', 'File tidak ditemukan di storage.');
        }

        // Kirim ke email dari kolom work_orders.email
        Mail::to($workOrder->email)
            ->send(new BeritaAcaraMail($workOrder, $filePath));

        return back()->with('success', 'Berita acara berhasil dikirim ke email.');
    }
}