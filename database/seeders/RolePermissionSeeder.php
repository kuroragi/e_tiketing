<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

class RolePermissionSeeder extends Seeder
{
    public function run(): void
    {
        // Reset cached roles and permissions
        app()[\Spatie\Permission\PermissionRegistrar::class]->forgetCachedPermissions();

        // ─── Define all permissions ────────────────────────────────────────
        $permissions = [
            // Tiket
            'lihat-tiket',
            'buat-tiket',
            'kelola-tiket',
            'assign-tiket',
            'tutup-tiket',
            'hapus-tiket',

            // Laporan
            'lihat-laporan',
            'export-laporan',

            // Manajemen
            'kelola-pengguna',
            'kelola-role',
            'kelola-permission',
            'kelola-skpd',
            'kelola-kategori',
            'kelola-pengaturan',

            // Aktivitas
            'lihat-log',
        ];

        foreach ($permissions as $perm) {
            Permission::firstOrCreate(['name' => $perm, 'guard_name' => 'web']);
        }

        // ─── Create roles and assign permissions ───────────────────────────

        // Admin — akses penuh
        $admin = Role::firstOrCreate(['name' => 'admin', 'guard_name' => 'web']);
        $admin->syncPermissions(Permission::all());

        // Pimpinan — baca + laporan
        $pimpinan = Role::firstOrCreate(['name' => 'pimpinan', 'guard_name' => 'web']);
        $pimpinan->syncPermissions([
            'lihat-tiket',
            'lihat-laporan',
            'export-laporan',
        ]);

        // Petugas — kelola tiket
        $petugas = Role::firstOrCreate(['name' => 'petugas', 'guard_name' => 'web']);
        $petugas->syncPermissions([
            'lihat-tiket',
            'kelola-tiket',
            'assign-tiket',
            'tutup-tiket',
            'lihat-laporan',
        ]);

        // SKPD — hanya buat & lihat tiket sendiri
        $skpd = Role::firstOrCreate(['name' => 'skpd', 'guard_name' => 'web']);
        $skpd->syncPermissions([
            'buat-tiket',
            'lihat-tiket',
        ]);

        // ─── Assign Spatie roles to existing users ─────────────────────────
        User::all()->each(function (User $user) {
            // Gunakan kolom role yang sudah ada untuk assign Spatie role
            if ($user->role && ! $user->hasRole($user->role)) {
                $user->assignRole($user->role);
            }
        });
    }
}
