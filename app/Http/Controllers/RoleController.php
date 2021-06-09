<?php

namespace App\Http\Controllers;

use App\Models\Role;
use App\Notifications\Note;
use App\Permission as AppPermission;
use App\User;
use File;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;


class RoleController extends Controller
{

    public function index()
    {
        return Role::all();
    }

    public function show(Role $role)
    {
        return $role->load('users', 'users.roles:name');
    }
    public function store()
    {
        request()->validate([
            'name' => 'required|unique:roles'
        ]);

        return Role::create(request()->all());
    }

    public function update(Role $role)
    {
        if (request()->has('add_user')) {
            $role->users()->syncWithoutDetaching(request()->user_id);
            return $role->load('users', 'users.roles:name');
        }
        if (request()->has('remove_user')) {
            $role->users()->detach(request()->user_id);
            return 'ok';
        }

        request()->validate([
            'name' => 'required|unique:roles,name,' . $role->id
        ]);

        $role->update(request()->all());
        return $role;
    }
    public function destroy(Role $role)
    {
        $role->delete();
    }

    public function findUsers($name = '')
    {
        return \App\User::select('id', 'name')->where('name', 'LIKE', '%' . $name . '%')->orWhere('email', 'LIKE', '%' . $name . '%')->orWhere('telephone', 'LIKE', '%' . $name . '%')->get()->take(10);
    }
    public function roleUsers($id = 0)
    {
        $role = Role::findOrFail($id);
        return $role->users->map->only('id', 'name', 'department_id');
    }

    public function UpdateRoles(Request $request)
    {
        if ($request->mode == 'update_role_permissions') {

            return   Role::findOrfail($request->role_id)->syncPermissions($request->ids);
        } elseif ($request->mode == 'remove_user_from_role') {
            Role::findOrfail($request->role_id)->users()->detach([$request->ids]);
        } elseif ($request->mode == 'add_user_to_role') {
            User::findOrFail($request->ids)->assignRole($request->role_id);
            //Role::findOrfail()->users()->attach([$request->ids]);
        }



        //  if ($request->mode == 'add')   return Role::create(['name' => $request->role_name]);
        // else if ($request->mode == 'delete')  Role::findOrfail($request->role_id)->delete();
        // else if ($request->mode == 'edit')   Role::findOrFail($request->role_id)->update(['name' => $request->role_name]);
    }

    public function UpdatePermissions(Request $request)
    {
        //  if ($request->mode == 'add')    return Permission::create(['name' => $request->perm_name, 'page' => $request->perm_page_name]);
        // else if ($request->mode == 'delete')   Permission::findOrFail($request->permission_id)->delete();
        // else if ($request->mode == 'edit')   Permission::findOrFail($request->permission_id)->update(['name' => $request->perm_name, 'page' => $request->perm_page_name]);
    }


    public function addPermission(Request $request)
    {

        $permission = Permission::create(['name' => $request->perm_name]);

        return 'ok';
    }
    public function addPermissionToRole(Request $request)
    {
        $role = Role::findOrFail($request->role_id);
        $permission = Permission::findOrFail($request->permission_id);
        if ($request->mode == 1)   $role->givePermissionTo($permission);
        else  $role->revokePermissionTo($permission);
        return Role::where('id', $role->id)->with(['permissions'])->get()->first();
    }
    public function test()
    {
        return \App\User::Role(['a1', 'admin'])->get();
    }
}
