<?php

namespace Database\Seeders;

use App\Models\City;
use App\Models\State;
use Illuminate\Database\Seeder;

class CitySeeder extends Seeder
{
    public function run(): void
    {
        $california = State::where('name', 'California')->value('id');
        $texas = State::where('name', 'Texas')->value('id');
        $london = State::where('name', 'London')->value('id');

        City::insert([
            ['name' => 'Los Angeles', 'state_id' => $california, 'status' => 'active'],
            ['name' => 'San Francisco', 'state_id' => $california, 'status' => 'active'],
            ['name' => 'Austin', 'state_id' => $texas, 'status' => 'active'],
            ['name' => 'London', 'state_id' => $london, 'status' => 'active'],
        ]);
    }
}
