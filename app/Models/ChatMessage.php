<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ChatMessage extends Model
{
    public $timestamps = false;

    protected $fillable = ['chat_id', 'sender_id', 'message', 'is_read'];
    protected $casts    = ['created_at' => 'datetime', 'is_read' => 'boolean'];

    public function sender(): BelongsTo
    {
        return $this->belongsTo(User::class, 'sender_id');
    }

    public function chat(): BelongsTo
    {
        return $this->belongsTo(Chat::class);
    }
}