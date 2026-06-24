<?php

namespace App\Console\Commands;

use App\Models\AuditLog;
use App\Models\Ticket;
use App\Models\User;
use App\Notifications\PicTicketOverdue;
use Illuminate\Console\Command;

class CheckPicOverdue extends Command
{
    protected $signature   = 'app:check-pic-overdue';
    protected $description = 'Notifikasi admin untuk tiket PIC yang belum dikerjakan lebih dari 3 jam';

    public function handle(): int
    {
        // Tiket PIC: kategori jenis='pic', sudah di-assign, belum dimulai,
        // dan assigned_at sudah > 3 jam yang lalu.
        // Cek via audit_log agar tidak mengirim notifikasi duplikat.
        $overdueTickets = Ticket::with(['category', 'assignee', 'department'])
            ->whereHas('category', fn($q) => $q->where('jenis', 'pic'))
            ->where('status', 'diproses')
            ->whereNotNull('assigned_at')
            ->whereNull('started_at')
            ->where('assigned_at', '<=', now()->subHours(3))
            ->whereNotExists(function ($query) {
                $query->selectRaw(1)
                      ->from('audit_logs')
                      ->whereColumn('audit_logs.entity_id', 'tickets.id')
                      ->where('audit_logs.entity_type', 'Ticket')
                      ->where('audit_logs.action', 'pic_overdue_notified');
            })
            ->get();

        if ($overdueTickets->isEmpty()) {
            $this->info('Tidak ada tiket PIC yang melewati batas waktu.');
            return self::SUCCESS;
        }

        $admins = User::role('admin')->where('status', 'aktif')->get();

        foreach ($overdueTickets as $ticket) {
            // Kirim notifikasi ke semua admin
            foreach ($admins as $admin) {
                $admin->notify(new PicTicketOverdue($ticket));
            }

            // Tandai sudah dinotifikasi agar tidak duplikat
            AuditLog::create([
                'user_id'     => null,
                'action'      => 'pic_overdue_notified',
                'entity_type' => 'Ticket',
                'entity_id'   => $ticket->id,
                'entity_name' => $ticket->number,
                'description' => "Notifikasi overdue PIC dikirim ke admin untuk tiket {$ticket->number} " .
                                 "(PIC: {$ticket->assignee?->name})",
                'ip_address'  => '127.0.0.1',
                'user_agent'  => 'scheduler',
            ]);

            $this->line("  ✓ Notifikasi: #{$ticket->number} → {$ticket->assignee?->name}");
        }

        $this->info("Total: {$overdueTickets->count()} tiket dikirim ke " . $admins->count() . ' admin.');
        return self::SUCCESS;
    }
}
