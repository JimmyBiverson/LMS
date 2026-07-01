<?php

namespace Database\Seeders;

use App\Models\Country;
use App\Models\State;
use Illuminate\Database\Seeder;

class StateSeeder extends Seeder
{
    public function run(): void
    {
        $us = Country::where('code', 'US')->value('id');
        $gb = Country::where('code', 'GB')->value('id');
        $ca = Country::where('code', 'CA')->value('id');

        State::insert([
            ['name' => 'California', 'country_id' => $us, 'status' => 'active'],
            ['name' => 'Texas', 'country_id' => $us, 'status' => 'active'],
            ['name' => 'London', 'country_id' => $gb, 'status' => 'active'],
            ['name' => 'Ontario', 'country_id' => $ca, 'status' => 'active'],
        ]);
    }
}
