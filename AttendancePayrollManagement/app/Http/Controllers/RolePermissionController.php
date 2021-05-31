<?php

namespace App\Http\Controllers;
use app\Services\PermissionService;
use Illuminate\Http\Request;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;
use App\User;


class RolePermissionController extends Controller
{
    public function __construct()
    {
        // $this->PermissionService = new PermissionService;
    }
    public function permission(){

        // dd('test');
        // $this->PermissionService;
        // return Permission::all();
    }

    public function givepermission(Request $request)
    {
        // $user->givePermissionTo('edit articles');
    }

    public function createRole(Request $request)
    {
        // dd($request->permission);
        $role = Role::create(['name' => $request->name]);
        // dd($role);

        foreach($request->permission as $value)
        {
            $permission=Permission::find($value);
            $role->assignPermission($permission);
        }
        return 'success';
    }
    public function assignPermissiontoRole()
    {

    }
    public function getRoles()
    {
        // dd('tste');
        return User::role('user')->get();
        // return User::where($role->name,'admin');
    }
}

