<?php

namespace App\Notifications;

use App\Models\Ticket;
use App\Models\User;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;

/**
 * Notifikasi ke admin ketika tiket kategori PIC dibuat dan langsung
 * diteruskan ke petugas PIC tanpa melalui admin.
 */
class PicTicketCreated extends Notification
{
    use Queueable;

    public function __construct(
        public readonly Ticket $ticket,
        public readonly User   $picPetugas,
    ) {}

    public function via(object $notifiable): array
    {
        return ['database'];
    }

    public function toArray(object $notifiable): array
    {
        return [
            'type'          => 'pic_ticket_created',
            'ticket_id'     => $this->ticket->id,
            'ticket_number' => $this->ticket->number,
            'ticket_title'  => $this->ticket->title,
            'department'    => $this->ticket->department?->name ?? '-',
            'pic_name'      => $this->picPetugas->name,
            'message'       => "Tiket #{$this->ticket->number} dari {$this->ticket->department?->name} " .
                               "langsung diteruskan ke PIC: {$this->picPetugas->name}.",
            'url'           => route('admin.tiket.show', $this->ticket->id),
        ];
    }
}
