<?php

namespace Database\Seeders;

use App\Models\Category;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class CategorySeeder extends Seeder
{
    public function run(): void
    {
        // ── CCTV ────────────────────────────────────────────────────────────────
        $cctv = [
            ['name' => 'CCTV', 'jenis' => 'cctv', 'description' => 'Pemasangan, perbaikan, dan pemeliharaan sistem CCTV'],
        ];

        // ── Pengaduan Publik ─────────────────────────────────────────────────────
        $publik = [
            ['name' => 'Internet Lambat',       'jenis' => 'publik', 'description' => 'Keluhan kecepatan internet publik yang lambat atau tidak stabil'],
            ['name' => 'Kabel Putus',            'jenis' => 'publik', 'description' => 'Pelaporan kabel jaringan yang putus atau rusak di area publik'],
            ['name' => 'Gangguan Jaringan',      'jenis' => 'publik', 'description' => 'Gangguan koneksi jaringan publik yang mempengaruhi masyarakat umum'],
            ['name' => 'Lainnya (Publik)',        'jenis' => 'publik', 'description' => 'Pengaduan publik lainnya yang tidak termasuk kategori di atas'],
        ];

        // ── Layanan SKPD ────────────────────────────────────────────────────────
        $skpd = [
            ['name' => 'Perbaikan Internet',       'jenis' => 'skpd', 'description' => 'Perbaikan koneksi atau infrastruktur internet di lingkungan SKPD'],
            ['name' => 'Pengembangan Aplikasi',    'jenis' => 'skpd', 'description' => 'Permintaan pengembangan atau modifikasi aplikasi internal SKPD'],
            ['name' => 'Integrasi Data',            'jenis' => 'skpd', 'description' => 'Integrasi atau sinkronisasi data antar sistem/aplikasi SKPD'],
            ['name' => 'Perbaikan Web Portal',     'jenis' => 'skpd', 'description' => 'Perbaikan website atau portal resmi SKPD'],
            ['name' => 'PIC',                       'jenis' => 'pic',  'description' => 'Permintaan yang langsung diteruskan ke petugas PIC yang ditunjuk per-SKPD'],
            ['name' => 'Troubleshooting',           'jenis' => 'skpd', 'description' => 'Pemecahan masalah teknis umum (jaringan, perangkat, software)'],
            ['name' => 'Permintaan Internet Baru', 'jenis' => 'skpd', 'description' => 'Permohonan pemasangan atau penambahan akses internet baru di SKPD'],
            ['name' => 'Lainnya',                   'jenis' => 'skpd', 'description' => 'Permintaan layanan SKPD lainnya yang tidak termasuk kategori di atas'],
        ];

        $all = array_merge($cctv, $publik, $skpd);

        foreach ($all as $cat) {
            Category::updateOrCreate(
                ['name' => $cat['name']],
                array_merge($cat, ['status' => 'aktif'])
            );
        }

        // Nonaktifkan kategori lama yang tidak ada di daftar baru
        $namaAktif = array_column($all, 'name');
        Category::whereNotIn('name', $namaAktif)->update(['status' => 'nonaktif']);
    }
}

