<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\WorkOrder;
use App\Services\WhatsAppService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class BeritaAcaraController extends Controller
{
    protected $whatsappService;

    public function __construct(WhatsAppService $whatsappService)
    {
        $this->whatsappService = $whatsappService;
    }

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

        return response()->json([
            'success' => true,
            'message' => 'Berita Acara dan Tanda Tangan berhasil disimpan!'
        ]);
    }

    public function kirim($id)
    {
        $workOrder = WorkOrder::findOrFail($id);

        // Pastikan nomor WhatsApp ada
        if (!$workOrder->whatsapp) {
            return back()->with('error', 'Nomor WhatsApp tidak ditemukan pada work order.');
        }

        // Pastikan file berita acara ada
        // Menggunakan kolom berita_acara_file sesuai model WorkOrder
        $filePath = $workOrder->berita_acara_file;

        if (!$filePath || !Storage::disk('public')->exists($filePath)) {
            return back()->with('error', 'File berita acara tidak tersedia atau tidak ditemukan.');
        }

        // Kirim melalui WhatsApp Service
        $sent = $this->whatsappService->sendBeritaAcara(
            $workOrder->whatsapp, 
            $workOrder, 
            $filePath
        );

        if ($sent) {
            return back()->with('success', 'Berita acara berhasil dikirim melalui WhatsApp.');
        }

        return back()->with('error', 'Gagal mengirim berita acara melalui WhatsApp. Periksa koneksi Fonnte.');
    }
}