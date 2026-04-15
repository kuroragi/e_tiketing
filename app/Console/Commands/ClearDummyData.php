<?php

namespace App\Console\Commands;

use App\Models\AuditLog;
use App\Models\Ticket;
use App\Models\TicketAttachment;
use App\Models\TicketComment;
use App\Models\User;
use Illuminate\Console\Command;

class ClearDummyData extends Command
{
    protected $signature = 'app:clear-dummy
                            {--force : Hapus tanpa konfirmasi}';

    protected $description = 'Hapus semua data dummy yang dibuat oleh app:seed-dummy';

    public function handle(): int
    {
        $ticketIds  = Ticket::where('description', 'like', '[DATA DUMMY]%')->pluck('id');
        $dummyUsers = User::where('email', 'like', '%@dummy.test')->pluck('id');

        $commentCount    = TicketComment::whereIn('ticket_id', $ticketIds)->count();
        $attachmentCount = TicketAttachment::whereIn('ticket_id', $ticketIds)->count();
        $auditCount      = AuditLog::where(function ($q) use ($ticketIds, $dummyUsers) {
            $q->whereIn('user_id', $dummyUsers)
              ->orWhere(function ($q2) use ($ticketIds) {
                  $q2->where('entity_type', 'ticket')
                     ->whereIn('entity_id', $ticketIds);
              });
        })->count();

        if ($ticketIds->isEmpty() && $dummyUsers->isEmpty()) {
            $this->info('Tidak ada data dummy yang ditemukan.');
            return self::SUCCESS;
        }

        $this->newLine();
        $this->table(
            ['Jenis Data', 'Jumlah'],
            [
                ['Tiket dummy',           $ticketIds->count()],
                ['Komentar tiket dummy',  $commentCount],
                ['Lampiran tiket dummy',  $attachmentCount],
                ['Audit log terkait',     $auditCount],
                ['Pengguna dummy',        $dummyUsers->count()],
            ]
        );
        $this->newLine();

        if (!$this->option('force')) {
            if (!$this->confirm('<fg=red>Semua data di atas akan dihapus permanen. Lanjutkan?</>', false)) {
                $this->line('Dibatalkan.');
                return self::SUCCESS;
            }
        }

        // Hapus komentar
        $this->line('  Menghapus komentar…');
        TicketComment::whereIn('ticket_id', $ticketIds)->delete();

        // Hapus lampiran (file fisik bila ada)
        $this->line('  Menghapus lampiran…');
        $attachments = TicketAttachment::whereIn('ticket_id', $ticketIds)->get();
        foreach ($attachments as $att) {
            if (!empty($att->file_path) && \Illuminate\Support\Facades\Storage::exists($att->file_path)) {
                \Illuminate\Support\Facades\Storage::delete($att->file_path);
            }
        }
        TicketAttachment::whereIn('ticket_id', $ticketIds)->delete();

        // Hapus audit log (cascade tidak ditanggung migration, hapus manual)
        $this->line('  Menghapus audit log terkait…');
        AuditLog::where(function ($q) use ($ticketIds, $dummyUsers) {
            $q->whereIn('user_id', $dummyUsers)
              ->orWhere(function ($q2) use ($ticketIds) {
                  $q2->where('entity_type', 'ticket')
                     ->whereIn('entity_id', $ticketIds);
              });
        })->delete();

        // Hapus tiket (ticket_assignees akan terhapus otomatis via cascade)
        $this->line('  Menghapus tiket dummy…');
        Ticket::where('description', 'like', '[DATA DUMMY]%')->delete();

        // Hapus pengguna dummy
        $this->line('  Menghapus pengguna dummy…');
        User::where('email', 'like', '%@dummy.test')->delete();

        $this->newLine();
        $this->components->twoColumnDetail('<fg=green>Tiket dihapus</>',    (string) $ticketIds->count());
        $this->components->twoColumnDetail('<fg=green>Pengguna dihapus</>', (string) $dummyUsers->count());
        $this->newLine();
        $this->info('✓ Semua data dummy berhasil dihapus.');

        return self::SUCCESS;
    }
}
