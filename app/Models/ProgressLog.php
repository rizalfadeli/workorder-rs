<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ProgressLog extends Model
{
    use HasFactory;

    protected $fillable = [
        'work_order_id',
        'status',
        'note',
    ];

    public function work_order()
    {
        return $this->belongsTo(WorkOrder::class);
    }
}