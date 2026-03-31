<?php

namespace App\Http\Controllers;

use App\Models\AuditLog;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;
use Spatie\Permission\PermissionRegistrar;

class PermissionController extends Controller
{
    public function index()
    {
        $permissions = Permission::withCount('roles')->orderBy('name')->get();
        $roles       = Role::with('permissions')->orderBy('name')->get();

        return view('pages.admin.permission.index', compact('permissions', 'roles'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:80|unique:permissions,name',
        ]);

        $perm = Permission::create(['name' => $request->name, 'guard_name' => 'web']);

        app()[PermissionRegistrar::class]->forgetCachedPermissions();

        AuditLog::create([
            'user_id'     => Auth::id(),
            'action'      => 'created',
            'entity_type' => 'Permission',
            'entity_id'   => $perm->id,
            'entity_name' => $perm->name,
            'description' => "Permission '{$perm->name}' dibuat",
            'ip_address'  => $request->ip(),
            'user_agent'  => $request->userAgent(),
        ]);

        return back()->with('success', "Permission '{$perm->name}' berhasil dibuat.");
    }

    public function update(Request $request, $id)
    {
        $perm = Permission::findOrFail($id);

        $request->validate([
            'name' => 'required|string|max:80|unique:permissions,name,' . $id,
        ]);

        $old = $perm->name;
        $perm->update(['name' => $request->name]);

        app()[PermissionRegistrar::class]->forgetCachedPermissions();

        AuditLog::create([
            'user_id'     => Auth::id(),
            'action'      => 'updated',
            'entity_type' => 'Permission',
            'entity_id'   => $perm->id,
            'entity_name' => $perm->name,
            'old_value'   => ['name' => $old],
            'new_value'   => ['name' => $request->name],
            'description' => "Permission '{$old}' diubah menjadi '{$request->name}'",
            'ip_address'  => $request->ip(),
            'user_agent'  => $request->userAgent(),
        ]);

        return back()->with('success', "Permission berhasil diperbarui.");
    }

    public function destroy(Request $request, $id)
    {
        $perm = Permission::findOrFail($id);

        AuditLog::create([
            'user_id'     => Auth::id(),
            'action'      => 'deleted',
            'entity_type' => 'Permission',
            'entity_id'   => $perm->id,
            'entity_name' => $perm->name,
            'description' => "Permission '{$perm->name}' dihapus",
            'ip_address'  => $request->ip(),
            'user_agent'  => $request->userAgent(),
        ]);

        $perm->delete();

        app()[PermissionRegistrar::class]->forgetCachedPermissions();

        return back()->with('success', "Permission berhasil dihapus.");
    }
}
