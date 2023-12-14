<?php

namespace App\Http\Controllers\Apps;

use Illuminate\Support\Facades\Hash;
use App\Models\User;
use Inertia\Inertia;
use Spatie\Permission\Models\Role;
use App\Http\Controllers\Controller;
use App\Http\Requests\UserRequests\StoreUserRequest;
use App\Http\Requests\UserRequests\UpdateUserRequest;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Redirect;

class UserController extends Controller
{
    public function index()
    {
        //get user
        $users = User::when(request()->q, function ($users) {
            $users = $users->where('name', 'like', '%' . request()->q . '%');
        })->with('roles')->latest()->paginate(5);

        return inertia('Apps/Users/Index', [
            'users' => $users,
        ]);
    }

    public function create()
    {
        $roles = Role::all();

        return Inertia::render('Apps/Users/Create', [
            'roles' => $roles
        ]);
    }

    public function store(StoreUserRequest $request): RedirectResponse
    {
        $validated = $request->validated();

        $validated['password'] = Hash::make($validated['password']);

        $user = User::create($validated);

        $user->assignRoles($validated['roles']);

        return redirect()->route('apps.users.index');
    }

    public function edit($id)
    {
        $user = User::with('roles')->findOrFail($id);

        $roles = Role::all();

        return Inertia::render('Apps/Users/Edit', [
            'user' => $user,
            'roles' => $roles
        ]);
    }

    public function update(UpdateUserRequest $request, User $user)
    {
        $validated = $request->validated();

        $validated['password'] = Hash::make($validated['password']);

        $user->update($validated);

        $user->syncRoles($request->roles);

        return redirect()->route('apps.users.index');
    }

    public function destroy($id)
    {
        $user = User::findOrFail($id);

        $user->delete();

        return redirect()->route('apps.users.index');
    }
}
