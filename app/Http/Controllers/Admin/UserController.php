<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;
use Inertia\Inertia;
use Illuminate\Support\Facades\Hash;  
use Illuminate\Support\Facades\DB; 
class UserController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
{
    $users = User::with('roles', 'permissions')->paginate(10);
     $roles = Role::all();
    return Inertia::render('Users/Index', [
        'users' => $users,
        'roles' => $roles
    ]);
}

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $roles = Role::with('permissions')->get();

        return Inertia::render('Users/Create', ['roles' => $roles]);
    }


    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
{
    // dd($request);
    $validated = $request->validate([
        'name' => 'required',
        'email' => 'required|email|unique:users',
        'password' => 'required|confirmed|min:6',
        'roles' => 'array',
        'roles.*' => 'exists:roles,name',
    ]);

    DB::transaction(function () use ($validated) {
        $user = User::create([
            'name' => $validated['name'],
            'email' => $validated['email'],
            'password' => Hash::make($validated['password']),
        ]);

        $user->syncRoles($validated['roles']);
    });

    return to_route('users.index')->with('success', 'User created successfully');
}


    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
       

        $user = User::with('roles.permissions')->findOrFail($id);
        $roles = Role::with('permissions')->get();
        return Inertia::render('Users/Create', [
            'user' => $user,
            'roles' => $roles, 
        ]);


    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id)
{
    $user = User::findOrFail($id);

    $validated = $request->validate([
        'name' => 'required',
        'email' => 'required|email|unique:users,email,' . $user->id,
        'password' => 'nullable|confirmed|min:6',
        'roles' => 'array',
        'roles.*' => 'exists:roles,name',
    ]);

    $user->update([
        'name' => $validated['name'],
        'email' => $validated['email'],
        'password' => $validated['password']
            ? Hash::make($validated['password'])
            : $user->password,
    ]);

    $user->syncRoles($validated['roles']);

    return to_route('users.index')->with('success', 'User record updated successfully');
}


    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $user = User::findOrFail($id);
        
        $user->delete();

        return 'success';
    }

    // public function editRoles(User $user)
    // {
    //     $roles = Role::all();
    //     return Inertia::render('Admin/Users/EditRoles', [
    //         'user' => $user,
    //         'roles' => $roles,
    //         'userRoles' => $user->roles->pluck('name'),
    //     ]);
    // }
    // public function updateRoles(Request $request, User $user)
    // {
    //     $validated = $request->validate([
    //         'roles' => 'array'
    //     ]);

    //     $user->syncRoles($validated['roles']);

    //     return redirect()->route('users.index')->with('success', 'Roles updated');
    // }


}
