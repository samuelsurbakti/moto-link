<?php

namespace Database\Seeders\Starter;

use App\Models\User;
use App\Models\Slp\Role;
use App\Helpers\FileHelper;
use App\Models\Slp\Permission;
use Illuminate\Database\Seeder;
use Laravolt\Avatar\Facade as Avatar;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $avatar = md5('Sammy');
        FileHelper::ensure_folder_exists('img/user/', 'src');
        Avatar::create('Sammy')->save(storage_path('app/src/img/user/'.$avatar.'.png'), $quality = 100);

        $dev = User::create([
            'name' => 'Sammy',
            'username' => 'sammy',
            'email' => 'sammysurbakti@gmail.com',
            'password' => bcrypt('sammy'),
            'avatar' => $avatar.'.png',
            'account_status' => '1',
        ]);

        $dev->assignRole('Developer');

        $role = Role::where('name', 'Developer')->first();
        foreach (Permission::orderBy('number')->get() as $permission) {
            $role->givePermissionTo($permission->name);
        }
    }
}
