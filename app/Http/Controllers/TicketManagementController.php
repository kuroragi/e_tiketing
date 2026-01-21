<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Carbon\Carbon;

class TicketManagementController extends Controller
{
    /**
     * Halaman utama manajemen tiket
     */
    public function index()
    {
        $pendingTickets = [
            [
                'id' => 'TKT-2026-001',
                'judul' => 'PIC Presensi - Integrasi Database Baru',
                'skpd' => 'Dinas Pendidikan',
                'jenis_pekerjaan' => 'PIC Presensi',
                'prioritas' => 'Tinggi',
                'status' => 'Baru',
                'tanggal_masuk' => Carbon::now()->subHours(2),
                'deskripsi' => 'Integrasi database presensi dengan sistem terbaru'
            ],
            [
                'id' => 'TKT-2026-002',
                'judul' => 'Perbaikan Portal - Login Error',
                'skpd' => 'Dinas Kesehatan',
                'jenis_pekerjaan' => 'Perbaikan Portal',
                'prioritas' => 'Urgent',
                'status' => 'Baru',
                'tanggal_masuk' => Carbon::now()->subHours(5),
                'deskripsi' => 'Portal tidak bisa login, error 500 di halaman login'
            ],
            [
                'id' => 'TKT-2026-003',
                'judul' => 'Troubleshooting - Jaringan Offline',
                'skpd' => 'Dinas Keuangan',
                'jenis_pekerjaan' => 'Troubleshooting',
                'prioritas' => 'Urgent',
                'status' => 'Baru',
                'tanggal_masuk' => Carbon::now()->subDays(1),
                'deskripsi' => 'Jaringan kantor offline, semua komputer tidak bisa internet'
            ],
            [
                'id' => 'TKT-2026-004',
                'judul' => 'PIC Presensi - Data Migration',
                'skpd' => 'BKD',
                'jenis_pekerjaan' => 'PIC Presensi',
                'prioritas' => 'Sedang',
                'status' => 'Baru',
                'tanggal_masuk' => Carbon::now()->subDays(1),
                'deskripsi' => 'Migrasi data presensi dari sistem lama ke sistem baru'
            ]
        ];

        return view('pages.admin.manajemen-tiket.index', compact('pendingTickets'));
    }

    /**
     * Halaman Assignment Otomatis
     */
    public function autoAssignment()
    {
        $assignmentRules = [
            [
                'jenis_pekerjaan' => 'PIC Presensi',
                'rules' => [
                    ['kondisi' => 'Prioritas Urgent', 'petugas' => 'Ahmad Fauzi (A)', 'persentase' => 70],
                    ['kondisi' => 'Prioritas Sedang', 'petugas' => 'Siti Aminah (B)', 'persentase' => 30]
                ]
            ],
            [
                'jenis_pekerjaan' => 'Perbaikan Portal',
                'rules' => [
                    ['kondisi' => 'Prioritas Urgent', 'petugas' => 'Siti Aminah (B)', 'persentase' => 60],
                    ['kondisi' => 'Prioritas Tinggi', 'petugas' => 'Rizki Pratama (C)', 'persentase' => 40]
                ]
            ],
            [
                'jenis_pekerjaan' => 'Troubleshooting',
                'rules' => [
                    ['kondisi' => 'Prioritas Urgent', 'petugas' => 'Rizki Pratama (C)', 'persentase' => 50],
                    ['kondisi' => 'Prioritas Urgent', 'petugas' => 'Desi Marlina (D)', 'persentase' => 50]
                ]
            ],
            [
                'jenis_pekerjaan' => 'Maintenance Server',
                'rules' => [
                    ['kondisi' => 'Semua Prioritas', 'petugas' => 'Budi Santoso (E)', 'persentase' => 100]
                ]
            ]
        ];

        $petugasList = [
            ['id' => 1, 'nama' => 'Ahmad Fauzi', 'kode' => 'A', 'keahlian' => ['PIC Presensi', 'Troubleshooting']],
            ['id' => 2, 'nama' => 'Siti Aminah', 'kode' => 'B', 'keahlian' => ['PIC Presensi', 'Perbaikan Portal']],
            ['id' => 3, 'nama' => 'Rizki Pratama', 'kode' => 'C', 'keahlian' => ['Perbaikan Portal', 'Troubleshooting']],
            ['id' => 4, 'nama' => 'Desi Marlina', 'kode' => 'D', 'keahlian' => ['Troubleshooting', 'Maintenance Server']],
            ['id' => 5, 'nama' => 'Budi Santoso', 'kode' => 'E', 'keahlian' => ['Maintenance Server', 'PIC Presensi']]
        ];

        $jenisPekerjaanList = [
            'PIC Presensi',
            'Perbaikan Portal',
            'Troubleshooting',
            'Maintenance Server',
            'Update Aplikasi'
        ];

        return view('pages.admin.manajemen-tiket.auto-assignment', compact('assignmentRules', 'petugasList', 'jenisPekerjaanList'));
    }

    /**
     * Halaman Assignment Manual
     */
    public function manualAssignment()
    {
        $pendingTickets = [
            [
                'id' => 'TKT-2026-001',
                'judul' => 'PIC Presensi - Integrasi Database Baru',
                'skpd' => 'Dinas Pendidikan',
                'jenis_pekerjaan' => 'PIC Presensi',
                'prioritas' => 'Tinggi',
                'tanggal_masuk' => Carbon::now()->subHours(2),
                'pemohon' => 'Dr. Siti Rahma',
                'deskripsi' => 'Integrasi database presensi dengan sistem terbaru'
            ],
            [
                'id' => 'TKT-2026-002',
                'judul' => 'Perbaikan Portal - Login Error',
                'skpd' => 'Dinas Kesehatan',
                'jenis_pekerjaan' => 'Perbaikan Portal',
                'prioritas' => 'Urgent',
                'tanggal_masuk' => Carbon::now()->subHours(5),
                'pemohon' => 'dr. Ahmad Yani',
                'deskripsi' => 'Portal tidak bisa login, error 500 di halaman login'
            ]
        ];

        $petugasList = [
            ['id' => 1, 'nama' => 'Ahmad Fauzi', 'skill' => 'PIC Presensi, Troubleshooting', 'load' => 2],
            ['id' => 2, 'nama' => 'Siti Aminah', 'skill' => 'PIC Presensi, Perbaikan Portal', 'load' => 3],
            ['id' => 3, 'nama' => 'Rizki Pratama', 'skill' => 'Perbaikan Portal, Troubleshooting', 'load' => 1],
            ['id' => 4, 'nama' => 'Desi Marlina', 'skill' => 'Troubleshooting, Maintenance Server', 'load' => 2],
            ['id' => 5, 'nama' => 'Budi Santoso', 'skill' => 'Maintenance Server, PIC Presensi', 'load' => 0]
        ];

        return view('pages.admin.manajemen-tiket.manual-assignment', compact('pendingTickets', 'petugasList'));
    }

    /**
     * Halaman History Tiket
     */
    public function history()
    {
        $assignmentHistory = [
            [
                'id' => 'TKT-2026-050',
                'judul' => 'PIC Presensi - Update Data',
                'petugas' => 'Ahmad Fauzi',
                'metode' => 'Otomatis',
                'tanggal' => Carbon::now()->subDays(5),
                'waktu' => 2,
                'status' => 'Selesai'
            ],
            [
                'id' => 'TKT-2026-051',
                'judul' => 'Perbaikan Portal - SQL Error',
                'petugas' => 'Siti Aminah',
                'metode' => 'Manual',
                'tanggal' => Carbon::now()->subDays(4),
                'waktu' => 3,
                'status' => 'Selesai'
            ],
            [
                'id' => 'TKT-2026-052',
                'judul' => 'Troubleshooting - Server Down',
                'petugas' => 'Rizki Pratama',
                'metode' => 'Otomatis',
                'tanggal' => Carbon::now()->subDays(3),
                'waktu' => 1,
                'status' => 'Selesai'
            ],
            [
                'id' => 'TKT-2026-053',
                'judul' => 'Maintenance Server - Database Backup',
                'petugas' => 'Budi Santoso',
                'metode' => 'Manual',
                'tanggal' => Carbon::now()->subDays(2),
                'waktu' => 4,
                'status' => 'Selesai'
            ],
            [
                'id' => 'TKT-2026-054',
                'judul' => 'Update Aplikasi - New Features',
                'petugas' => 'Siti Aminah',
                'metode' => 'Manual',
                'tanggal' => Carbon::now()->subDays(1),
                'waktu' => 2,
                'status' => 'Berlangsung'
            ]
        ];

        $statistics = [
            'total_assignment' => 254,
            'auto_assignment' => 74,
            'manual_assignment' => 26,
            'avg_time' => 2.5
        ];

        $assignmentByPetugas = [
            [
                'nama' => 'Ahmad Fauzi',
                'total' => 45,
                'otomatis' => 35,
                'manual' => 10
            ],
            [
                'nama' => 'Siti Aminah',
                'total' => 52,
                'otomatis' => 38,
                'manual' => 14
            ],
            [
                'nama' => 'Rizki Pratama',
                'total' => 38,
                'otomatis' => 30,
                'manual' => 8
            ],
            [
                'nama' => 'Desi Marlina',
                'total' => 42,
                'otomatis' => 32,
                'manual' => 10
            ],
            [
                'nama' => 'Budi Santoso',
                'total' => 77,
                'otomatis' => 52,
                'manual' => 25
            ]
        ];

        $petugasList = [
            ['id' => 1, 'nama' => 'Ahmad Fauzi'],
            ['id' => 2, 'nama' => 'Siti Aminah'],
            ['id' => 3, 'nama' => 'Rizki Pratama'],
            ['id' => 4, 'nama' => 'Desi Marlina'],
            ['id' => 5, 'nama' => 'Budi Santoso']
        ];

        return view('pages.admin.manajemen-tiket.history', compact('assignmentHistory', 'statistics', 'assignmentByPetugas', 'petugasList'));
    }

    /**
     * Simpan assignment otomatis
     */
    public function saveAutoAssignment(Request $request)
    {
        // Logika simpan konfigurasi assignment otomatis
        return response()->json([
            'success' => true,
            'message' => 'Konfigurasi assignment otomatis berhasil disimpan'
        ]);
    }

    /**
     * Assign tiket manual
     */
    public function assignManual(Request $request)
    {
        // Logika assign tiket ke petugas
        return response()->json([
            'success' => true,
            'message' => 'Tiket berhasil diassign ke petugas: ' . $request->petugas
        ]);
    }
}
