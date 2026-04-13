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

        User::firstOrCreate(
            ['email' => 'admin@kominfo.bukittinggi.go.id'],
            [
                'name'          => 'Administrator Sistem',
                'email'         => 'admin@kominfo.bukittinggi.go.id',
                'password'      => bcrypt('@Zaq123Qwerty'),
                'role'          => 'admin',
                'department_id' => $kominfoDept?->id,
                'status'        => 'aktif',
            ]
        );
    }
}
