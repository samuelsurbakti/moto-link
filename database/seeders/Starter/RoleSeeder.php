<?php

namespace Database\Seeders\Starter;

use App\Models\Sys\App;
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
        $default_app = App::where('name', 'Access')->first();

        Role::create([
            'name' => 'Developer',
            'guard_name' => 'web',
            'default_app_id' => $default_app->id,
            'badge_color' => '2b2c40',
        ]);

        Role::create([
            'name' => 'Owner',
            'guard_name' => 'web',
            'default_app_id' => $default_app->id,
            'badge_color' => '0D47A1',
        ]);
    }
}
