<?php

namespace Database\Seeders;

use App\Models\Priority;
use Illuminate\Database\Seeder;

class PrioritySeeder extends Seeder
{
    public function run(): void
    {
        $priorities = [
            ['name' => 'Urgent',  'weight' => 4, 'color' => '#dc3545', 'description' => 'Gangguan kritis yang menghentikan operasional, perlu penanganan segera'],
            ['name' => 'Tinggi',  'weight' => 3, 'color' => '#fd7e14', 'description' => 'Masalah serius yang mempengaruhi kinerja, selesai dalam 1 hari kerja'],
            ['name' => 'Sedang',  'weight' => 2, 'color' => '#0dcaf0', 'description' => 'Masalah yang perlu ditangani, selesai dalam 3 hari kerja'],
            ['name' => 'Rendah',  'weight' => 1, 'color' => '#198754', 'description' => 'Permintaan rutin atau peningkatan, selesai dalam 7 hari kerja'],
        ];

        foreach ($priorities as $priority) {
            Priority::firstOrCreate(['name' => $priority['name']], $priority);
        }
    }
}
