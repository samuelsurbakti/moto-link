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
            'subdomain' => 'access.moto-link.test',
            'image' => 'access',
            'order_number' => $app_no++
        ]);

        App::create([
            'name' => 'MotoLink',
            'subdomain' => 'moto-link.moto-link.test',
            'image' => 'moto-link',
            'order_number' => $app_no++
        ]);

        App::create([
            'name' => 'CMS',
            'subdomain' => 'cms.moto-link.test',
            'image' => 'cms',
            'order_number' => $app_no++
        ]);
    }
}
