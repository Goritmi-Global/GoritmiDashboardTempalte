<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;
use Inertia\Inertia;
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
        $roles = Role::all();
        return Inertia::render('Users/Create', ['roles' => $roles]);
    }


    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required',
            'email' => 'required|email|unique:users',
            'password' => 'required|confirmed|min:6',
            'roles' => 'array'
        ]);

        $user = User::create([
            'name' => $validated['name'],
            'email' => $validated['email'],
            'password' => bcrypt($validated['password']),
        ]);

        $user->syncRoles($validated['roles']);

        return redirect()->route('users.index')->with('success', 'User created successfully');
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
       

        $user = User::with('roles')->findOrFail($id);
        $roles = Role::all();
        
        return Inertia::render('Users/Create', [
            'user' => $user,
            'roles' => $roles,
            // 'userRoles' => $user->roles->pluck('name'),
        ]);


    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }

    public function editRoles(User $user)
    {
        $roles = Role::all();
        return Inertia::render('Admin/Users/EditRoles', [
            'user' => $user,
            'roles' => $roles,
            'userRoles' => $user->roles->pluck('name'),
        ]);
    }
    public function updateRoles(Request $request, User $user)
    {
        $validated = $request->validate([
            'roles' => 'array'
        ]);

        $user->syncRoles($validated['roles']);

        return redirect()->route('users.index')->with('success', 'Roles updated');
    }


}
