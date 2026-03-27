<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;

class TicketComment extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'ticket_id',
        'user_id',
        'body',
        'type',
    ];

    // ─── Relationships ─────────────────────────────────────────────────────────

    public function ticket(): BelongsTo
    {
        return $this->belongsTo(Ticket::class);
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    // ─── Helpers ──────────────────────────────────────────────────────────────

    public function typeLabel(): string
    {
        return match ($this->type) {
            'comment'       => 'Komentar',
            'note'          => 'Catatan Teknis',
            'status_change' => 'Perubahan Status',
            'assignment'    => 'Penugasan',
            'progress'      => 'Progress Pekerjaan',
            default         => ucfirst($this->type),
        };
    }

    // ─── Scopes ───────────────────────────────────────────────────────────────

    public function scopeProgress($query)
    {
        return $query->where('type', 'progress');
    }

    public function scopePublic($query)
    {
        return $query->whereIn('type', ['comment', 'status_change', 'assignment']);
    }
}
