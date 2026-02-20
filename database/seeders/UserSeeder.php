<?php

namespace Database\Seeders;

use App\Models\Department;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    public function run(): void
    {
        $kominfoDept = Department::where('code', 'KOMINFO')->first();
        $dikbudDept  = Department::where('code', 'DIKBUD')->first();
        $dinkesDept  = Department::where('code', 'DINKES')->first();
        $dinkeuDept  = Department::where('code', 'DINKEU')->first();
        $bkdDept     = Department::where('code', 'BKD')->first();

        $users = [
            // Admin Kominfo
            [
                'name'          => 'Administrator Sistem',
                'email'         => 'admin@kominfo.bukittinggi.go.id',
                'password'      => Hash::make('password'),
                'role'          => 'admin',
                'department_id' => $kominfoDept?->id,
                'status'        => 'aktif',
            ],
            // Pimpinan
            [
                'name'          => 'Kepala Dinas Kominfo',
                'email'         => 'pimpinan@kominfo.bukittinggi.go.id',
                'password'      => Hash::make('password'),
                'role'          => 'pimpinan',
                'department_id' => $kominfoDept?->id,
                'status'        => 'aktif',
            ],
            // Petugas Kominfo
            [
                'name'          => 'Ahmad Fauzi',
                'email'         => 'ahmad.fauzi@kominfo.bukittinggi.go.id',
                'password'      => Hash::make('password'),
                'role'          => 'petugas',
                'department_id' => $kominfoDept?->id,
                'status'        => 'aktif',
            ],
            [
                'name'          => 'Siti Aminah',
                'email'         => 'siti.aminah@kominfo.bukittinggi.go.id',
                'password'      => Hash::make('password'),
                'role'          => 'petugas',
                'department_id' => $kominfoDept?->id,
                'status'        => 'aktif',
            ],
            [
                'name'          => 'Rizki Pratama',
                'email'         => 'rizki.pratama@kominfo.bukittinggi.go.id',
                'password'      => Hash::make('password'),
                'role'          => 'petugas',
                'department_id' => $kominfoDept?->id,
                'status'        => 'aktif',
            ],
            [
                'name'          => 'Desi Marlina',
                'email'         => 'desi.marlina@kominfo.bukittinggi.go.id',
                'password'      => Hash::make('password'),
                'role'          => 'petugas',
                'department_id' => $kominfoDept?->id,
                'status'        => 'aktif',
            ],
            [
                'name'          => 'Budi Santoso',
                'email'         => 'budi.santoso@kominfo.bukittinggi.go.id',
                'password'      => Hash::make('password'),
                'role'          => 'petugas',
                'department_id' => $kominfoDept?->id,
                'status'        => 'aktif',
            ],
            // SKPD Users
            [
                'name'          => 'Operator Dinas Pendidikan',
                'email'         => 'operator@dikbud.bukittinggi.go.id',
                'password'      => Hash::make('password'),
                'role'          => 'skpd',
                'department_id' => $dikbudDept?->id,
                'status'        => 'aktif',
            ],
            [
                'name'          => 'Operator Dinas Kesehatan',
                'email'         => 'operator@dinkes.bukittinggi.go.id',
                'password'      => Hash::make('password'),
                'role'          => 'skpd',
                'department_id' => $dinkesDept?->id,
                'status'        => 'aktif',
            ],
            [
                'name'          => 'Operator Dinas Keuangan',
                'email'         => 'operator@dinkeu.bukittinggi.go.id',
                'password'      => Hash::make('password'),
                'role'          => 'skpd',
                'department_id' => $dinkeuDept?->id,
                'status'        => 'aktif',
            ],
            [
                'name'          => 'Operator BKD',
                'email'         => 'operator@bkd.bukittinggi.go.id',
                'password'      => Hash::make('password'),
                'role'          => 'skpd',
                'department_id' => $bkdDept?->id,
                'status'        => 'aktif',
            ],
        ];

        foreach ($users as $userData) {
            User::firstOrCreate(['email' => $userData['email']], $userData);
        }
    }
}
