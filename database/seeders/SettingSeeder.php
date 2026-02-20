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
        ];

        foreach ($settings as $s) {
            Setting::firstOrCreate(['key' => $s['key']], $s);
        }
    }
}
