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

        if (empty($normalized)) {
            Log::error('Nomor WhatsApp tidak valid.');
            return false;
        }

        // waktu realtime WIB
        $time = now()->setTimezone('Asia/Jakarta')->format('d/m/Y H:i');

        $messageBody =
            "📢 *LAPORAN ANDA*\n\n" .
            "Halo {$wo->nama_pelapor},\n\n" .
            "Laporan Anda sudah kami terima dengan detail berikut:\n\n" .
            "🔖 *Kode WO:* {$wo->code}\n" .
            "🖥 *Barang:* {$wo->item_name}\n" .
            "📍 *Lokasi:* {$wo->location}\n" .
            "📝 *Deskripsi:* {$wo->description}\n" .
            "⏰ *Waktu:* {$time} WIB\n\n" .
            "Tim teknisi kami akan segera menindaklanjuti laporan Anda.\n\n" .
            "Terima kasih telah menggunakan sistem Work Order.";

        try {

            $response = Http::asForm()
                ->timeout(30)
                ->withHeaders([
                    'Authorization' => $token,
                ])
                ->post($endpoint, [
                    'target' => $normalized,
                    'message' => $messageBody,
                    'countryCode' => '62',
                ]);

            if ($response->successful()) {

                $result = $response->json();

                if (isset($result['status']) && $result['status'] !== false) {
                    Log::info("WhatsApp berhasil dikirim ke {$normalized} (WO: {$wo->code})");
                    return true;
                }

                Log::error('Fonnte API Error: ' . $response->body());
                return false;
            }

            Log::error('HTTP Error Fonnte: ' . $response->body());
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

        // jika mulai dari 0
        if (str_starts_with($number, '0')) {
            return '62' . substr($number, 1);
        }

        // jika sudah 62
        if (str_starts_with($number, '62')) {
            return $number;
        }

        // default tambahkan 62
        return '62' . $number;
    }
}