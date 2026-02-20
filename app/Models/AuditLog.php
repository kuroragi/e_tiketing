<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class AuditLog extends Model
{
    public $timestamps = false;

    protected $fillable = [
        'user_id',
        'action',
        'entity_type',
        'entity_id',
        'entity_name',
        'old_value',
        'new_value',
        'description',
        'ip_address',
        'user_agent',
    ];

    protected $casts = [
        'old_value'  => 'array',
        'new_value'  => 'array',
        'created_at' => 'datetime',
    ];

    // ─── Relationships ─────────────────────────────────────────────────────────

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    // ─── Helpers ──────────────────────────────────────────────────────────────

    public function actionLabel(): string
    {
        return match ($this->action) {
            'created'        => 'Dibuat',
            'updated'        => 'Diperbarui',
            'deleted'        => 'Dihapus',
            'status_changed' => 'Status Diubah',
            'assigned'       => 'Ditugaskan',
            'login'          => 'Login',
            'logout'         => 'Logout',
            default          => ucfirst($this->action),
        };
    }
}
