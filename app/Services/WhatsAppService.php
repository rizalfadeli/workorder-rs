<?php

namespace App\Services;

use App\Models\WorkOrder;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Storage;

class WhatsAppService
{
    /**
     * Kirim notifikasi bahwa Work Order telah diterima.
     */
    public function sendWorkOrderReceived(string $target, WorkOrder $wo): bool
    {
        $token = config('services.fonnte.token');
        $endpoint = config('services.fonnte.endpoint', 'https://api.fonnte.com/send');

        if (empty($token)) {
            Log::warning("Fonnte token belum diatur. Melewati pengiriman WA untuk WO: {$wo->code}");
            return false;
        }

        $normalized = $this->normalizeNumber($target);
        $messageBody = "📢 *LAPORAN ANDA*\n\nHalo {$wo->nama_pelapor},\n\nLaporan Anda sudah kami terima (Kode: {$wo->code}).\nTerima kasih.";

        try {
            $response = Http::timeout(30)
                ->withHeaders(['Authorization' => $token])
                ->asForm()
                ->post($endpoint, [
                    'target' => $normalized,
                    'message' => $messageBody,
                    'countryCode' => '62',
                ]);

            if ($response->successful()) {
                Log::info("WA (Notification) Terkirim ke {$normalized} (WO: {$wo->code})");
                return true;
            }

            Log::error("Fonnte API Error (Notification) [{$response->status()}]: " . $response->body());
            return false;

        } catch (\Throwable $e) {
            Log::error("Exception WA Notification: " . $e->getMessage());
            return false;
        }
    }

    /**
     * Kirim Berita Acara (File PDF) melalui WhatsApp.
     */
    public function sendBeritaAcara(string $target, WorkOrder $wo, string $filePath): bool
    {
        $token = config('services.fonnte.token');
        $endpoint = config('services.fonnte.endpoint', 'https://api.fonnte.com/send');

        if (empty($token)) {
            Log::warning("Fonnte token belum diatur. Melewati pengiriman Berita Acara untuk WO: {$wo->code}");
            return false;
        }

        // Dapatkan full path dari storage public
        $fullPath = Storage::disk('public')->path($filePath);

        if (!file_exists($fullPath)) {
            Log::error("File Berita Acara tidak ditemukan: " . $fullPath);
            return false;
        }

        $normalized = $this->normalizeNumber($target);
        $message = "Halo *{$wo->nama_pelapor}*,\n\nBerikut adalah lampiran dokumen *Berita Acara* untuk Work Order *{$wo->code}*.\n\nTerima kasih.";

        try {
            // Gunakan fopen untuk stream file (lebih efisien)
            $fileResource = fopen($fullPath, 'r');
            $fileName = 'Berita_Acara_' . str_replace(['/', '-'], '_', $wo->code) . '.pdf';

            $response = Http::timeout(60)
                ->withHeaders([
                    'Authorization' => $token,
                ])
                ->attach('file', $fileResource, $fileName)
                ->post($endpoint, [
                    'target'      => $normalized,
                    'message'     => $message,
                    'countryCode' => '62',
                    'delay'       => '2', // Tambahkan sedikit delay jika diperlukan
                ]);

            // Tutup resource file
            if (is_resource($fileResource)) {
                fclose($fileResource);
            }

            if ($response->successful()) {
                Log::info("Berita Acara (FILE) Berhasil Terkirim ke {$normalized} (WO: {$wo->code})");
                return true;
            }

            Log::error("Fonnte API Error (FILE) [{$response->status()}]: " . $response->body());
            return false;

        } catch (\Throwable $e) {
            Log::error("Exception WA File (WO: {$wo->code}): " . $e->getMessage(), [
                'file' => $e->getFile(),
                'line' => $e->getLine()
            ]);
            return false;
        }
    }

    /**
     * Normalisasi nomor telepon ke format internasional (62xxx).
     */
    private function normalizeNumber(string $number): string
    {
        $number = preg_replace('/\D+/', '', $number) ?? '';
        
        if (empty($number)) return '';

        // Awalan 0 -> 62
        if (str_starts_with($number, '0')) {
            return '62' . substr($number, 1);
        }

        // Jika sudah 62, biarkan
        if (str_starts_with($number, '62')) {
            return $number;
        }

        // Default tambahkan 62 jika tidak ada awalan 0 atau 62
        return '62' . $number;
    }
}
