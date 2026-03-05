<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\WorkOrder;

class EscalateWorkOrders extends Command
{
    protected $signature = 'workorders:escalate';
    protected $description = 'Escalate work order priority if overdue';

    public function handle()
    {
        WorkOrder::where('status', '!=', 'completed')
            ->get()
            ->each
            ->checkAndEscalatePriority();

        $this->info('Escalation check completed.');
    }
}