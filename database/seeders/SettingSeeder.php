<?php

namespace Database\Seeders;

use App\Models\Setting;
use Illuminate\Database\Seeder;

class SettingSeeder extends Seeder
{
    public function run(): void
    {
        $settings = [
            ['key' => 'app_name',         'value' => 'Sistem Ticketing Kominfo',         'type' => 'string',  'description' => 'Nama aplikasi'],
            ['key' => 'app_description',  'value' => 'Layanan Kominfo Kota Bukittinggi', 'type' => 'string',  'description' => 'Deskripsi aplikasi'],
            ['key' => 'app_institution',  'value' => 'Dinas Komunikasi dan Informatika Kota Bukittinggi', 'type' => 'string', 'description' => 'Nama instansi'],
            ['key' => 'max_upload_size',  'value' => '10240',                            'type' => 'integer', 'description' => 'Ukuran maksimal unggahan dalam KB (default 10 MB)'],
            ['key' => 'allowed_mimetypes','value' => 'application/pdf,image/jpeg,image/png,image/jpg', 'type' => 'string', 'description' => 'MIME type yang diizinkan untuk lampiran'],
            ['key' => 'smtp_host',        'value' => 'smtp.mailtrap.io',                 'type' => 'string',  'description' => 'SMTP host'],
            ['key' => 'smtp_port',        'value' => '587',                              'type' => 'integer', 'description' => 'SMTP port'],
            ['key' => 'mail_from_name',   'value' => 'Kominfo Bukittinggi',              'type' => 'string',  'description' => 'Nama pengirim email'],
            ['key' => 'mail_from_address','value' => 'noreply@kominfo.bukittinggi.go.id','type' => 'string',  'description' => 'Alamat pengirim email'],

            // Landing Page
            ['key' => 'landing_hero_title',       'value' => "Layanan Pengaduan &\nPermintaan Data Publik",  'type' => 'string',  'description' => 'Judul hero landing page'],
            ['key' => 'landing_hero_subtitle',    'value' => 'Sampaikan pengaduan, permintaan data CCTV, atau layanan lainnya secara online. Cepat, transparan, dan dapat dilacak.', 'type' => 'string', 'description' => 'Sub-judul hero landing page'],
            ['key' => 'landing_services_title',   'value' => 'Jenis Layanan yang Tersedia',                 'type' => 'string',  'description' => 'Judul section layanan'],
            ['key' => 'landing_services_subtitle','value' => 'Pilih jenis layanan sesuai kebutuhan Anda. Semua layanan dapat diakses tanpa perlu datang ke kantor.', 'type' => 'string', 'description' => 'Sub-judul section layanan'],
            ['key' => 'landing_primary_color',    'value' => '#4f46e5',                                     'type' => 'string',  'description' => 'Warna utama landing page'],
            ['key' => 'landing_primary_dark',     'value' => '#3730a3',                                     'type' => 'string',  'description' => 'Warna utama gelap landing page'],
            ['key' => 'landing_enable_public_ticket','value' => '1',                                        'type' => 'boolean', 'description' => 'Aktifkan pengaduan publik'],
            ['key' => 'landing_show_stats',       'value' => '1',                                           'type' => 'boolean', 'description' => 'Tampilkan statistik di landing'],
            ['key' => 'landing_show_recent',      'value' => '1',                                           'type' => 'boolean', 'description' => 'Tampilkan pengaduan terbaru di landing'],

            // API
            ['key' => 'api_enabled',    'value' => '1',  'type' => 'boolean', 'description' => 'Aktifkan REST API'],
            ['key' => 'api_rate_limit', 'value' => '30', 'type' => 'integer', 'description' => 'API rate limit per menit'],
            ['key' => 'api_key',        'value' => '',   'type' => 'string',  'description' => 'API key untuk akses eksternal'],
        ];

        foreach ($settings as $s) {
            Setting::firstOrCreate(['key' => $s['key']], $s);
        }
    }
}
