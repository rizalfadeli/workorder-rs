<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Facades\Storage;

class Attachment extends Model
{
    protected $fillable = ['work_order_id', 'type', 'file_path', 'original_name', 'file_size', 'uploaded_by'];

    public function workOrder(): BelongsTo
    {
        return $this->belongsTo(WorkOrder::class);
    }

    public function uploader(): BelongsTo
    {
        return $this->belongsTo(User::class, 'uploaded_by');
    }

    /** URL publik untuk file */
    public function getUrlAttribute(): string
    {
        return Storage::url($this->file_path);
    }
}