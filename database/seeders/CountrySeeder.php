<?php

namespace Database\Seeders;

use App\Models\Country;
use Illuminate\Database\Seeder;

class CountrySeeder extends Seeder
{
    public function run(): void
    {
        Country::insert([
            ['name' => 'United States', 'code' => 'US', 'status' => 'active'],
            ['name' => 'United Kingdom', 'code' => 'GB', 'status' => 'active'],
            ['name' => 'Canada', 'code' => 'CA', 'status' => 'active'],
            ['name' => 'Australia', 'code' => 'AU', 'status' => 'active'],
        ]);
    }
}
