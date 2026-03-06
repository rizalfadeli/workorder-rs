<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Support\Str;

class WorkOrder extends Model
{
    protected $fillable = [
        'code', 'user_id', 'whatsapp', 'item_name', 'location', 'description',
        'priority', 'status', 'technician_id', 'estimated_days', 'admin_notes', 'berita_acara_file', 'berita_acara_generated_at',
        'nama_pelapor','tanda_tangan', 'kategori','ttd_admin','email'
    ];
    protected $casts = [
        'berita_acara_generated_at' => 'datetime',
    ];

    // Konstanta untuk kemudahan referensi
    const PRIORITY_HIGH   = 'high';
    const PRIORITY_MEDIUM = 'medium';
    const PRIORITY_LOW    = 'low';

    const STATUS_SUBMITTED    = 'submitted';
    const STATUS_IN_PROGRESS  = 'in_progress';
    const STATUS_COMPLETED    = 'completed';
    const STATUS_BROKEN_TOTAL = 'broken_total';

    const PRIORITY_ORDER = ['high' => 1, 'medium' => 2, 'low' => 3];

    // ==================== Relationships ====================

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function technician(): BelongsTo
    {
        return $this->belongsTo(Technician::class);
    }

    public function attachments(): HasMany
    {
        return $this->hasMany(Attachment::class);
    }

    public function images(): HasMany
    {
        return $this->hasMany(Attachment::class)->where('type', 'image');
    }

    public function pdfs(): HasMany
    {
        return $this->hasMany(Attachment::class)->where('type', 'pdf');
    }

    public function statusLogs(): HasMany
    {
        return $this->hasMany(StatusLog::class);
    }
    public function chatMessages()
    {
        return $this->hasMany(ChatMessage::class);
    }
    public function chat(): HasOne
    {
        return $this->hasOne(Chat::class);
    }
    public function progress_logs()
    {
        return $this->hasMany(ProgressLog::class);
    }
    // ==================== Scopes ====================

    /** Sort by priority: Tinggi -> Sedang -> Rendah */
    public function scopeOrderByPriority($query)
    {
        return $query->orderByRaw("FIELD(priority, 'high', 'medium', 'low')");
    }

    public function scopeForUser($query, int $userId)
    {
        return $query->where('user_id', $userId);
    }

    // ==================== Accessors ====================

    public function getPriorityLabelAttribute(): string
    {
        return match ($this->priority) {
            'high'   => 'Tinggi',
            'medium' => 'Sedang',
            'low'    => 'Rendah',
            default  => '-',
        };
    }

    public function getStatusLabelAttribute(): string
    {
        return match ($this->status) {
            'submitted'    => 'Diajukan',
            'in_progress'  => 'Diproses',
            'completed'    => 'Selesai',
            'broken_total' => 'Rusak Total',
            default        => '-',
        };
    }

    public function getPriorityColorAttribute(): string
    {
        return match ($this->priority) {
            'high'   => 'red',
            'medium' => 'yellow',
            'low'    => 'green',
            default  => 'gray',
        };
    }

    // ==================== Static Helpers ====================

    /** Generate kode WO unik: WO-20240101-0001 */
    public static function generateCode(): string
    {
        $prefix = 'WO-' . now()->format('Ymd') . '-';
        $last = static::where('code', 'like', $prefix . '%')
            ->orderByDesc('code')->value('code');

        $seq = $last ? ((int) Str::afterLast($last, '-') + 1) : 1;
        return $prefix . str_pad($seq, 4, '0', STR_PAD_LEFT);
    }

    /** Hitung pesan belum dibaca untuk user tertentu di WO ini */
    public function unreadMessagesFor(int $userId): int
    {
        if (!$this->relationLoaded('chat') || !$this->chat) return 0;

        // Jika messages juga sudah diload, gunakan collection agar tidak query ulang
        if ($this->chat->relationLoaded('messages')) {
            return $this->chat->messages
                ->where('sender_id', '!=', $userId)
                ->where('is_read', false)
                ->count();
        }

        return $this->chat->messages()
            ->where('sender_id', '!=', $userId)
            ->where('is_read', false)
            ->count();
    }
    

}