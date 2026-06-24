<?php

namespace App\Notifications;

use App\Models\Ticket;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;

/**
 * Notifikasi ke admin ketika tiket PIC sudah lebih dari 3 jam
 * sejak ditugaskan dan belum mulai dikerjakan (started_at = null).
 */
class PicTicketOverdue extends Notification
{
    use Queueable;

    public function __construct(public readonly Ticket $ticket) {}

    public function via(object $notifiable): array
    {
        return ['database'];
    }

    public function toArray(object $notifiable): array
    {
        $hoursWaiting = $this->ticket->assigned_at
            ? (int) now()->diffInHours($this->ticket->assigned_at)
            : 3;

        return [
            'type'          => 'pic_ticket_overdue',
            'ticket_id'     => $this->ticket->id,
            'ticket_number' => $this->ticket->number,
            'ticket_title'  => $this->ticket->title,
            'department'    => $this->ticket->department?->name ?? '-',
            'pic_name'      => $this->ticket->assignee?->name ?? '-',
            'hours_waiting' => $hoursWaiting,
            'message'       => "⚠ Tiket #{$this->ticket->number} belum dikerjakan oleh PIC " .
                               "({$this->ticket->assignee?->name}) selama {$hoursWaiting} jam.",
            'url'           => route('admin.tiket.show', $this->ticket->id),
        ];
    }
}
