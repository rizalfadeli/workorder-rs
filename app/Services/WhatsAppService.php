<?php

namespace App\Services;

use App\Models\WorkOrder;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class WhatsAppService
{
    public function sendWorkOrderReceived(string $target, WorkOrder $wo): bool
    {
        $token = config('services.fonnte.token');
        $endpoint = config('services.fonnte.endpoint', 'https://api.fonnte.com/send');

        if (empty($token)) {
            Log::warning('Fonnte token belum diatur, pengiriman WA dilewati.');
            return false;
        }

        $normalized = $this->normalizeNumber($target);

        $messageBody =
            "LAPORAN ANDA\n\n" .
            "Halo {$wo->nama_pelapor},\n\n" .
            "Laporan Anda sudah kami terima.\n\n" .
            "Kode WO: {$wo->code}\n" .
            "Barang: {$wo->item_name}\n" .
            "Lokasi: {$wo->location}\n" .
            "Deskripsi: {$wo->description}\n" .
            "Waktu: " . $wo->created_at->format('d/m/Y H:i') . " WIB\n\n" .
            "Tim teknisi akan segera menindaklanjuti laporan Anda.\n\n" .
            "Terima kasih.";

        try {
            $response = Http::asForm()
                ->withHeaders([
                    'Authorization' => $token,
                ])
                ->post($endpoint, [
                    'target' => $normalized,
                    'message' => $messageBody,
                    'countryCode' => '62',
                ]);

            $status = $response->json('status');
            if ($response->successful() && $status !== false) {
                Log::info("WhatsApp Fonnte berhasil dikirim ke {$normalized} (WO: {$wo->code})");
                return true;
            }

            Log::error('Fonnte API Error: ' . $response->body());
            return false;
        } catch (\Throwable $e) {
            Log::error('Fonnte Exception: ' . $e->getMessage());
            return false;
        }
    }

    private function normalizeNumber(string $number): string
    {
        $number = preg_replace('/\D+/', '', $number) ?? '';

        if ($number === '') {
            return '';
        }

        if (str_starts_with($number, '0')) {
            return '62' . substr($number, 1);
        }

        if (str_starts_with($number, '62')) {
            return $number;
        }

        return '62' . $number;
    }
}
