<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Spatie\Permission\Traits\HasRoles;

class User extends Authenticatable
{
    use HasFactory, Notifiable, HasRoles;

    protected $fillable = [
        'name',
        'email',
        'password',
        'role',
        'department_id',
        'status',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }

    // ─── Role Helpers ────────────────────────────────────────────────────────────
    // Check both the legacy `role` column AND Spatie's model_has_roles table
    // so either source of truth is sufficient.

    public function isAdmin(): bool
    {
        return $this->role === 'admin' || $this->hasRole('admin');
    }

    public function isPetugas(): bool
    {
        return $this->role === 'petugas' || $this->hasRole('petugas');
    }

    public function isSkpd(): bool
    {
        return $this->role === 'skpd' || $this->hasRole('skpd');
    }

    public function isPimpinan(): bool
    {
        return $this->role === 'pimpinan' || $this->hasRole('pimpinan');
    }

    /**
     * Check role against both Spatie roles and legacy role column.
     */
    public function hasRoleName(string|array $roles): bool
    {
        return in_array($this->role, (array) $roles) || $this->hasRole($roles);
    }

    public function isAktif(): bool
    {
        return $this->status === 'aktif';
    }

    // ─── Relationships ────────────────────────────────────────────────────────────

    public function department(): BelongsTo
    {
        return $this->belongsTo(Department::class);
    }

    /** Tiket yang dibuat oleh user ini (sebagai pemohon SKPD) */
    public function tickets(): HasMany
    {
        return $this->hasMany(Ticket::class, 'requester_id');
    }

    /** Tiket yang ditugaskan ke user ini (legacy single assignee - untuk sorting) */
    public function assignedTickets(): HasMany
    {
        return $this->hasMany(Ticket::class, 'assignee_id');
    }

    /** Tiket yang ditugaskan ke user ini via pivot table (many-to-many) */
    public function assignedTicketsMulti(): BelongsToMany
    {
        return $this->belongsToMany(Ticket::class, 'ticket_assignees', 'user_id', 'ticket_id')
            ->withPivot(['assigned_by_id', 'assigned_at']);
    }

    public function comments(): HasMany
    {
        return $this->hasMany(TicketComment::class);
    }

    public function auditLogs(): HasMany
    {
        return $this->hasMany(AuditLog::class);
    }
}
