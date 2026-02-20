<?php

namespace Database\Seeders;

use App\Models\Category;
use App\Models\Department;
use App\Models\Priority;
use App\Models\Ticket;
use App\Models\TicketComment;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Database\Seeder;

class TicketSeeder extends Seeder
{
    public function run(): void
    {
        $petugas = User::where('role', 'petugas')->get();
        $skpdUsers = User::where('role', 'skpd')->get();
        $categories = Category::all()->keyBy('name');
        $priorities = Priority::all()->keyBy('name');
        $departments = Department::all();

        if ($skpdUsers->isEmpty() || $petugas->isEmpty()) return;

        $sampleTickets = [
            [
                'title'       => 'Perbaikan Website Resmi SKPD - Error 500',
                'description' => 'Website resmi dinas mengalami error 500 saat diakses dari luar jaringan. Sudah berlangsung sejak kemarin pagi.',
                'category'    => 'Perbaikan Portal',
                'priority'    => 'Tinggi',
                'status'      => 'selesai',
                'contact_pic' => 'Budi Rahmat - 08123456789',
                'days_ago'    => 14,
                'closed_days' => 11,
            ],
            [
                'title'       => 'Maintenance Server Database Kepegawaian',
                'description' => 'Perlu dilakukan maintenance preventif pada server database kepegawaian. Backup data sebelum dilakukan maintenance.',
                'category'    => 'Maintenance Server',
                'priority'    => 'Sedang',
                'status'      => 'selesai',
                'contact_pic' => 'Dewi Sartika - 08234567890',
                'days_ago'    => 10,
                'closed_days' => 8,
            ],
            [
                'title'       => 'Update Sistem Informasi Kepegawaian (SIMPEG)',
                'description' => 'Modul absensi SIMPEG perlu diperbarui mengikuti kebijakan terbaru. Fitur yang perlu diupdate: format laporan, kalkulasi tunjangan.',
                'category'    => 'PIC Presensi',
                'priority'    => 'Sedang',
                'status'      => 'selesai',
                'contact_pic' => 'Hendra Wijaya - 08345678901',
                'days_ago'    => 20,
                'closed_days' => 15,
            ],
            [
                'title'       => 'Instalasi Software Akuntansi SIMAKDA',
                'description' => 'Perlu instalasi dan konfigurasi SIMAKDA versi terbaru pada 3 unit komputer di bagian keuangan.',
                'category'    => 'Instalasi Software',
                'priority'    => 'Tinggi',
                'status'      => 'diproses',
                'contact_pic' => 'Rina Sari - 08456789012',
                'days_ago'    => 3,
                'closed_days' => null,
            ],
            [
                'title'       => 'Jaringan Kantor Offline - Semua Komputer Tidak Bisa Internet',
                'description' => 'Sejak pagi jam 08.00 seluruh komputer di kantor tidak bisa mengakses internet. Sudah coba restart router tapi tidak berhasil.',
                'category'    => 'Troubleshooting',
                'priority'    => 'Urgent',
                'status'      => 'diproses',
                'contact_pic' => 'Agus Salim - 08567890123',
                'days_ago'    => 1,
                'closed_days' => null,
            ],
            [
                'title'       => 'Integrasi Database Presensi dengan Sistem Pusat',
                'description' => 'Diperlukan integrasi data presensi elektronik dengan sistem BKN pusat untuk pelaporan bulanan secara otomatis.',
                'category'    => 'PIC Presensi',
                'priority'    => 'Sedang',
                'status'      => 'baru',
                'contact_pic' => 'Sri Wahyuni - 08678901234',
                'days_ago'    => 0,
                'closed_days' => null,
            ],
            [
                'title'       => 'Portal PPID Tidak Bisa Upload Dokumen',
                'description' => 'Fitur upload dokumen di portal PPID mengalami error: "File too large" padahal ukuran file hanya 2 MB.',
                'category'    => 'Perbaikan Portal',
                'priority'    => 'Tinggi',
                'status'      => 'baru',
                'contact_pic' => 'Fajar Nugroho - 08789012345',
                'days_ago'    => 0,
                'closed_days' => null,
            ],
            [
                'title'       => 'Pelatihan Penggunaan Aplikasi e-Office',
                'description' => 'Mohon diagendakan pelatihan penggunaan aplikasi e-Office untuk 15 orang staf yang belum familiar dengan sistem.',
                'category'    => 'Pelatihan TI',
                'priority'    => 'Rendah',
                'status'      => 'baru',
                'contact_pic' => 'Mira Andika - 08890123456',
                'days_ago'    => 0,
                'closed_days' => null,
            ],
        ];

        foreach ($sampleTickets as $index => $data) {
            $skpdUser = $skpdUsers->get($index % $skpdUsers->count());
            $category = $categories->get($data['category']) ?? $categories->first();
            $priority = $priorities->get($data['priority']) ?? $priorities->first();

            $createdAt = Carbon::now()->subDays($data['days_ago']);
            $prefix    = $createdAt->format('Y-m');

            // Generate unique number
            $existing = Ticket::where('number', 'like', $prefix . '-%')->count();
            $number   = $prefix . '-' . str_pad($existing + 1, 4, '0', STR_PAD_LEFT);

            $assignee   = null;
            $assignedAt = null;
            $startedAt  = null;
            $closedAt   = null;

            if (in_array($data['status'], ['diproses', 'selesai'])) {
                $assignee   = $petugas->get($index % $petugas->count());
                $assignedAt = $createdAt->copy()->addHours(2);
                $startedAt  = $assignedAt->copy()->addHours(1);
            }
            if ($data['status'] === 'selesai' && $data['closed_days']) {
                $closedAt = $createdAt->copy()->addDays($data['days_ago'] - ($data['closed_days'] ?? 0));
            }

            $ticket = Ticket::create([
                'number'        => $number,
                'title'         => $data['title'],
                'description'   => $data['description'],
                'requester_id'  => $skpdUser->id,
                'department_id' => $skpdUser->department_id,
                'category_id'   => $category->id,
                'priority_id'   => $priority->id,
                'assignee_id'   => $assignee?->id,
                'status'        => $data['status'],
                'contact_pic'   => $data['contact_pic'],
                'assigned_at'   => $assignedAt,
                'started_at'    => $startedAt,
                'closed_at'     => $closedAt,
                'summary'       => $data['status'] === 'selesai' ? 'Pekerjaan telah diselesaikan dengan baik. Sistem kembali berfungsi normal.' : null,
                'created_at'    => $createdAt,
                'updated_at'    => $createdAt,
            ]);

            // Tambah komentar untuk tiket yang sudah diproses/selesai
            if ($assignee && $data['status'] !== 'baru') {
                TicketComment::create([
                    'ticket_id'  => $ticket->id,
                    'user_id'    => $assignee->id,
                    'body'       => 'Tiket telah diterima dan sedang dalam proses penanganan.',
                    'type'       => 'status_change',
                    'created_at' => $assignedAt,
                    'updated_at' => $assignedAt,
                ]);
            }
            if ($data['status'] === 'selesai') {
                TicketComment::create([
                    'ticket_id'  => $ticket->id,
                    'user_id'    => $assignee->id,
                    'body'       => 'Pekerjaan telah selesai dilaksanakan. Silakan konfirmasi jika masih ada kendala.',
                    'type'       => 'status_change',
                    'created_at' => $closedAt ?? $createdAt->copy()->addDays(2),
                    'updated_at' => $closedAt ?? $createdAt->copy()->addDays(2),
                ]);
            }
        }
    }
}
