<?php
 
namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;

use Inertia\Inertia;

class RoleController extends Controller
{
     
public function roles_and_permissions()
{
    return Inertia::render('Roles/Index', [
        'roles' => Role::with('permissions')->get(),
        'permissions' => Permission::all(),
    ]);
}
public function index()
{
    return $this->roles_and_permissions();
    // return Inertia::render('Roles/Index', [
    //     'roles' => Role::with('permissions')->get(),
    // ]);
}

public function create()
{
    return Inertia::render('Roles/Create', [
        'permissions' => Permission::all(),
    ]);
}

public function store(Request $request)
{
    $validated = $request->validate([
        'name' => 'required|unique:roles',
        'permissions' => 'array',
    ]);

    $role = Role::create(['name' => $validated['name']]);
    $role->syncPermissions($validated['permissions']);

    return redirect()->route('roles.index')->with('success', 'Role created');
}

public function edit(Role $role)
{
    return Inertia::render('Roles/Create', [
        'role' => $role,
        'permissions' => Permission::all(),
        'rolePermissions' => $role->permissions->pluck('name'),
    ]);
}

public function update(Request $request, Role $role)
{
    $validated = $request->validate([
        'name' => 'required|unique:roles,name,' . $role->id,
        'permissions' => 'array',
    ]);

    $role->update(['name' => $validated['name']]);
    $role->syncPermissions($validated['permissions']);

    return redirect()->route('roles.index')->with('success', 'Role updated');
}

}
