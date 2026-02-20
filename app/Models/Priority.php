<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Priority extends Model
{
    protected $fillable = [
        'name',
        'weight',
        'color',
        'description',
    ];

    // ─── Relationships ─────────────────────────────────────────────────────────

    public function tickets(): HasMany
    {
        return $this->hasMany(Ticket::class);
    }

    // ─── Scopes ────────────────────────────────────────────────────────────────

    public function scopeOrdered($query)
    {
        return $query->orderByDesc('weight');
    }
}
