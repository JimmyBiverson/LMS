<?php

namespace Database\Seeders;

use App\Models\Timezone;
use Illuminate\Database\Seeder;

class TimezoneSeeder extends Seeder
{
    public function run(): void
    {
        Timezone::insert([
            ['name' => 'UTC', 'gmt_offset' => '+00:00', 'status' => 'active'],
            ['name' => 'America/New_York', 'gmt_offset' => '-05:00', 'status' => 'active'],
            ['name' => 'Europe/London', 'gmt_offset' => '+00:00', 'status' => 'active'],
            ['name' => 'Asia/Dubai', 'gmt_offset' => '+04:00', 'status' => 'active'],
        ]);
    }
}
