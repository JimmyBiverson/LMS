<?php

namespace Database\Seeders;

use App\Models\IconProvider;
use Illuminate\Database\Seeder;

class IconProviderSeeder extends Seeder
{
    public function run(): void
    {
        IconProvider::insert([
            ['name' => 'Remixicon', 'url' => 'https://remixicon.com', 'status' => 'active'],
            ['name' => 'Font Awesome', 'url' => 'https://fontawesome.com', 'status' => 'active'],
            ['name' => 'Bootstrap Icons', 'url' => 'https://icons.getbootstrap.com', 'status' => 'active'],
        ]);
    }
}
