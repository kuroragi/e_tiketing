<?php

namespace App\Console\Commands;

use App\Models\Category;
use App\Models\Department;
use App\Models\Priority;
use App\Models\Ticket;
use App\Models\TicketComment;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Hash;

class SeedDummyData extends Command
{
    protected $signature = 'app:seed-dummy
                            {--tickets=60  : Jumlah tiket dummy yang dibuat}
                            {--months=3    : Distribusi tiket dalam N bulan terakhir}
                            {--force       : Jalankan tanpa konfirmasi}';

    protected $description = 'Buat data dummy (tiket, komentar, pengguna) untuk keperluan testing';

    // Distribusi status: baru=10%, diproses=15%, menunggu_verifikasi=8%, selesai=45%, ditolak=12%, dibatalkan=10%
    private array $statuses = ['baru', 'diproses', 'menunggu_verifikasi', 'selesai', 'ditolak', 'dibatalkan'];
    private array $weights  = [10, 15, 8, 45, 12, 10];

    private array $titles = [
        'Jaringan internet kantor tidak bisa diakses',
        'Komputer tidak bisa menyala setelah mati lampu',
        'Software akuntansi error saat cetak laporan bulanan',
        'Website dinas tidak bisa dibuka dari luar jaringan',
        'Email dinas tidak bisa mengirim lampiran besar',
        'Printer tidak terdeteksi setelah update Windows',
        'Sistem absensi fingerprint tidak merespons',
        'Monitor berkedip-kedip dan kadang blank screen',
        'Permohonan instalasi aplikasi desain grafis',
        'VPN kantor tidak bisa terkoneksi dari rumah',
        'SIMDA tidak bisa login setelah ganti password',
        'Perlu backup data server sebelum migrasi sistem',
        'Permintaan akun e-Office untuk pegawai baru',
        'Koneksi Wi-Fi di ruang rapat sangat lambat',
        'Proyektor tidak terdeteksi saat presentasi',
        'File server tidak bisa diakses dari lantai 2',
        'Request pelatihan penggunaan SIPD untuk staf',
        'Antivirus expired di 12 unit komputer kantor',
        'Webcam tidak terdeteksi di aplikasi video conference',
        'Hard disk nyaris penuh, perlu pembersihan data',
        'Permintaan migrasi data dari sistem lama ke baru',
        'Browser tidak bisa membuka halaman SSO',
        'IP address konflik di beberapa komputer',
        'Scanner tidak bisa digunakan setelah update driver',
        'Request penambahan kapasitas storage server',
        'Sistem e-Kinerja tidak bisa simpan data evaluasi',
        'Koneksi internet di lantai 3 sangat tidak stabil',
        'Perlu konfigurasi email Outlook untuk staf baru',
        'CCTV kantor tidak merekam karena storage penuh',
        'Aplikasi SiRUP error saat input data pengadaan',
        'Keyboard dan mouse wireless tidak terhubung',
        'Perlu penambahan RAM pada komputer bagian keuangan',
        'Restore data dari backup setelah hard disk rusak',
        'Tidak bisa akses SIASN untuk update data ASN',
        'Pengajuan penggantian UPS yang sudah rusak',
    ];

    private array $contacts = [
        'Bambang Irawan - 08111222333',
        'Siti Rahayu - 08222333444',
        'Dian Pertiwi - 08333444555',
        'Fitra Hadianto - 08444555666',
        'Yudi Setiawan - 08555666777',
        'Nurul Hidayah - 08666777888',
        'Rendi Saputra - 08777888999',
        'Putri Andini - 08888999000',
        'Abdul Aziz - 08999000111',
    ];

    public function handle(): int
    {
        $ticketCount = (int) $this->option('tickets');
        $months      = (int) $this->option('months');

        if (!$this->option('force')) {
            $existing = Ticket::where('source', 'dummy')->count();
            if ($existing > 0) {
                $this->warn("Sudah ada {$existing} tiket dummy di database.");
            }

            if (!$this->confirm("Akan membuat {$ticketCount} tiket dummy. Lanjutkan?", true)) {
                $this->line('Dibatalkan.');
                return self::SUCCESS;
            }
        }

        $this->newLine();
        $this->info('» Membuat pengguna dummy...');
        [$petugasList, $skpdList] = $this->createDummyUsers();

        if ($petugasList->isEmpty() || $skpdList->isEmpty()) {
            $this->error('Gagal membuat pengguna dummy. Pastikan tabel departments tidak kosong.');
            return self::FAILURE;
        }

        $this->info("» Membuat {$ticketCount} tiket dummy (distribusi {$months} bulan terakhir)...");
        $this->createDummyTickets($petugasList, $skpdList, $ticketCount, $months);

        $this->newLine();
        $this->components->twoColumnDetail('<fg=green>Petugas dummy dibuat</>', (string) $petugasList->count());
        $this->components->twoColumnDetail('<fg=green>Akun SKPD dummy dibuat</>', (string) $skpdList->count());
        $this->components->twoColumnDetail('<fg=green>Tiket dummy dibuat</>', (string) $ticketCount);
        $this->newLine();
        $this->comment('Gunakan <fg=yellow>php artisan app:clear-dummy</> untuk menghapus semua data ini.');

        return self::SUCCESS;
    }

    // ─────────────────────────────────────────────────────────────────────────

    private function createDummyUsers(): array
    {
        $departments = Department::all();

        if ($departments->isEmpty()) {
            return [collect(), collect()];
        }

        $kominfoDept = $departments->where('code', 'KOMINFO')->first() ?? $departments->first();

        // Dummy petugas
        $petugasSeeds = [
            ['name' => 'Ahmad Fauzi (Dummy)',    'email' => 'ahmad.fauzi@dummy.test'],
            ['name' => 'Budi Santoso (Dummy)',   'email' => 'budi.santoso@dummy.test'],
            ['name' => 'Citra Dewi (Dummy)',     'email' => 'citra.dewi@dummy.test'],
        ];

        $petugasList = collect();
        foreach ($petugasSeeds as $data) {
            $user = User::firstOrCreate(['email' => $data['email']], [
                'name'          => $data['name'],
                'password'      => Hash::make('password'),
                'role'          => 'petugas',
                'department_id' => $kominfoDept->id,
                'status'        => 'aktif',
            ]);
            if (!$user->hasRole('petugas')) {
                $user->assignRole('petugas');
            }
            $petugasList->push($user);
        }

        // Dummy SKPD users, satu per department
        $skpdSeeds = [
            ['name' => 'Rina Marlina (Dummy)',    'email' => 'rina.marlina@dummy.test',    'dept' => 'DIKBUD'],
            ['name' => 'Hendra Gunawan (Dummy)',  'email' => 'hendra.gunawan@dummy.test',  'dept' => 'DINKES'],
            ['name' => 'Sri Utami (Dummy)',       'email' => 'sri.utami@dummy.test',       'dept' => 'DINKEU'],
            ['name' => 'Wahyu Prasetyo (Dummy)',  'email' => 'wahyu.prasetyo@dummy.test',  'dept' => 'BKD'],
            ['name' => 'Lestari Ningrum (Dummy)', 'email' => 'lestari.ningrum@dummy.test', 'dept' => 'SETDA'],
            ['name' => 'Dodi Firmansyah (Dummy)', 'email' => 'dodi.firmansyah@dummy.test', 'dept' => 'BAPPEDA'],
            ['name' => 'Yuni Astuti (Dummy)',     'email' => 'yuni.astuti@dummy.test',     'dept' => 'DINSOS'],
            ['name' => 'Eko Prasetya (Dummy)',    'email' => 'eko.prasetya@dummy.test',    'dept' => 'DINPU'],
        ];

        $skpdList = collect();
        foreach ($skpdSeeds as $data) {
            $dept = $departments->where('code', $data['dept'])->first() ?? $departments->first();
            $user = User::firstOrCreate(['email' => $data['email']], [
                'name'          => $data['name'],
                'password'      => Hash::make('password'),
                'role'          => 'skpd',
                'department_id' => $dept->id,
                'status'        => 'aktif',
            ]);
            if (!$user->hasRole('skpd')) {
                $user->assignRole('skpd');
            }
            $skpdList->push($user);
        }

        return [$petugasList, $skpdList];
    }

    private function createDummyTickets(
        \Illuminate\Support\Collection $petugas,
        \Illuminate\Support\Collection $skpdUsers,
        int $count,
        int $months
    ): void {
        $categories = Category::all();
        $priorities  = Priority::all();

        if ($categories->isEmpty() || $priorities->isEmpty()) {
            $this->error('Kategori atau prioritas kosong. Jalankan seeder master data terlebih dahulu.');
            return;
        }

        $bar = $this->output->createProgressBar($count);
        $bar->setFormat(' %current%/%max% [%bar%] %percent:3s%% – %message%');
        $bar->start();

        for ($i = 0; $i < $count; $i++) {
            $skpdUser  = $skpdUsers->get($i % $skpdUsers->count());
            $category  = $categories->get($i % $categories->count());
            $priority  = $priorities->random();
            $status    = $this->weightedRandom($this->statuses, $this->weights);
            $title     = $this->titles[$i % count($this->titles)];
            $contact   = $this->contacts[$i % count($this->contacts)];

            // Distribusi waktu: lebih banyak tiket di bulan-bulan terakhir
            $daysAgo  = random_int(0, $months * 30);
            $createdAt = Carbon::now()
                ->subDays($daysAgo)
                ->subHours(random_int(0, 23))
                ->subMinutes(random_int(0, 59));

            $prefix   = $createdAt->format('Y-m');
            $existing = Ticket::where('number', 'like', $prefix . '-%')->count();
            $number   = $prefix . '-' . str_pad($existing + 1, 4, '0', STR_PAD_LEFT);

            $assignee   = null;
            $assignedAt = null;
            $startedAt  = null;
            $closedAt   = null;

            if (in_array($status, ['diproses', 'menunggu_verifikasi', 'selesai', 'ditolak'])) {
                $assignee   = $petugas->random();
                $assignedAt = $createdAt->copy()->addHours(random_int(1, 8));
                $startedAt  = $assignedAt->copy()->addHours(random_int(1, 4));
            }

            if ($status === 'selesai') {
                $closedAt = $startedAt->copy()->addDays(random_int(1, 7));
            } elseif ($status === 'ditolak') {
                $closedAt = $assignedAt->copy()->addHours(random_int(2, 24));
            }

            $ticket = Ticket::create([
                'number'        => $number,
                'title'         => $title,
                'description'   => '[DATA DUMMY] ' . fake()->paragraph(3),
                'requester_id'  => $skpdUser->id,
                'department_id' => $skpdUser->department_id,
                'category_id'   => $category->id,
                'priority_id'   => $priority->id,
                'assignee_id'   => $assignee?->id,
                'status'        => $status,
                'contact_pic'   => $contact,
                'source'        => 'internal',
                'assigned_at'   => $assignedAt,
                'started_at'    => $startedAt,
                'closed_at'     => $closedAt,
                'summary'       => $status === 'selesai' ? 'Permasalahan telah ditangani dan sistem kembali normal. [DUMMY]' : null,
                'created_at'    => $createdAt,
                'updated_at'    => $closedAt ?? $startedAt ?? $createdAt,
            ]);

            // Relasi many-to-many assignee
            if ($assignee) {
                $ticket->assignees()->syncWithoutDetaching([
                    $assignee->id => [
                        'assigned_by_id' => $assignee->id,
                        'assigned_at'    => $assignedAt,
                    ],
                ]);
            }

            // Komentar otomatis
            if ($assignee && $status !== 'baru') {
                TicketComment::create([
                    'ticket_id'  => $ticket->id,
                    'user_id'    => $assignee->id,
                    'body'       => 'Tiket diterima dan sedang dalam proses penanganan. [DUMMY]',
                    'type'       => 'status_change',
                    'created_at' => $assignedAt,
                    'updated_at' => $assignedAt,
                ]);
            }

            if ($status === 'selesai') {
                TicketComment::create([
                    'ticket_id'  => $ticket->id,
                    'user_id'    => $assignee->id,
                    'body'       => 'Pekerjaan telah selesai dilaksanakan. Sistem kembali berfungsi normal. [DUMMY]',
                    'type'       => 'status_change',
                    'created_at' => $closedAt,
                    'updated_at' => $closedAt,
                ]);
            }

            if ($status === 'ditolak') {
                TicketComment::create([
                    'ticket_id'  => $ticket->id,
                    'user_id'    => $assignee->id,
                    'body'       => 'Tiket ditolak karena tidak sesuai dengan lingkup layanan Kominfo. [DUMMY]',
                    'type'       => 'status_change',
                    'created_at' => $closedAt,
                    'updated_at' => $closedAt,
                ]);
            }

            $bar->setMessage($status);
            $bar->advance();
        }

        $bar->finish();
        $this->newLine();
    }

    private function weightedRandom(array $items, array $weights): string
    {
        $total      = array_sum($weights);
        $rand       = random_int(1, $total);
        $cumulative = 0;

        foreach ($items as $i => $item) {
            $cumulative += $weights[$i];
            if ($rand <= $cumulative) {
                return $item;
            }
        }

        return $items[array_key_last($items)];
    }
}
