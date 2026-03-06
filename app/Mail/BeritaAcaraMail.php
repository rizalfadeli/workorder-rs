<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class BeritaAcaraMail extends Mailable
{
    use Queueable, SerializesModels;

    public $workOrder;
    public $filePath;

    public function __construct($workOrder, $filePath)
    {
        $this->workOrder = $workOrder;
        $this->filePath  = $filePath;
    }

    public function build()
    {
        return $this->subject('Berita Acara Work Order ' . $this->workOrder->code)
                    ->view('emails.berita_acara')
                    ->attach(
                        storage_path('app/public/' . $this->filePath),
                        [
                            'as'   => 'Berita_Acara_' . $this->workOrder->code . '.pdf',
                            'mime' => 'application/pdf',
                        ]
                    );
    }
}