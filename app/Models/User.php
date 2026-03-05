<?php
namespace App\Models;

use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    use Notifiable;

    protected $fillable = ['name', 'email', 'password', 'role_id', 'unit'];
    protected $hidden   = ['password', 'remember_token'];

    public function role(): BelongsTo
    {
        return $this->belongsTo(Role::class);
    }

    public function workOrders(): HasMany
    {
        return $this->hasMany(WorkOrder::class);
    }

    /** Helper: cek apakah user adalah admin */
    public function isAdmin(): bool
    {
        return $this->role?->name === 'admin';
    }

    /** Helper: cek apakah user biasa */
    public function isUser(): bool
    {
        return $this->role?->name === 'user';
    }
}