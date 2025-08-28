<?php

namespace Database\Seeders\Starter;

use App\Models\Slp\Role;
use Illuminate\Database\Seeder;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class RoleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Role::create([
            'name' => 'Developer',
            'guard_name' => 'web',
        ]);

        Role::create([
            'name' => 'Owner',
            'guard_name' => 'web',
        ]);
    }
}
