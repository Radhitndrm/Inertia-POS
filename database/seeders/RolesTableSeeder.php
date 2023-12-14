<?php

namespace Database\Seeders;
use Spatie\Permission\Models\Role;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class RolesTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Role::Create(['name' => 'admin']);
        Role::Create(['name' => 'cashier']);

    }
}
