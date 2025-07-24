<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

class RoleAndPermissionController extends Controller
{
    function index()
    {
        $roles = Role::all();
        $permissions = Permission::all();
        return view('role-permission.index', compact('roles','permissions'));
    }

    function rolePermissionsData()
    {
        $roles = Role::all();
        return response()->json($roles, 200);
    }


    function store(Request $request)
    {
        $post = $request->all();


        $role = Role::create(['name' => $post['name']]);

        foreach ($post['permission'] as $key => $permission) {
            $role->givePermissionTo($permission);
        }

        return response()->json([
            'status' => 'success',
            'message' => 'Role & Permission added successfully!'
        ]);
    }

    public function addPermissions(Request $request)
    {
        /* $permissions = [
            'Contact',
            'Role Management',
        ];

        foreach ($permissions as $key => $value) {
            Permission::create(['name' => $value]);
        } */

        $user = User::whereId(Auth::user()->id)->first();

        $user->assignRole('Super Admin');
    }
}
