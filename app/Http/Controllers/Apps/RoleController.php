<?php

namespace App\Http\Controllers\Apps;

use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;
use App\Http\Controllers\Controller;
use App\Http\Requests\RoleRequests\StoreRoleRequest;
use App\Http\Requests\RoleRequests\UpdateRoleRequest;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Redirect;

class RoleController extends Controller
{
    public function index()
    {
        //get roles
        $roles = Role::when(request()->q, function ($roles) {
            $roles = $roles->where('name', 'like', '%' . request()->q . '%');
        })->with('permissions')->latest()->paginate(5);

        //render with inertia
        return inertia('Apps/Roles/Index', [
            'roles' => $roles,
        ]);
    }

    public function create()
    {
        //get permission all
        $permissions = Permission::all();

        //render with inertia 
        return inertia('Apps/Roles/Create', [
            'permissions' => $permissions,
        ]);
    }

    public function store(StoreRoleRequest $request): RedirectResponse
    {
        //validate request
        $role = $request->validated();

        //create role
        $role = Role::create(['name' => $request->name]);

        //assign permissions to role
        $role->givePermissionTo($request->permissions);

        //redirect
        return redirect()->route('apps.roles.index');
    }

    public function edit($id)
    {
        //get role
        $role = Role::with('permissions')->findOrFail($id);

        //get permission all
        $permissions = Permission::all();

        //return with inertia
        return inertia('Apps/Roles/Edit', [
            'role' => $role,
            'permissions' => $permissions,
        ]);
    }

    public function update(UpdateRoleRequest $request, Role $role)
    {
        //validate request
        $validated = $request->validated();

        $role->update($validated);

        //sync permission
        $role->syncPermissions($request->permissions);

        //redirect
        return redirect()->route('apps.roles.index');
    }

    public function destroy($id)
    {
        //find role by id
        $role = Role::findOrFail($id);

        //delete role
        $role->delete();

        //redirect
        return redirect()->route('apps.roles.index');
    }
}
