<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\User;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

class UserTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        //
        $user = User::create([
            'name' => 'Administrator',
            'email' => 'admin@gmail.com',
            'password' => bcrypt('password')
        ]);

        //get all permissons
        $permissions = Permission::all();

        //get role admin
        $role = Role::find(1);

        //async role admin dan permissionnya
        $role->syncPermissions($permissions);

        //asign role to user
        $user->assignRole($role);
    }
}
