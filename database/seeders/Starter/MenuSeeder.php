<?php

namespace Database\Seeders\Starter;

use App\Models\Sys\App;
use App\Models\Sys\Menu;
use Illuminate\Database\Seeder;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class MenuSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        foreach (App::orderBy('order_number')->get() as $app) {
            if($app->name == 'Access') {
                $menu_no = 1;

                Menu::create([
                    'app_id' => $app->id,
                    'title' => 'Portal',
                    'icon' => 'bx bx-grid-alt',
                    'url' => 'gate',
                    'order_number' => $menu_no++,
                    'parent' => null,
                    'member_of' => null
                ]);

                Menu::create([
                    'app_id' => $app->id,
                    'title' => 'Akun',
                    'icon' => 'bx bxs-user-account',
                    'url' => 'account',
                    'order_number' => $menu_no++,
                    'parent' => null,
                    'member_of' => null
                ]);

                Menu::create([
                    'app_id' => $app->id,
                    'title' => 'Sistem',
                    'icon' => 'bx bx-cog',
                    'url' => 'system',
                    'order_number' => $menu_no++,
                    'parent' => null,
                    'member_of' => null
                ]);

                Menu::create([
                    'app_id' => $app->id,
                    'title' => 'Otorisasi',
                    'icon' => 'bx bx-check-shield',
                    'url' => 'authorization',
                    'order_number' => $menu_no++,
                    'parent' => null,
                    'member_of' => null
                ]);
            }

            else {
                Menu::create([
                    'app_id' => $app->id,
                    'title' => 'Beranda',
                    'icon' => 'bx bx-home',
                    'url' => 'home',
                    'order_number' => 1,
                    'parent' => null,
                    'member_of' => null
                ]);
            }
        }
    }
}
