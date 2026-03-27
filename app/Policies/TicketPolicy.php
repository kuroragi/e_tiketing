<?php

namespace App\Policies;

use App\Models\Ticket;
use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class TicketPolicy
{
    use HandlesAuthorization;

    /**
     * Admin & petugas selalu bisa sebelum method lain dicek.
     * Pimpinan hanya bisa melihat (bukan bypass penuh).
     */
    public function before(User $user, string $ability): ?bool
    {
        // Admin bypass semua pembatasan
        if ($user->isAdmin()) {
            return true;
        }

        return null; // lanjut ke method individual
    }

    /**
     * Siapa yang boleh melihat daftar tiket?
     * Admin (handled by before), petugas, pimpinan.
     */
    public function viewAny(User $user): bool
    {
        return $user->isAdmin() || $user->isPetugas() || $user->isPimpinan();
    }

    /**
     * Siapa yang boleh melihat detail tiket tertentu?
     * - Admin: semua (via before)
     * - Petugas: hanya tiket yang ditugaskan kepadanya
     * - Pimpinan: semua tiket (read-only)
     * - SKPD: hanya tiket dari departemennya sendiri
     */
    public function view(User $user, Ticket $ticket): bool
    {
        if ($user->isPimpinan()) {
            return true;
        }

        if ($user->isPetugas()) {
            return $ticket->assignees->contains('id', $user->id)
                || $ticket->assignee_id === $user->id;
        }

        if ($user->isSkpd()) {
            return $ticket->department_id === $user->department_id;
        }

        return false;
    }

    /**
     * Siapa yang boleh membuat tiket baru?
     * Hanya SKPD (admin sudah via before).
     */
    public function create(User $user): bool
    {
        return $user->isSkpd();
    }

    /**
     * Siapa yang boleh mengubah status tiket?
     * - Petugas: bisa set diproses, menunggu_verifikasi, ditolak
     * - (Admin via before)
     */
    public function updateStatus(User $user, Ticket $ticket): bool
    {
        if ($user->isPetugas()) {
            // Petugas hanya bisa ubah status tiket yang ditugaskan kepadanya
            return $ticket->assignees->contains('id', $user->id)
                || $ticket->assignee_id === $user->id;
        }

        return false;
    }

    /**
     * Siapa yang boleh men-assign tiket?
     * Petugas bisa assign (misal re-assign ke rekan).
     * Admin via before.
     */
    public function assign(User $user, Ticket $ticket): bool
    {
        return $user->isPetugas();
    }

    /**
     * Siapa yang boleh membatalkan tiket?
     * Hanya requester (pemilik tiket) atau admin (via before).
     */
    public function cancel(User $user, Ticket $ticket): bool
    {
        return $ticket->requester_id === $user->id;
    }

    /**
     * Siapa yang boleh menambah komentar?
     * - Admin via before
     * - Petugas: tiket yang ditugaskan
     * - SKPD: tiket dari departemennya
     * - Pimpinan: tidak boleh komentar
     */
    public function addComment(User $user, Ticket $ticket): bool
    {
        if ($user->isPetugas()) {
            return $ticket->assignees->contains('id', $user->id)
                || $ticket->assignee_id === $user->id;
        }

        if ($user->isSkpd()) {
            return $ticket->department_id === $user->department_id;
        }

        return false;
    }

    /**
     * Siapa yang boleh menambah progress pekerjaan?
     * Hanya petugas yang ditugaskan (admin via before).
     */
    public function addProgress(User $user, Ticket $ticket): bool
    {
        if ($user->isPetugas()) {
            // Pastikan tiket aktif
            if (! in_array($ticket->status, ['baru', 'diproses'])) {
                return false;
            }

            return $ticket->assignees->contains('id', $user->id)
                || $ticket->assignee_id === $user->id;
        }

        return false;
    }

    /**
     * Siapa yang boleh upload lampiran tambahan?
     * - Petugas: tiket yang ditugaskan
     * - SKPD: tiket departemennya
     * - Admin via before
     */
    public function uploadAttachment(User $user, Ticket $ticket): bool
    {
        if ($user->isPetugas()) {
            return $ticket->assignees->contains('id', $user->id)
                || $ticket->assignee_id === $user->id;
        }

        if ($user->isSkpd()) {
            return $ticket->department_id === $user->department_id;
        }

        return false;
    }

    /**
     * Siapa yang boleh download lampiran tiket?
     */
    public function downloadAttachment(User $user, Ticket $ticket): bool
    {
        if ($user->isPimpinan() || $user->isPetugas()) {
            // Petugas semua tiket OK (bisa jadi perlu audit ulang nantinya)
            return true;
        }

        if ($user->isSkpd()) {
            return $ticket->department_id === $user->department_id;
        }

        return false;
    }
}
