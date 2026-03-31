<?php

namespace App\Http\Controllers;

use App\Models\AuditLog;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;
use Spatie\Permission\PermissionRegistrar;

class RoleController extends Controller
{
    public function index()
    {
        $roles = Role::with('permissions')
            ->withCount('users')
            ->orderBy('name')
            ->get();

        $allPermissions = Permission::orderBy('name')->get()->groupBy(function ($p) {
            return explode('-', $p->name)[1] ?? 'lainnya';
        });

        return view('pages.admin.role.index', compact('roles', 'allPermissions'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'name'        => 'required|string|max:50|unique:roles,name',
            'permissions' => 'nullable|array',
            'permissions.*' => 'exists:permissions,id',
        ]);

        $role = Role::create(['name' => $request->name, 'guard_name' => 'web']);

        if ($request->filled('permissions')) {
            $role->syncPermissions($request->permissions);
        }

        app()[PermissionRegistrar::class]->forgetCachedPermissions();

        AuditLog::create([
            'user_id'     => Auth::id(),
            'action'      => 'created',
            'entity_type' => 'Role',
            'entity_id'   => $role->id,
            'entity_name' => $role->name,
            'new_value'   => ['permissions' => $request->permissions],
            'description' => "Role '{$role->name}' dibuat",
            'ip_address'  => $request->ip(),
            'user_agent'  => $request->userAgent(),
        ]);

        return back()->with('success', "Role '{$role->name}' berhasil dibuat.");
    }

    public function update(Request $request, $id)
    {
        $role = Role::findOrFail($id);

        $request->validate([
            'name'        => 'required|string|max:50|unique:roles,name,' . $id,
            'permissions' => 'nullable|array',
            'permissions.*' => 'exists:permissions,id',
        ]);

        // Jaga role admin agar tidak bisa di-rename
        if ($role->name === 'admin' && $request->name !== 'admin') {
            return back()->with('error', 'Role admin tidak dapat di-rename.');
        }

        $old = $role->name;
        $role->update(['name' => $request->name]);
        $role->syncPermissions($request->permissions ?? []);

        app()[PermissionRegistrar::class]->forgetCachedPermissions();

        AuditLog::create([
            'user_id'     => Auth::id(),
            'action'      => 'updated',
            'entity_type' => 'Role',
            'entity_id'   => $role->id,
            'entity_name' => $role->name,
            'old_value'   => ['name' => $old],
            'new_value'   => ['name' => $request->name, 'permissions' => $request->permissions],
            'description' => "Role '{$old}' diperbarui",
            'ip_address'  => $request->ip(),
            'user_agent'  => $request->userAgent(),
        ]);

        return back()->with('success', "Role '{$role->name}' berhasil diperbarui.");
    }

    public function destroy(Request $request, $id)
    {
        $role = Role::findOrFail($id);

        if (in_array($role->name, ['admin', 'petugas', 'skpd', 'pimpinan'])) {
            return back()->with('error', "Role sistem '{$role->name}' tidak dapat dihapus.");
        }

        AuditLog::create([
            'user_id'     => Auth::id(),
            'action'      => 'deleted',
            'entity_type' => 'Role',
            'entity_id'   => $role->id,
            'entity_name' => $role->name,
            'description' => "Role '{$role->name}' dihapus",
            'ip_address'  => $request->ip(),
            'user_agent'  => $request->userAgent(),
        ]);

        $role->delete();

        app()[PermissionRegistrar::class]->forgetCachedPermissions();

        return back()->with('success', "Role berhasil dihapus.");
    }
}
