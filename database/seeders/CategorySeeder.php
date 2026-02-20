<?php

namespace Database\Seeders;

use App\Models\Category;
use Illuminate\Database\Seeder;

class CategorySeeder extends Seeder
{
    public function run(): void
    {
        $categories = [
            ['name' => 'PIC Presensi',         'description' => 'Permasalahan terkait sistem presensi elektronik pegawai'],
            ['name' => 'Perbaikan Portal',      'description' => 'Perbaikan website/portal resmi SKPD'],
            ['name' => 'Troubleshooting',       'description' => 'Pemecahan masalah teknis umum (jaringan, perangkat, software)'],
            ['name' => 'Maintenance Server',    'description' => 'Pemeliharaan rutin server dan infrastruktur'],
            ['name' => 'Instalasi Software',    'description' => 'Pemasangan perangkat lunak baru atau pembaruan'],
            ['name' => 'Keamanan Jaringan',     'description' => 'Penanganan insiden keamanan siber dan jaringan'],
            ['name' => 'Migrasi Data',          'description' => 'Pemindahan atau konversi data antar sistem'],
            ['name' => 'Pelatihan TI',          'description' => 'Bimbingan dan pelatihan penggunaan sistem/aplikasi'],
            ['name' => 'Pengembangan Aplikasi', 'description' => 'Permintaan pengembangan atau modifikasi aplikasi'],
            ['name' => 'Lainnya',               'description' => 'Permasalahan teknis lainnya yang tidak termasuk kategori di atas'],
        ];

        foreach ($categories as $cat) {
            Category::firstOrCreate(['name' => $cat['name']], array_merge($cat, ['status' => 'aktif']));
        }
    }
}
