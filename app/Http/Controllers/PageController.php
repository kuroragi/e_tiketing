<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class PageController extends Controller
{
    /**
     * Halaman Panduan Penggunaan Sistem
     */
    public function panduan()
    {
        $sections = [
            [
                'id' => 'skpd-login',
                'title' => 'Cara Login sebagai SKPD',
                'content' => 'Pengguna SKPD dapat login menggunakan akun yang telah terdaftar di sistem. Gunakan username dan password yang diberikan oleh administrator.',
                'icon' => 'bi-box-arrow-in-right'
            ],
            [
                'id' => 'buat-tiket',
                'title' => 'Cara Membuat Tiket Pekerjaan',
                'content' => 'Untuk membuat tiket pekerjaan baru: 1) Login terlebih dahulu, 2) Klik menu "Pengajuan Tiket", 3) Isi formulir dengan informasi lengkap, 4) Pilih jenis pekerjaan dan prioritas, 5) Upload lampiran jika ada, 6) Klik tombol "Ajukan Tiket". Tiket akan segera diproses oleh tim Kominfo.',
                'icon' => 'bi-file-earmark-plus'
            ],
            [
                'id' => 'cek-status',
                'title' => 'Cara Mengecek Status Tiket',
                'content' => 'Untuk mengecek status tiket: 1) Login ke sistem, 2) Klik menu "Tiket Saya", 3) Lihat daftar tiket yang telah diajukan, 4) Klik detail tiket untuk melihat informasi lebih lengkap termasuk catatan dari tim Kominfo.',
                'icon' => 'bi-search'
            ],
            [
                'id' => 'update-profil',
                'title' => 'Cara Update Profil Pengguna',
                'content' => 'Untuk mengupdate profil: 1) Klik ikon profil di pojok atas kanan, 2) Pilih "Pengaturan Profil", 3) Edit informasi yang ingin diubah, 4) Klik tombol "Simpan". Perubahan akan tersimpan secara otomatis.',
                'icon' => 'bi-person-circle'
            ]
        ];

        $faqItems = [
            [
                'pertanyaan' => 'Berapa lama waktu penyelesaian pekerjaan?',
                'jawaban' => 'Waktu penyelesaian tergantung dari jenis pekerjaan dan prioritas yang ditetapkan. Untuk prioritas Urgent, kami target penyelesaian dalam 1 hari. Prioritas Tinggi: 2-3 hari. Prioritas Sedang: 1 minggu. Prioritas Rendah: hingga 2 minggu.'
            ],
            [
                'pertanyaan' => 'Bagaimana jika pekerjaan tidak sesuai harapan?',
                'jawaban' => 'Silakan hubungi tim Kominfo melalui menu "Hubungi Kami" atau reply pada tiket pekerjaan. Tim kami siap membantu dan melakukan perbaikan sesuai kebutuhan Anda.'
            ],
            [
                'pertanyaan' => 'Apa saja jenis pekerjaan yang ditangani Kominfo?',
                'jawaban' => 'Kominfo menangani berbagai jenis pekerjaan IT seperti: Perbaikan/Update Website, Maintenance Server/Database, Pengembangan/Update Aplikasi, Perbaikan Jaringan/Internet, Perbaikan Hardware/Komputer, Instalasi/Update Software, Backup/Recovery Data, dan Pelatihan IT.'
            ],
            [
                'pertanyaan' => 'Bisakah lupa password?',
                'jawaban' => 'Jika lupa password, klik tombol "Lupa Password" di halaman login, kemudian masukkan email Anda. Anda akan menerima link reset password melalui email dalam waktu beberapa menit.'
            ],
            [
                'pertanyaan' => 'Bagaimana cara membatalkan tiket?',
                'jawaban' => 'Tiket yang masih berstatus "Baru" dapat dibatalkan melalui menu pengaturan tiket. Untuk tiket yang sudah diproses, silakan hubungi tim Kominfo untuk meminta pembatalan.'
            ]
        ];

        return view('pages.panduan', compact('sections', 'faqItems'));
    }

    /**
     * Halaman Tentang Sistem
     */
    public function tentang()
    {
        $aboutData = [
            'title' => 'Tentang Sistem E-Ticket Kominfo Bukittinggi',
            'description' => 'Sistem Ticketing Layanan Kominfo Kota Bukittinggi adalah platform internal pemerintah yang dirancang untuk memudahkan Satuan Kerja Perangkat Daerah (SKPD) dalam mengajukan permintaan bantuan teknis kepada Dinas Komunikasi dan Informatika (Kominfo).',
            'features' => [
                [
                    'icon' => 'bi-clipboard-check',
                    'title' => 'Pencatatan Terpusat',
                    'description' => 'Semua permintaan pekerjaan tercatat secara resmi dan terdokumentasi dengan baik.'
                ],
                [
                    'icon' => 'bi-graph-up',
                    'title' => 'Monitoring Real-time',
                    'description' => 'Pantau status pekerjaan secara langsung tanpa perlu menunggu laporan tertulis.'
                ],
                [
                    'icon' => 'bi-bar-chart',
                    'title' => 'Laporan Komprehensif',
                    'description' => 'Dapatkan laporan detail tentang beban kerja dan kinerja Kominfo.'
                ],
                [
                    'icon' => 'bi-shield-lock',
                    'title' => 'Aman dan Terpercaya',
                    'description' => 'Sistem menggunakan keamanan tingkat enterprise untuk melindungi data Anda.'
                ],
                [
                    'icon' => 'bi-people',
                    'title' => 'Kolaborasi Lebih Baik',
                    'description' => 'Komunikasi yang lebih efektif antara SKPD dan Kominfo melalui satu platform.'
                ],
                [
                    'icon' => 'bi-lightning',
                    'title' => 'Responsif dan Cepat',
                    'description' => 'Antarmuka yang user-friendly dan proses pengajuan yang sederhana.'
                ]
            ],
            'visi' => 'Menjadi sistem ticketing yang komprehensif, transparan, dan efisien dalam mengelola permintaan pekerjaan di lingkungan Pemerintah Kota Bukittinggi.',
            'misi' => [
                'Menyediakan platform resmi untuk pencatatan permintaan bantuan teknis dari SKPD kepada Kominfo.',
                'Memastikan setiap permintaan diproses dengan transparan dan dapat dipantau progresnya.',
                'Menghasilkan laporan beban kerja yang akurat untuk evaluasi kinerja dan pengambilan keputusan.',
                'Meningkatkan kualitas pelayanan pemerintahan berbasis teknologi digital.'
            ]
        ];

        $teams = [
            [
                'nama' => 'Dinas Komunikasi dan Informatika',
                'peran' => 'Pengelola Sistem',
                'deskripsi' => 'Tim yang bertanggung jawab dalam pengembangan, pemeliharaan, dan peningkatan sistem ticketing.'
            ],
            [
                'nama' => 'SKPD se-Kota Bukittinggi',
                'peran' => 'Pengguna Sistem',
                'deskripsi' => 'Satuan Kerja Perangkat Daerah yang menggunakan sistem untuk mengajukan permintaan pekerjaan ke Kominfo.'
            ],
            [
                'nama' => 'Pimpinan Kota Bukittinggi',
                'peran' => 'Pengawas Kinerja',
                'deskripsi' => 'Pihak yang memantau laporan dan beban kerja Kominfo melalui dashboard sistem.'
            ]
        ];

        return view('pages.tentang', compact('aboutData', 'teams'));
    }

    /**
     * Halaman Hubungi Kami
     */
    public function hubungi()
    {
        $kontakInfo = [
            [
                'icon' => 'bi-telephone',
                'label' => 'Telepon',
                'nilai' => '(0752) 123-4567'
            ],
            [
                'icon' => 'bi-envelope',
                'label' => 'Email',
                'nilai' => 'kominfo@bukittinggi.go.id'
            ],
            [
                'icon' => 'bi-geo-alt',
                'label' => 'Alamat',
                'nilai' => 'Jl. Panglima Nyak Arief No. 45, Bukittinggi, Sumatera Barat'
            ],
            [
                'icon' => 'bi-clock',
                'label' => 'Jam Operasional',
                'nilai' => 'Senin - Jumat, 08:00 - 17:00 WIB'
            ]
        ];

        $departments = [
            [
                'nama' => 'Bagian Website dan Portal',
                'email' => 'website@kominfo.bukittinggi.go.id',
                'fungsi' => 'Menangani perbaikan dan update website resmi SKPD'
            ],
            [
                'nama' => 'Bagian Infrastructure dan Server',
                'email' => 'infrastructure@kominfo.bukittinggi.go.id',
                'fungsi' => 'Menangani maintenance server, database, dan jaringan'
            ],
            [
                'nama' => 'Bagian Software dan Aplikasi',
                'email' => 'software@kominfo.bukittinggi.go.id',
                'fungsi' => 'Menangani pengembangan aplikasi dan software baru'
            ],
            [
                'nama' => 'Bagian Support dan Troubleshooting',
                'email' => 'support@kominfo.bukittinggi.go.id',
                'fungsi' => 'Menangani keluhan teknis dan support end-user'
            ]
        ];

        return view('pages.hubungi', compact('kontakInfo', 'departments'));
    }

    /**
     * Halaman Kebijakan Privasi
     */
    public function kebijakan()
    {
        $sections = [
            [
                'title' => '1. Pendahuluan',
                'content' => 'Sistem E-Ticket Kominfo Bukittinggi ("Sistem") berkomitmen untuk melindungi privasi dan keamanan data pengguna. Kebijakan Privasi ini menjelaskan bagaimana kami mengumpulkan, menggunakan, menyimpan, dan melindungi informasi Anda.'
            ],
            [
                'title' => '2. Informasi yang Kami Kumpulkan',
                'content' => 'Kami mengumpulkan informasi berikut:\n- Data pribadi (nama, NIP, jabatan, kontak)\n- Data instansi (nama SKPD, alamat, kontak)\n- Data tiket (deskripsi pekerjaan, lampiran, catatan)\n- Data penggunaan sistem (login history, aktivitas)'
            ],
            [
                'title' => '3. Penggunaan Informasi',
                'content' => 'Informasi yang kami kumpulkan digunakan untuk:\n- Mengelola permintaan pekerjaan dan tiket\n- Meningkatkan kualitas layanan sistem\n- Monitoring dan evaluasi kinerja\n- Keamanan dan pencegahan fraud'
            ],
            [
                'title' => '4. Keamanan Data',
                'content' => 'Kami menggunakan teknologi enkripsi dan protokol keamanan tingkat enterprise untuk melindungi data Anda. Akses data dibatasi hanya kepada pihak yang berwenang berdasarkan peran pengguna.'
            ],
            [
                'title' => '5. Retensi Data',
                'content' => 'Data tiket dan informasi terkait disimpan sesuai dengan regulasi pemerintah tentang pengarsipan data. Data akan dihapus jika tidak lagi diperlukan dan sesuai dengan kebijakan yang berlaku.'
            ],
            [
                'title' => '6. Hak Pengguna',
                'content' => 'Pengguna berhak untuk:\n- Mengakses data pribadi mereka\n- Meminta koreksi data yang tidak akurat\n- Menghapus akun mereka (dengan syarat dan ketentuan)\n- Menolak penggunaan data untuk tujuan tertentu'
            ],
            [
                'title' => '7. Perubahan Kebijakan',
                'content' => 'Kami dapat mengubah Kebijakan Privasi ini kapan saja. Perubahan akan diumumkan melalui sistem dan pengguna diminta untuk menyetujui kebijakan terbaru untuk melanjutkan penggunaan sistem.'
            ],
            [
                'title' => '8. Kontak',
                'content' => 'Jika Anda memiliki pertanyaan tentang kebijakan privasi ini, silakan hubungi kami melalui:\nEmail: privacy@kominfo.bukittinggi.go.id\nTelepon: (0752) 123-4567'
            ]
        ];

        return view('pages.kebijakan', compact('sections'));
    }

    /**
     * Halaman Syarat dan Ketentuan
     */
    public function syaratKetentuan()
    {
        $sections = [
            [
                'title' => '1. Penerimaan Syarat dan Ketentuan',
                'content' => 'Dengan menggunakan Sistem E-Ticket Kominfo Bukittinggi, Anda menyetujui untuk terikat oleh Syarat dan Ketentuan ini. Jika Anda tidak setuju dengan bagian mana pun, silakan jangan gunakan sistem ini.'
            ],
            [
                'title' => '2. Pengguna yang Diizinkan',
                'content' => 'Sistem ini hanya untuk penggunaan oleh pengguna internal Pemerintah Kota Bukittinggi. Pengguna harus berusia minimal 18 tahun dan memiliki akun yang valid. Anda bertanggung jawab untuk menjaga kerahasiaan password Anda.'
            ],
            [
                'title' => '3. Penggunaan Sistem',
                'content' => 'Anda setuju untuk menggunakan sistem hanya untuk tujuan yang sah dan sesuai dengan kebijakan Pemerintah Kota Bukittinggi. Larangan:\n- Menggunakan sistem untuk aktivitas ilegal\n- Mengunggah konten yang berbahaya atau tidak pantas\n- Mencoba mengakses area sistem yang tidak diizinkan\n- Mengganggu operasional sistem'
            ],
            [
                'title' => '4. Pengajuan Tiket',
                'content' => 'Pengajuan tiket harus berisi informasi yang akurat dan lengkap. SKPD bertanggung jawab untuk memastikan informasi yang diajukan benar. Tiket yang mengandung informasi palsu atau menyesatkan dapat ditolak.'
            ],
            [
                'title' => '5. Tanggung Jawab Kominfo',
                'content' => 'Kominfo berkomitmen untuk:\n- Memproses tiket sesuai prioritas dan SLA yang ditetapkan\n- Memberikan update status tiket secara berkala\n- Menjaga keamanan data dan privasi pengguna\n- Menyediakan dukungan teknis untuk penggunaan sistem'
            ],
            [
                'title' => '6. Batasan Tanggung Jawab',
                'content' => 'Kominfo tidak bertanggung jawab atas:\n- Kehilangan data akibat kesalahan pengguna\n- Gangguan sistem yang diluar kontrol\n- Penggunaan sistem yang tidak sesuai aturan\n- Kerugian yang timbul dari penggunaan sistem'
            ],
            [
                'title' => '7. Hak Kekayaan Intelektual',
                'content' => 'Semua konten, fitur, dan fungsionalitas sistem adalah milik Pemerintah Kota Bukittinggi. Pengguna tidak diizinkan untuk menggandakan, memodifikasi, atau mendistribusikan sistem atau konten tanpa izin resmi.'
            ],
            [
                'title' => '8. Penutupan Akun',
                'content' => 'Kominfo berhak untuk menutup akun pengguna jika ditemukan pelanggaran terhadap syarat dan ketentuan ini. Penutupan akun dapat dilakukan tanpa pemberitahuan sebelumnya dalam kasus-kasus tertentu.'
            ],
            [
                'title' => '9. Perubahan Syarat dan Ketentuan',
                'content' => 'Kami dapat mengubah syarat dan ketentuan ini kapan saja. Pengguna akan dinotifikasi tentang perubahan penting. Penggunaan sistem setelah perubahan dianggap sebagai persetujuan terhadap syarat dan ketentuan yang baru.'
            ],
            [
                'title' => '10. Penyelesaian Sengketa',
                'content' => 'Segala sengketa yang timbul dari penggunaan sistem akan diselesaikan sesuai dengan hukum yang berlaku di Indonesia dan yurisdiksi Pemerintah Kota Bukittinggi.'
            ]
        ];

        return view('pages.syarat-ketentuan', compact('sections'));
    }
}
