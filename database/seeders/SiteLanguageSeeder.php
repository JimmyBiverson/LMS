<?php

namespace Database\Seeders;

use App\Models\SiteLanguage;
use Illuminate\Database\Seeder;

class SiteLanguageSeeder extends Seeder
{
    public function run(): void
    {
        SiteLanguage::insert([
            ['name' => 'English', 'code' => 'en', 'is_default' => true, 'status' => 'active'],
            ['name' => 'Spanish', 'code' => 'es', 'is_default' => false, 'status' => 'active'],
            ['name' => 'French', 'code' => 'fr', 'is_default' => false, 'status' => 'active'],
            ['name' => 'Arabic', 'code' => 'ar', 'is_default' => false, 'status' => 'active'],
        ]);
    }
}
