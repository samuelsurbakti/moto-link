<?php

namespace Database\Seeders;

use App\Models\User;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Database\Seeders\Starter\AppSeeder;
use Database\Seeders\Starter\MenuSeeder;
use Database\Seeders\Starter\RoleSeeder;
use Database\Seeders\Starter\UserSeeder;
use Database\Seeders\Starter\PermissionSeeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        $this->call(AppSeeder::class);
        $this->call(MenuSeeder::class);
        $this->call(RoleSeeder::class);
        $this->call(PermissionSeeder::class);
        $this->call(UserSeeder::class);
    }
}
