<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Category extends Model
{
    protected $fillable = [
        'name',
        'jenis',
        'description',
        'status',
        'auto_assignee_id',
    ];

    // ─── Relationships ─────────────────────────────────────────────────────────

    public function tickets(): HasMany
    {
        return $this->hasMany(Ticket::class);
    }

    /** Petugas yang otomatis ditugaskan untuk kategori ini (nullable). */
    public function autoAssignee(): BelongsTo
    {
        return $this->belongsTo(User::class, 'auto_assignee_id');
    }

    // ─── Scopes ────────────────────────────────────────────────────────────────

    public function scopeAktif($query)
    {
        return $query->where('status', 'aktif');
    }
}
