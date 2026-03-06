<?php

namespace App\Services;

use App\Models\WorkOrder;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Http;

class WhatsAppTeknisi
{
    public function sendWorkOrderReceived(string $phone, WorkOrder $wo): bool
    {
        $token = config('services.fonnte.token');
        $endpoint = config('services.fonnte.endpoint', 'https://api.fonnte.com/send');

        if (empty($token)) {
            Log::warning('Fonnte token belum diatur, pengiriman WA dilewati.');
            return false;
        }

        $phone = $this->normalizeNumber($phone);

        $message =
            "📢 *WORK ORDER BARU*\n\n".
            "Anda ditugaskan untuk menangani Work Order berikut:\n\n".
            "🔖 *Kode WO:* {$wo->code}\n".
            "🖥 *Barang:* {$wo->item_name}\n".
            "📍 *Lokasi:* {$wo->location}\n".
            "📝 *Deskripsi:* {$wo->description}\n".
            "Silakan segera menindaklanjuti laporan ini.";

        try {
            $response = Http::asForm()
                ->timeout(30)
                ->withHeaders([
                    'Authorization' => $token,
                ])
                ->post($endpoint, [
                    'target' => $phone,
                    'message' => $message,
                    'countryCode' => '62',
                ]);

            $status = $response->status();
            $body = $response->body();

            if ($response->successful()) {
                $result = $response->json();
                if (isset($result['status']) && $result['status'] === true) {
                    Log::info("WA terkirim ke {$phone} (WO: {$wo->code})");
                    return true;
                }
                
                Log::warning("Fonnte API returned success status but message failed (Technician): {$body}", [
                    'wo_code' => $wo->code,
                    'target' => $phone
                ]);
                return false;
            }

            Log::error("Fonnte API HTTP Error (Technician) {$status}: {$body}", [
                'wo_code' => $wo->code,
                'target' => $phone
            ]);
            return false;

        } catch (\Illuminate\Http\Client\ConnectionException $e) {
            Log::error("Fonnte Connection Timeout/Error (Technician): " . $e->getMessage(), ['wo_code' => $wo->code]);
            return false;
        } catch (\Throwable $e) {
            Log::error("Fonnte Unexpected Exception (Technician): " . $e->getMessage(), [
                'file' => $e->getFile(),
                'line' => $e->getLine(),
                'wo_code' => $wo->code
            ]);
            return false;
        }
    }

    private function normalizeNumber(string $number): string
    {
        $number = preg_replace('/\D+/', '', $number) ?? '';
        if (str_starts_with($number, '0')) return '62'.substr($number, 1);
        if (str_starts_with($number, '62')) return $number;
        return '62'.$number;
    }
}