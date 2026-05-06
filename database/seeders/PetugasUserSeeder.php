<?php

namespace Database\Seeders;

use App\Models\Department;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class PetugasUserSeeder extends Seeder
{
    /**
     * Data petugas — pola: email = {namapertama}@bukittinggikota.go.id
     *                        password = {namapertama}123.
     */
    private array $petugas = [
        ['name' => "Jum'atul Zikri",       'email' => 'jumatul@bukittinggikota.go.id',  'password' => 'jumatul123.'],
        ['name' => 'Rahmad Hidayatullah',   'email' => 'rahmad@bukittinggikota.go.id',   'password' => 'rahmad123.'],
        ['name' => 'Ridho Al Amzah',        'email' => 'ridho@bukittinggikota.go.id',    'password' => 'ridho123.'],
        ['name' => 'Lutri Veflina',         'email' => 'lutri@bukittinggikota.go.id',    'password' => 'lutri123.'],
        ['name' => 'Ilham Sanusi',          'email' => 'ilham@bukittinggikota.go.id',    'password' => 'ilham123.'],
        ['name' => 'Didi Nugroho',          'email' => 'didi@bukittinggikota.go.id',     'password' => 'didi123.'],
        ['name' => 'Ryan Fitra',            'email' => 'ryan@bukittinggikota.go.id',     'password' => 'ryan123.'],
        ['name' => 'Rindu Putri Yuna',      'email' => 'rindu@bukittinggikota.go.id',    'password' => 'rindu123.'],
        ['name' => 'Aditya Marvi',          'email' => 'aditya@bukittinggikota.go.id',   'password' => 'aditya123.'],
    ];

    public function run(): void
    {
        $kominfoDept = Department::where('code', 'KOMINFO')->first();

        foreach ($this->petugas as $data) {
            $user = User::firstOrCreate(
                ['email' => $data['email']],
                [
                    'name'          => $data['name'],
                    'password'      => Hash::make($data['password']),
                    'role'          => 'petugas',
                    'department_id' => $kominfoDept?->id,
                    'status'        => 'aktif',
                ]
            );

            if (!$user->hasRole('petugas')) {
                $user->assignRole('petugas');
            }
        }
    }
}
