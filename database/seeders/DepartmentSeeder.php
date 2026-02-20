<?php

namespace Database\Seeders;

use App\Models\Department;
use Illuminate\Database\Seeder;

class DepartmentSeeder extends Seeder
{
    public function run(): void
    {
        $departments = [
            ['name' => 'Dinas Komunikasi dan Informatika',         'code' => 'KOMINFO',  'contact' => '0752-12345'],
            ['name' => 'Dinas Pendidikan dan Kebudayaan',          'code' => 'DIKBUD',   'contact' => '0752-12346'],
            ['name' => 'Dinas Kesehatan',                          'code' => 'DINKES',   'contact' => '0752-12347'],
            ['name' => 'Dinas Keuangan',                           'code' => 'DINKEU',   'contact' => '0752-12348'],
            ['name' => 'Badan Kepegawaian Daerah',                 'code' => 'BKD',      'contact' => '0752-12349'],
            ['name' => 'Dinas Pekerjaan Umum',                     'code' => 'DINPU',    'contact' => '0752-12350'],
            ['name' => 'Dinas Sosial',                             'code' => 'DINSOS',   'contact' => '0752-12351'],
            ['name' => 'Dinas Perdagangan dan Perindustrian',      'code' => 'DISPERIND','contact' => '0752-12352'],
            ['name' => 'Sekretariat Daerah',                       'code' => 'SETDA',    'contact' => '0752-12353'],
            ['name' => 'Badan Perencanaan Pembangunan Daerah',     'code' => 'BAPPEDA',  'contact' => '0752-12354'],
        ];

        foreach ($departments as $dept) {
            Department::firstOrCreate(['code' => $dept['code']], array_merge($dept, ['status' => 'aktif']));
        }
    }
}
