<?php

namespace Database\Seeders\Starter;

use App\Models\Sys\App;
use App\Models\Sys\Menu;
use App\Models\Slp\Permission;
use Illuminate\Database\Seeder;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class PermissionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $app_number = 1;
        foreach (App::orderBy('order_number')->get() as $app) {
            Permission::create([
                'type' => 'App',
                'app_id' => $app->id,
                'menu_id' => null,
                'name' => $app->name,
                'guard_name' => 'web',
                'number' => $app_number++,
            ]);

            $menu_number = 1;

            foreach (Menu::where('app_id', $app->id)->orderBy('order_number')->get() as $menu) {
                Permission::create([
                    'type' => 'Menu',
                    'app_id' => $app->id,
                    'menu_id' => $menu->id,
                    'name' => $app->name.' - '.$menu->title,
                    'guard_name' => 'web',
                    'number' => $menu_number++,
                ]);

                if($app->name == 'Access' && $menu->title == 'Otorisasi')
                {
                    Permission::create([
                        'type' => 'Permission',
                        'app_id' => $app->id,
                        'menu_id' => $menu->id,
                        'name' => $app->name.' - '.$menu->title.' - Mengelola Otoritas',
                        'guard_name' => 'web',
                        'number' => 1,
                    ]);

                    Permission::create([
                        'type' => 'Permission',
                        'app_id' => $app->id,
                        'menu_id' => $menu->id,
                        'name' => $app->name.' - '.$menu->title.' - Peran - Melihat Daftar Data',
                        'guard_name' => 'web',
                        'number' => 11,
                    ]);

                    Permission::create([
                        'type' => 'Permission',
                        'app_id' => $app->id,
                        'menu_id' => $menu->id,
                        'name' => $app->name.' - '.$menu->title.' - Peran - Menambah Data',
                        'guard_name' => 'web',
                        'number' => 12,
                    ]);

                    Permission::create([
                        'type' => 'Permission',
                        'app_id' => $app->id,
                        'menu_id' => $menu->id,
                        'name' => $app->name.' - '.$menu->title.' - Peran - Mengubah Data',
                        'guard_name' => 'web',
                        'number' => 13,
                    ]);

                    Permission::create([
                        'type' => 'Permission',
                        'app_id' => $app->id,
                        'menu_id' => $menu->id,
                        'name' => $app->name.' - '.$menu->title.' - Izin - Melihat Daftar Data',
                        'guard_name' => 'web',
                        'number' => 21,
                    ]);

                    Permission::create([
                        'type' => 'Permission',
                        'app_id' => $app->id,
                        'menu_id' => $menu->id,
                        'name' => $app->name.' - '.$menu->title.' - Izin - Menambah Data',
                        'guard_name' => 'web',
                        'number' => 22,
                    ]);

                    Permission::create([
                        'type' => 'Permission',
                        'app_id' => $app->id,
                        'menu_id' => $menu->id,
                        'name' => $app->name.' - '.$menu->title.' - Izin - Mengubah Data',
                        'guard_name' => 'web',
                        'number' => 23,
                    ]);

                    Permission::create([
                        'type' => 'Permission',
                        'app_id' => $app->id,
                        'menu_id' => $menu->id,
                        'name' => $app->name.' - '.$menu->title.' - Izin - Menghapus Data',
                        'guard_name' => 'web',
                        'number' => 24,
                    ]);
                }

                if($app->name == 'Access' && $menu->title == 'Sistem')
                {
                    Permission::create([
                        'type' => 'Permission',
                        'app_id' => $app->id,
                        'menu_id' => $menu->id,
                        'name' => $app->name.' - '.$menu->title.' - Aplikasi - Melihat Daftar Data',
                        'guard_name' => 'web',
                        'number' => 11,
                    ]);

                    Permission::create([
                        'type' => 'Permission',
                        'app_id' => $app->id,
                        'menu_id' => $menu->id,
                        'name' => $app->name.' - '.$menu->title.' - Aplikasi - Menambah Data',
                        'guard_name' => 'web',
                        'number' => 12,
                    ]);

                    Permission::create([
                        'type' => 'Permission',
                        'app_id' => $app->id,
                        'menu_id' => $menu->id,
                        'name' => $app->name.' - '.$menu->title.' - Aplikasi - Mengubah Data',
                        'guard_name' => 'web',
                        'number' => 13,
                    ]);

                    Permission::create([
                        'type' => 'Permission',
                        'app_id' => $app->id,
                        'menu_id' => $menu->id,
                        'name' => $app->name.' - '.$menu->title.' - Menu - Melihat Daftar Data',
                        'guard_name' => 'web',
                        'number' => 21,
                    ]);

                    Permission::create([
                        'type' => 'Permission',
                        'app_id' => $app->id,
                        'menu_id' => $menu->id,
                        'name' => $app->name.' - '.$menu->title.' - Menu - Menambah Data',
                        'guard_name' => 'web',
                        'number' => 22,
                    ]);

                    Permission::create([
                        'type' => 'Permission',
                        'app_id' => $app->id,
                        'menu_id' => $menu->id,
                        'name' => $app->name.' - '.$menu->title.' - Menu - Mengubah Data',
                        'guard_name' => 'web',
                        'number' => 23,
                    ]);

                    Permission::create([
                        'type' => 'Permission',
                        'app_id' => $app->id,
                        'menu_id' => $menu->id,
                        'name' => $app->name.' - '.$menu->title.' - Menu - Menghapus Data',
                        'guard_name' => 'web',
                        'number' => 24,
                    ]);
                }
            }
        }
    }
}
