<?php

namespace Database\Seeders\Starter;

use App\Models\Sys\App;
use Illuminate\Database\Seeder;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class AppSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $app_no = 0;

        App::create([
            'name' => 'Access',
            'subdomain' => 'access.samuelsurbakti.my.id',
            'image' => 'access',
            'order_number' => $app_no++
        ]);

        App::create([
            'name' => 'MotoLink',
            'subdomain' => 'moto-link.samuelsurbakti.my.id',
            'image' => 'moto-link',
            'order_number' => $app_no++
        ]);

        App::create([
            'name' => 'CMS',
            'subdomain' => 'cms.samuelsurbakti.my.id',
            'image' => 'cms',
            'order_number' => $app_no++
        ]);
    }
}
