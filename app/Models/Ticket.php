<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Ticket extends Model
{
    protected $fillable = [
        'number',
        'title',
        'description',
        'requester_id',
        'department_id',
        'category_id',
        'priority_id',
        'assignee_id',
        'status',
        'contact_pic',
        'target_date',
        'assigned_at',
        'started_at',
        'closed_at',
        'summary',
    ];

    protected $casts = [
        'target_date'  => 'date',
        'assigned_at'  => 'datetime',
        'started_at'   => 'datetime',
        'closed_at'    => 'datetime',
    ];

    // ─── Auto Numbering ────────────────────────────────────────────────────────

    /**
     * Generate nomor tiket unik: YYYY-MM-XXXX
     */
    public static function generateNumber(): string
    {
        $prefix = now()->format('Y-m');
        $last = static::where('number', 'like', $prefix . '-%')
            ->orderByDesc('number')
            ->value('number');

        $seq = $last ? ((int) substr($last, -4)) + 1 : 1;

        return $prefix . '-' . str_pad($seq, 4, '0', STR_PAD_LEFT);
    }

    // ─── Relationships ─────────────────────────────────────────────────────────

    public function requester(): BelongsTo
    {
        return $this->belongsTo(User::class, 'requester_id');
    }

    public function assignee(): BelongsTo
    {
        return $this->belongsTo(User::class, 'assignee_id');
    }

    public function department(): BelongsTo
    {
        return $this->belongsTo(Department::class);
    }

    public function category(): BelongsTo
    {
        return $this->belongsTo(Category::class);
    }

    public function priority(): BelongsTo
    {
        return $this->belongsTo(Priority::class);
    }

    public function comments(): HasMany
    {
        return $this->hasMany(TicketComment::class)->orderBy('created_at');
    }

    public function attachments(): HasMany
    {
        return $this->hasMany(TicketAttachment::class)->orderBy('created_at');
    }

    // ─── Scopes ────────────────────────────────────────────────────────────────

    public function scopeBaru($query)
    {
        return $query->where('status', 'baru');
    }

    public function scopeDiproses($query)
    {
        return $query->where('status', 'diproses');
    }

    public function scopeSelesai($query)
    {
        return $query->where('status', 'selesai');
    }

    public function scopeAktif($query)
    {
        return $query->whereIn('status', ['baru', 'diproses']);
    }

    public function scopeForDepartment($query, int $departmentId)
    {
        return $query->where('department_id', $departmentId);
    }

    public function scopeAssignedTo($query, int $userId)
    {
        return $query->where('assignee_id', $userId);
    }

    // ─── Helpers ───────────────────────────────────────────────────────────────

    public function statusBadgeClass(): string
    {
        return match ($this->status) {
            'baru'       => 'secondary',
            'diproses'   => 'info',
            'selesai'    => 'success',
            'ditolak'    => 'danger',
            'dibatalkan' => 'warning',
            default      => 'secondary',
        };
    }

    public function statusLabel(): string
    {
        return match ($this->status) {
            'baru'       => 'Baru',
            'diproses'   => 'Diproses',
            'selesai'    => 'Selesai',
            'ditolak'    => 'Ditolak',
            'dibatalkan' => 'Dibatalkan',
            default      => ucfirst($this->status),
        };
    }

    public function isOpen(): bool
    {
        return in_array($this->status, ['baru', 'diproses']);
    }

    public function isClosed(): bool
    {
        return in_array($this->status, ['selesai', 'ditolak', 'dibatalkan']);
    }

    /**
     * Durasi penyelesaian dalam hari (jika sudah selesai)
     */
    public function resolutionDays(): ?int
    {
        if (! $this->closed_at) {
            return null;
        }

        return (int) $this->created_at->diffInDays($this->closed_at);
    }
}
