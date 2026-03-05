<?php

namespace App\Mail;

use App\Models\WorkOrder;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class TechnicianAssignedMail extends Mailable
{
    use Queueable, SerializesModels;

    public $workOrder;

    public function __construct(WorkOrder $workOrder)
    {
        $this->workOrder = $workOrder;
    }

    public function build()
    {
        return $this->subject('Penugasan Work Order - ' . $this->workOrder->code)
                    ->view('emails.technician_assigned');
    }
}