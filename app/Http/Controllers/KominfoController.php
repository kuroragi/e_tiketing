<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Carbon\Carbon;

class KominfoController extends Controller
{
    /**
     * Dashboard - Ringkasan statistik dan aktivitas terbaru
     */
    public function dashboard()
    {
        // Simulate dashboard data
        $stats = [
            'total_tiket' => 156,
            'tiket_baru' => 12,
            'tiket_diproses' => 8,
            'tiket_selesai' => 136,
            'rata_penyelesaian' => 3.2,
            'beban_kerja' => 'Normal'
        ];

        $recentTickets = [
            [
                'id' => 'TKT-2024-001',
                'judul' => 'Perbaikan Website Resmi SKPD',
                'skpd' => 'Dinas Pendidikan',
                'prioritas' => 'Tinggi',
                'status' => 'Baru',
                'tanggal' => Carbon::now()->subHours(2)->format('d M Y H:i'),
                'petugas' => null
            ],
            [
                'id' => 'TKT-2024-002',
                'judul' => 'Maintenance Server Database',
                'skpd' => 'Dinas Kesehatan',
                'prioritas' => 'Sedang',
                'status' => 'Diproses',
                'tanggal' => Carbon::now()->subHours(5)->format('d M Y H:i'),
                'petugas' => 'Ahmad Fauzi'
            ],
            [
                'id' => 'TKT-2024-003',
                'judul' => 'Update Sistem Informasi Kepegawaian',
                'skpd' => 'Badan Kepegawaian Daerah',
                'prioritas' => 'Rendah',
                'status' => 'Selesai',
                'tanggal' => Carbon::now()->subDays(1)->format('d M Y H:i'),
                'petugas' => 'Siti Aminah'
            ],
            [
                'id' => 'TKT-2024-004',
                'judul' => 'Instalasi Software Akuntansi',
                'skpd' => 'Dinas Keuangan',
                'prioritas' => 'Tinggi',
                'status' => 'Diproses',
                'tanggal' => Carbon::now()->subDays(2)->format('d M Y H:i'),
                'petugas' => 'Rizki Pratama'
            ]
        ];

        $quickActions = [
            [
                'icon' => 'plus-circle',
                'title' => 'Tiket Baru',
                'description' => 'Buat tiket baru',
                'url' => route('tiket.create'),
                'color' => 'primary'
            ],
            [
                'icon' => 'list-task',
                'title' => 'Kelola Tiket',
                'description' => 'Kelola semua tiket',
                'url' => route('tiket.index'),
                'color' => 'info'
            ],
            [
                'icon' => 'bar-chart',
                'title' => 'Laporan',
                'description' => 'Lihat laporan',
                'url' => route('laporan.index'),
                'color' => 'success'
            ]
        ];

        return view('kominfo.dashboard', compact('stats', 'recentTickets', 'quickActions'));
    }

    /**
     * Form pengajuan tiket baru oleh SKPD
     */
    public function create()
    {
        $skpdList = $this->getSkpdList();
        $jenisKerjaan = $this->getJenisKerjaan();
        $prioritasList = $this->getPrioritas();

        return view('kominfo.tiket-pengajuan', compact('skpdList', 'jenisKerjaan', 'prioritasList'));
    }

    /**
     * Simpan tiket baru
     */
    public function store(Request $request)
    {
        $request->validate([
            'nama_pemohon' => 'required|string|max:255',
            'nip' => 'required|string|max:20',
            'jabatan' => 'required|string|max:255',
            'skpd_id' => 'required',
            'no_hp' => 'required|string|max:15',
            'email' => 'required|email',
            'judul_pekerjaan' => 'required|string|max:255',
            'jenis_pekerjaan' => 'required',
            'deskripsi' => 'required|string',
            'prioritas' => 'required|in:Rendah,Sedang,Tinggi,Urgent',
            'target_selesai' => 'required|date|after:today',
            'lampiran.*' => 'nullable|file|mimes:pdf,doc,docx,jpg,jpeg,png|max:2048',
            'persetujuan' => 'required|accepted'
        ]);

        // Simulate saving ticket
        $ticketId = 'TKT-' . date('Y') . '-' . str_pad(rand(1, 999), 3, '0', STR_PAD_LEFT);

        // In real implementation, save to database
        // Ticket::create($request->all());

        return redirect()->route('dashboard')
            ->with('success', "Tiket berhasil diajukan dengan ID: {$ticketId}. Tim Kominfo akan segera menindaklanjuti.");
    }

    /**
     * Daftar tiket untuk staff Kominfo
     */
    public function index(Request $request)
    {
        $filters = [
            'status' => '',
            'prioritas' => '',
            'skpd' => '',
            'petugas' => '',
            'periode' => '',
            'search' => '',
            'status' => '',
        ];

        $tickets = $this->getFilteredTickets($filters);
        $skpdList = $this->getSkpdList();
        $petugasList = $this->getPetugasList();

        $stats = [
            'total' => count($tickets),
            'baru' => count(array_filter($tickets, fn($t) => $t['status'] === 'Baru')),
            'diproses' => count(array_filter($tickets, fn($t) => $t['status'] === 'Diproses')),
            'selesai' => count(array_filter($tickets, fn($t) => $t['status'] === 'Selesai'))
        ];

        return view('kominfo.tiket-daftar', compact('tickets', 'filters', 'skpdList', 'petugasList', 'stats'));
    }

    /**
     * Detail tiket
     */
    public function show($id)
    {
        $ticket = $this->getTicketById($id);
        
        if (!$ticket) {
            abort(404, 'Tiket tidak ditemukan');
        }

        return response()->json($ticket);
    }

    /**
     * Update status tiket
     */
    public function updateStatus(Request $request, $id)
    {
        $request->validate([
            'status' => 'required|in:Baru,Diproses,Selesai,Ditolak',
            'catatan' => 'nullable|string',
            'petugas_id' => 'nullable'
        ]);

        // Simulate status update
        // In real implementation: Ticket::find($id)->update($request->all());

        $statusMessage = [
            'Baru' => 'Tiket dikembalikan ke status baru',
            'Diproses' => 'Tiket sedang diproses',
            'Selesai' => 'Tiket telah diselesaikan',
            'Ditolak' => 'Tiket ditolak'
        ];

        return response()->json([
            'success' => true,
            'message' => $statusMessage[$request->status]
        ]);
    }

    /**
     * Laporan untuk pimpinan
     */
    public function laporan(Request $request)
    {
        $periode = $request->get('periode', 'bulan_ini');
        $skpdFilter = $request->get('skpd_filter');
        
        // Simulate report data
        $summary = [
            'total_tiket' => 156,
            'tiket_selesai' => 142,
            'persentase_selesai' => 91,
            'rata_waktu' => 3.2,
            'beban_kerja' => 15
        ];

        $statusDistribution = [
            'selesai' => 142,
            'diproses' => 8,
            'baru' => 6,
            'ditolak' => 0
        ];

        $skpdReport = [
            ['nama' => 'Dinas Pendidikan', 'total' => 45, 'selesai' => 42, 'persentase' => 93],
            ['nama' => 'Dinas Kesehatan', 'total' => 38, 'selesai' => 35, 'persentase' => 92],
            ['nama' => 'Dinas Keuangan', 'total' => 28, 'selesai' => 24, 'persentase' => 86],
            ['nama' => 'BKD', 'total' => 22, 'selesai' => 20, 'persentase' => 91],
            ['nama' => 'Dinas PU', 'total' => 23, 'selesai' => 21, 'persentase' => 91]
        ];

        $jenisReport = [
            ['nama' => 'Perbaikan Website', 'jumlah' => 45, 'persentase' => 29],
            ['nama' => 'Maintenance Server', 'jumlah' => 35, 'persentase' => 22],
            ['nama' => 'Update Sistem', 'jumlah' => 28, 'persentase' => 18],
            ['nama' => 'Instalasi Software', 'jumlah' => 25, 'persentase' => 16],
            ['nama' => 'Troubleshooting', 'jumlah' => 23, 'persentase' => 15]
        ];

        $kpi = [
            'response_time' => 85,
            'avg_response' => 2,
            'completion_rate' => 91,
            'satisfaction' => 4.2,
            'workload' => 15
        ];

        $skpdList = $this->getSkpdList();

        return view('kominfo.laporan', compact(
            'summary', 
            'statusDistribution', 
            'skpdReport', 
            'jenisReport', 
            'kpi', 
            'skpdList'
        ));
    }

    /**
     * Helper: Get SKPD list
     */
    private function getSkpdList()
    {
        return [
            ['id' => 1, 'nama' => 'Dinas Pendidikan dan Kebudayaan'],
            ['id' => 2, 'nama' => 'Dinas Kesehatan'],
            ['id' => 3, 'nama' => 'Dinas Pekerjaan Umum dan Penataan Ruang'],
            ['id' => 4, 'nama' => 'Dinas Keuangan dan Aset Daerah'],
            ['id' => 5, 'nama' => 'Badan Kepegawaian Daerah'],
            ['id' => 6, 'nama' => 'Dinas Sosial'],
            ['id' => 7, 'nama' => 'Dinas Koperasi dan UKM'],
            ['id' => 8, 'nama' => 'Dinas Pariwisata dan Kebudayaan'],
            ['id' => 9, 'nama' => 'Sekretariat Daerah'],
            ['id' => 10, 'nama' => 'DPRD Kota Bukittinggi']
        ];
    }

    /**
     * Helper: Get jenis pekerjaan list
     */
    private function getJenisKerjaan()
    {
        return [
            'website' => 'Perbaikan/Update Website',
            'server' => 'Maintenance Server/Database',
            'aplikasi' => 'Pengembangan/Update Aplikasi',
            'jaringan' => 'Perbaikan Jaringan/Internet',
            'hardware' => 'Perbaikan Hardware/Komputer',
            'software' => 'Instalasi/Update Software',
            'backup' => 'Backup/Recovery Data',
            'keamanan' => 'Keamanan Sistem/Cyber Security',
            'pelatihan' => 'Pelatihan IT/Sistem',
            'lainnya' => 'Lainnya'
        ];
    }

    /**
     * Helper: Get prioritas list
     */
    private function getPrioritas()
    {
        return [
            'Rendah' => 'Rendah - Tidak mendesak, bisa ditunda',
            'Sedang' => 'Sedang - Perlu ditangani dalam 1 minggu',
            'Tinggi' => 'Tinggi - Perlu segera ditangani (1-2 hari)',
            'Urgent' => 'Urgent - Sangat mendesak (hari ini juga)'
        ];
    }

    /**
     * Helper: Get petugas list
     */
    private function getPetugasList()
    {
        return [
            ['id' => 1, 'nama' => 'Ahmad Fauzi'],
            ['id' => 2, 'nama' => 'Siti Aminah'],
            ['id' => 3, 'nama' => 'Rizki Pratama'],
            ['id' => 4, 'nama' => 'Desi Marlina'],
            ['id' => 5, 'nama' => 'Budi Santoso']
        ];
    }

    /**
     * Helper: Get filtered tickets
     */
    private function getFilteredTickets($filters)
    {
        // Simulate ticket data
        $allTickets = [
            [
                'id' => 'TKT-2024-001',
                'judul' => 'Perbaikan Website Resmi SKPD',
                'skpd' => 'Dinas Pendidikan',
                'skpd_id' => 1,
                'jenis' => 'Website',
                'prioritas' => 'Tinggi',
                'status' => 'Baru',
                'tanggal' => Carbon::now()->subHours(2),
                'target' => Carbon::now()->addDays(3),
                'petugas' => null,
                'petugas_id' => null,
                'pemohon' => 'Dr. Siti Rahma',
                'kontak' => '0812-3456-7890',
                'deskripsi' => 'Website resmi dinas tidak dapat diakses sejak kemarin. Perlu perbaikan segera karena ada pengumuman penting.'
            ],
            [
                'id' => 'TKT-2024-002',
                'judul' => 'Maintenance Server Database',
                'skpd' => 'Dinas Kesehatan',
                'skpd_id' => 2,
                'jenis' => 'Server',
                'prioritas' => 'Sedang',
                'status' => 'Diproses',
                'tanggal' => Carbon::now()->subHours(5),
                'target' => Carbon::now()->addDays(5),
                'petugas' => 'Ahmad Fauzi',
                'petugas_id' => 1,
                'pemohon' => 'dr. Ahmad Yani',
                'kontak' => '0813-4567-8901',
                'deskripsi' => 'Server database sistem informasi kesehatan mengalami perlambatan. Perlu maintenance rutin.'
            ],
            [
                'id' => 'TKT-2024-003',
                'judul' => 'Update Sistem Informasi Kepegawaian',
                'skpd' => 'Badan Kepegawaian Daerah',
                'skpd_id' => 5,
                'jenis' => 'Aplikasi',
                'prioritas' => 'Rendah',
                'status' => 'Selesai',
                'tanggal' => Carbon::now()->subDays(1),
                'target' => Carbon::now()->addDays(7),
                'petugas' => 'Siti Aminah',
                'petugas_id' => 2,
                'pemohon' => 'Drs. Bambang Sutrisno',
                'kontak' => '0814-5678-9012',
                'deskripsi' => 'Sistem SIMPEG perlu update fitur baru untuk pelaporan absensi online.'
            ],
            [
                'id' => 'TKT-2024-004',
                'judul' => 'Instalasi Software Akuntansi',
                'skpd' => 'Dinas Keuangan',
                'skpd_id' => 4,
                'jenis' => 'Software',
                'prioritas' => 'Tinggi',
                'status' => 'Diproses',
                'tanggal' => Carbon::now()->subDays(2),
                'target' => Carbon::now()->addDays(2),
                'petugas' => 'Rizki Pratama',
                'petugas_id' => 3,
                'pemohon' => 'Ir. Dewi Sartika',
                'kontak' => '0815-6789-0123',
                'deskripsi' => 'Instalasi software akuntansi baru untuk menggantikan sistem lama yang sudah tidak support.'
            ],
            [
                'id' => 'TKT-2024-005',
                'judul' => 'Perbaikan Jaringan Internet Kantor',
                'skpd' => 'Dinas PU',
                'skpd_id' => 3,
                'jenis' => 'Jaringan',
                'prioritas' => 'Urgent',
                'status' => 'Baru',
                'tanggal' => Carbon::now()->subMinutes(30),
                'target' => Carbon::now()->addHours(6),
                'petugas' => null,
                'petugas_id' => null,
                'pemohon' => 'Ir. Joni Iskandar',
                'kontak' => '0816-7890-1234',
                'deskripsi' => 'Jaringan internet kantor mati total. Sangat mengganggu pekerjaan harian.'
            ]
        ];

        // Apply filters
        $filtered = $allTickets;

        if ($filters['status']) {
            $filtered = array_filter($filtered, fn($t) => $t['status'] === $filters['status']);
        }

        if ($filters['prioritas']) {
            $filtered = array_filter($filtered, fn($t) => $t['prioritas'] === $filters['prioritas']);
        }

        if ($filters['skpd']) {
            $filtered = array_filter($filtered, fn($t) => $t['skpd_id'] == $filters['skpd']);
        }

        if ($filters['petugas']) {
            $filtered = array_filter($filtered, fn($t) => $t['petugas_id'] == $filters['petugas']);
        }

        if ($filters['search']) {
            $search = strtolower($filters['search']);
            $filtered = array_filter($filtered, function($t) use ($search) {
                return strpos(strtolower($t['judul']), $search) !== false ||
                       strpos(strtolower($t['skpd']), $search) !== false ||
                       strpos(strtolower($t['id']), $search) !== false;
            });
        }

        return array_values($filtered);
    }

    /**
     * Helper: Get ticket by ID
     */
    private function getTicketById($id)
    {
        $tickets = $this->getFilteredTickets([]);
        return array_first($tickets, fn($t) => $t['id'] === $id);
    }
}