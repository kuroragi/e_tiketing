<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Carbon\Carbon;

class AdminPageController extends Controller
{
    /**
     * Dashboard Administrasi
     */
    public function dashboard()
    {
        $stats = [
            [
                'label' => 'Total Pengguna',
                'nilai' => 45,
                'icon' => 'bi-people',
                'color' => 'primary',
                'change' => '+5 minggu ini'
            ],
            [
                'label' => 'Total SKPD',
                'nilai' => 10,
                'icon' => 'bi-building',
                'color' => 'info',
                'change' => 'Stabil'
            ],
            [
                'label' => 'Total Tiket',
                'nilai' => 156,
                'icon' => 'bi-ticket',
                'color' => 'success',
                'change' => '+12 minggu ini'
            ],
            [
                'label' => 'Tiket Pending',
                'nilai' => 8,
                'icon' => 'bi-hourglass-bottom',
                'color' => 'warning',
                'change' => 'Urgent'
            ]
        ];

        $recentActivities = [
            [
                'user' => 'Ahmad Fauzi',
                'action' => 'Membuat tiket baru',
                'target' => 'TKT-2026-001',
                'waktu' => '2 jam lalu',
                'icon' => 'bi-plus-circle',
                'color' => 'success'
            ],
            [
                'user' => 'Siti Aminah',
                'action' => 'Update status tiket',
                'target' => 'TKT-2026-002',
                'waktu' => '4 jam lalu',
                'icon' => 'bi-pencil',
                'color' => 'info'
            ],
            [
                'user' => 'Rizki Pratama',
                'action' => 'Menyelesaikan tiket',
                'target' => 'TKT-2026-003',
                'waktu' => '1 hari lalu',
                'icon' => 'bi-check-circle',
                'color' => 'success'
            ],
            [
                'user' => 'Admin',
                'action' => 'Menambah pengguna baru',
                'target' => 'Dudi Santoso',
                'waktu' => '2 hari lalu',
                'icon' => 'bi-person-plus',
                'color' => 'primary'
            ]
        ];

        return view('pages.admin.dashboard', compact('stats', 'recentActivities'));
    }

    /**
     * Halaman Manajemen Pengguna
     */
    public function pengguna()
    {
        $users = [
            [
                'id' => 1,
                'nama' => 'Ahmad Fauzi',
                'email' => 'ahmad.fauzi@kominfo.go.id',
                'username' => 'ahmad.fauzi',
                'role' => 'Petugas Kominfo',
                'skpd' => 'Kominfo',
                'status' => 'aktif',
                'terdaftar' => '2025-01-15'
            ],
            [
                'id' => 2,
                'nama' => 'Siti Aminah',
                'email' => 'siti.aminah@kominfo.go.id',
                'username' => 'siti.aminah',
                'role' => 'Petugas Kominfo',
                'skpd' => 'Kominfo',
                'status' => 'aktif',
                'terdaftar' => '2025-02-10'
            ],
            [
                'id' => 3,
                'nama' => 'Dr. Siti Rahma',
                'email' => 'siti.rahma@pendidikan.go.id',
                'username' => 'dr.siti.rahma',
                'role' => 'Pengguna SKPD',
                'skpd' => 'Dinas Pendidikan',
                'status' => 'aktif',
                'terdaftar' => '2025-01-20'
            ],
            [
                'id' => 4,
                'nama' => 'dr. Ahmad Yani',
                'email' => 'ahmad.yani@kesehatan.go.id',
                'username' => 'dr.ahmad.yani',
                'role' => 'Pengguna SKPD',
                'skpd' => 'Dinas Kesehatan',
                'status' => 'aktif',
                'terdaftar' => '2025-02-05'
            ]
        ];

        $roles = [
            ['id' => 1, 'nama' => 'Administrator', 'deskripsi' => 'Akses penuh ke sistem'],
            ['id' => 2, 'nama' => 'Pimpinan', 'deskripsi' => 'Akses ke laporan dan monitoring'],
            ['id' => 3, 'nama' => 'Petugas Kominfo', 'deskripsi' => 'Kelola tiket pekerjaan'],
            ['id' => 4, 'nama' => 'Pengguna SKPD', 'deskripsi' => 'Ajukan tiket pekerjaan']
        ];

        return view('pages.admin.pengguna', compact('users', 'roles'));
    }

    /**
     * Halaman Manajemen SKPD
     */
    public function skpd()
    {
        $skpdList = [
            [
                'id' => 1,
                'nama' => 'Dinas Pendidikan dan Kebudayaan',
                'singkatan' => 'Disdik',
                'alamat' => 'Jl. Pendidikan No. 1',
                'kontak' => '(0752) 12345',
                'email' => 'disdik@bukittinggi.go.id',
                'pimpinan' => 'Dr. Siti Rahma',
                'total_tiket' => 45,
                'status' => 'aktif'
            ],
            [
                'id' => 2,
                'nama' => 'Dinas Kesehatan',
                'singkatan' => 'Dinkes',
                'alamat' => 'Jl. Kesehatan No. 2',
                'kontak' => '(0752) 23456',
                'email' => 'dinkes@bukittinggi.go.id',
                'pimpinan' => 'dr. Ahmad Yani',
                'total_tiket' => 38,
                'status' => 'aktif'
            ],
            [
                'id' => 3,
                'nama' => 'Dinas Pekerjaan Umum dan Penataan Ruang',
                'singkatan' => 'DPUPR',
                'alamat' => 'Jl. PU No. 3',
                'kontak' => '(0752) 34567',
                'email' => 'dpupr@bukittinggi.go.id',
                'pimpinan' => 'Ir. Joni Iskandar',
                'total_tiket' => 23,
                'status' => 'aktif'
            ],
            [
                'id' => 4,
                'nama' => 'Dinas Keuangan dan Aset Daerah',
                'singkatan' => 'DKAD',
                'alamat' => 'Jl. Keuangan No. 4',
                'kontak' => '(0752) 45678',
                'email' => 'dkad@bukittinggi.go.id',
                'pimpinan' => 'Ir. Dewi Sartika',
                'total_tiket' => 28,
                'status' => 'aktif'
            ]
        ];

        return view('pages.admin.skpd', compact('skpdList'));
    }

    /**
     * Halaman Manajemen Jenis Pekerjaan
     */
    public function jenisPekerjaan()
    {
        $jenisList = [
            [
                'id' => 1,
                'nama' => 'Perbaikan Website',
                'kode' => 'WEB',
                'deskripsi' => 'Perbaikan dan update website resmi SKPD',
                'estimasi_hari' => '1-3',
                'total_tiket' => 45,
                'total_selesai' => 42,
                'status' => 'aktif'
            ],
            [
                'id' => 2,
                'nama' => 'Maintenance Server/Database',
                'kode' => 'SRV',
                'deskripsi' => 'Maintenance dan monitoring server serta database',
                'estimasi_hari' => '1-2',
                'total_tiket' => 35,
                'total_selesai' => 32,
                'status' => 'aktif'
            ],
            [
                'id' => 3,
                'nama' => 'Pengembangan Aplikasi',
                'kode' => 'APP',
                'deskripsi' => 'Pengembangan dan update aplikasi bisnis',
                'estimasi_hari' => '5-10',
                'total_tiket' => 28,
                'total_selesai' => 24,
                'status' => 'aktif'
            ],
            [
                'id' => 4,
                'nama' => 'Perbaikan Jaringan/Internet',
                'kode' => 'NET',
                'deskripsi' => 'Troubleshooting dan perbaikan jaringan',
                'estimasi_hari' => '1',
                'total_tiket' => 25,
                'total_selesai' => 23,
                'status' => 'aktif'
            ],
            [
                'id' => 5,
                'nama' => 'Instalasi Software',
                'kode' => 'SOFT',
                'deskripsi' => 'Instalasi dan konfigurasi software',
                'estimasi_hari' => '1-2',
                'total_tiket' => 18,
                'total_selesai' => 18,
                'status' => 'aktif'
            ]
        ];

        return view('pages.admin.jenis-pekerjaan', compact('jenisList'));
    }

    /**
     * Halaman Pengaturan Sistem
     */
    public function pengaturan()
    {
        $settings = [
            'nama_sistem' => 'Sistem Ticketing Layanan Kominfo Kota Bukittinggi',
            'versi' => '1.0',
            'url_sistem' => 'http://e-ticketing.bukittinggi.go.id',
            'nama_organisasi' => 'Dinas Komunikasi dan Informatika Kota Bukittinggi',
            'email_admin' => 'admin@kominfo.bukittinggi.go.id',
            'telepon' => '(0752) 123-4567',
            'alamat' => 'Jl. Panglima Nyak Arief No. 45, Bukittinggi',
            'jam_operasional' => 'Senin - Jumat, 08:00 - 17:00 WIB'
        ];

        $emailSettings = [
            'mail_driver' => 'smtp',
            'mail_host' => 'smtp.gmail.com',
            'mail_port' => '587',
            'mail_encryption' => 'tls',
            'mail_from_name' => 'Sistem E-Ticketing Kominfo'
        ];

        $systemSettings = [
            [
                'kategori' => 'Umum',
                'settings' => [
                    ['key' => 'maintenance_mode', 'label' => 'Mode Maintenance', 'value' => false, 'type' => 'boolean'],
                    ['key' => 'allow_registration', 'label' => 'Izinkan Registrasi Pengguna Baru', 'value' => true, 'type' => 'boolean']
                ]
            ],
            [
                'kategori' => 'Tiket',
                'settings' => [
                    ['key' => 'auto_close_days', 'label' => 'Hari untuk Auto-Close Tiket', 'value' => '30', 'type' => 'number'],
                    ['key' => 'reminder_days', 'label' => 'Hari Pengingat Tiket Pending', 'value' => '7', 'type' => 'number'],
                    ['key' => 'sla_urgent', 'label' => 'SLA Urgent (jam)', 'value' => '24', 'type' => 'number'],
                    ['key' => 'sla_tinggi', 'label' => 'SLA Tinggi (jam)', 'value' => '48', 'type' => 'number']
                ]
            ],
            [
                'kategori' => 'Notifikasi',
                'settings' => [
                    ['key' => 'notif_email', 'label' => 'Notifikasi Email', 'value' => true, 'type' => 'boolean'],
                    ['key' => 'notif_sms', 'label' => 'Notifikasi SMS', 'value' => false, 'type' => 'boolean']
                ]
            ]
        ];

        return view('pages.admin.pengaturan', compact('settings', 'emailSettings', 'systemSettings'));
    }

    /**
     * Halaman Log Aktivitas
     */
    public function logAktivitas()
    {
        $logs = [
            [
                'id' => 1,
                'user' => 'Ahmad Fauzi',
                'action' => 'CREATE_TICKET',
                'action_label' => 'Membuat Tiket Baru',
                'target' => 'TKT-2026-001',
                'target_label' => 'Perbaikan Website',
                'status' => 'success',
                'ip_address' => '192.168.1.100',
                'waktu' => Carbon::now()->subHours(2)
            ],
            [
                'id' => 2,
                'user' => 'Siti Aminah',
                'action' => 'UPDATE_TICKET',
                'action_label' => 'Update Status Tiket',
                'target' => 'TKT-2026-002',
                'target_label' => 'Diproses → Selesai',
                'status' => 'success',
                'ip_address' => '192.168.1.101',
                'waktu' => Carbon::now()->subHours(4)
            ],
            [
                'id' => 3,
                'user' => 'Admin',
                'action' => 'CREATE_USER',
                'action_label' => 'Menambah Pengguna',
                'target' => 'dudi.santoso',
                'target_label' => 'Dudi Santoso',
                'status' => 'success',
                'ip_address' => '192.168.1.102',
                'waktu' => Carbon::now()->subDays(1)
            ],
            [
                'id' => 4,
                'user' => 'Rizki Pratama',
                'action' => 'LOGIN',
                'action_label' => 'Login Sistem',
                'target' => 'N/A',
                'target_label' => 'Login Berhasil',
                'status' => 'success',
                'ip_address' => '192.168.1.103',
                'waktu' => Carbon::now()->subDays(1)
            ],
            [
                'id' => 5,
                'user' => 'Admin',
                'action' => 'DELETE_USER',
                'action_label' => 'Menghapus Pengguna',
                'target' => 'budi.santoso',
                'target_label' => 'Budi Santoso',
                'status' => 'danger',
                'ip_address' => '192.168.1.102',
                'waktu' => Carbon::now()->subDays(2)
            ]
        ];

        $filters = [
            'actions' => ['CREATE_TICKET', 'UPDATE_TICKET', 'CREATE_USER', 'LOGIN', 'DELETE_USER'],
            'statuses' => ['success', 'danger', 'warning']
        ];

        return view('pages.admin.log-aktivitas', compact('logs', 'filters'));
    }

    /**
     * Halaman Laporan Admin
     */
    public function laporan()
    {
        $reportData = [
            'periode' => 'Bulan Januari 2026',
            'total_tiket' => 156,
            'tiket_selesai' => 142,
            'tiket_diproses' => 8,
            'tiket_baru' => 6,
            'persentase_selesai' => 91,
            'rata_waktu_penyelesaian' => 3.2,
            'kepuasan_pengguna' => 4.2
        ];

        $topSkpd = [
            ['skpd' => 'Dinas Pendidikan', 'tiket' => 45, 'selesai' => 42],
            ['skpd' => 'Dinas Kesehatan', 'tiket' => 38, 'selesai' => 35],
            ['skpd' => 'Dinas Keuangan', 'tiket' => 28, 'selesai' => 24],
            ['skpd' => 'Dinas PU', 'tiket' => 23, 'selesai' => 21]
        ];

        $topJenis = [
            ['jenis' => 'Perbaikan Website', 'tiket' => 45],
            ['jenis' => 'Maintenance Server', 'tiket' => 35],
            ['jenis' => 'Update Aplikasi', 'tiket' => 28],
            ['jenis' => 'Perbaikan Jaringan', 'tiket' => 25]
        ];

        return view('pages.admin.laporan', compact('reportData', 'topSkpd', 'topJenis'));
    }
}
