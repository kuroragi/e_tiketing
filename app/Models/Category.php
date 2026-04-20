<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Category extends Model
{
    protected $fillable = [
        'name',
        'jenis',
        'description',
        'status',
    ];

    // ─── Relationships ─────────────────────────────────────────────────────────

    public function tickets(): HasMany
    {
        return $this->hasMany(Ticket::class);
    }

    // ─── Scopes ────────────────────────────────────────────────────────────────

    public function scopeAktif($query)
    {
        return $query->where('status', 'aktif');
    }
}
