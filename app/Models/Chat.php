<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Chat extends Model
{
    protected $fillable = ['work_order_id'];

    public function workOrder(): BelongsTo
    {
        return $this->belongsTo(WorkOrder::class);
    }

    public function messages(): HasMany
    {
        return $this->hasMany(ChatMessage::class)->orderBy('created_at');
    }

    /** Hitung pesan belum dibaca untuk user tertentu */
    public function unreadCountFor(int $userId): int
    {
        return $this->messages()
            ->where('sender_id', '!=', $userId)
            ->where('is_read', false)
            ->count();
    }
}