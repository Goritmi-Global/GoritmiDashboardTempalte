<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Spatie\Permission\Models\Permission;
use Inertia\Inertia;

class PermissionController extends Controller
{
    public function index()
    {
        $permissions = Permission::all();
        return Inertia::render('Permissions/Index', compact('permissions'));
    }

    public function create()
    {
        return Inertia::render('Permissions/Create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|unique:permissions,name',
        ]);

        Permission::create(['name' => $request->name]);

        return redirect()->route('roles-permissions')->with('success', 'Permission created.');
    }

    public function edit(Permission $permission)
    {
        return Inertia::render('Permissions/Create', compact('permission'));
    }

    public function update(Request $request, Permission $permission)
    {
        $request->validate([
            'name' => 'required|unique:permissions,name,' . $permission->id,
        ]);

        $permission->update(['name' => $request->name]);

        return redirect()->route('roles-permissions')->with('success', 'Permission updated.');
    }

   public function destroy(Permission $permission)
{
    if ($permission->roles()->count() > 0) {
        return response()->json([
            'message' => 'Cannot delete permission: it is assigned to one or more roles.',
        ], 422); // 422 for validation-type error
    }

    $permission->delete();

    return response()->json(['message' => 'Permission deleted successfully.']);
}


}
