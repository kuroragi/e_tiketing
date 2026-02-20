<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class TicketAttachment extends Model
{
    protected $fillable = [
        'ticket_id',
        'user_id',
        'original_name',
        'stored_name',
        'path',
        'mime_type',
        'size',
    ];

    // ─── Relationships ─────────────────────────────────────────────────────────

    public function ticket(): BelongsTo
    {
        return $this->belongsTo(Ticket::class);
    }

    public function uploader(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    // ─── Helpers ──────────────────────────────────────────────────────────────

    public function humanSize(): string
    {
        $bytes = $this->size;
        if ($bytes >= 1048576) return round($bytes / 1048576, 1) . ' MB';
        if ($bytes >= 1024)    return round($bytes / 1024, 1) . ' KB';
        return $bytes . ' B';
    }

    public function isImage(): bool
    {
        return str_starts_with($this->mime_type, 'image/');
    }

    public function downloadUrl(): string
    {
        return route('tiket.attachment.download', $this->id);
    }
}
