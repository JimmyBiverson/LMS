<?php

namespace Database\Seeders;

use App\Models\Currency;
use Illuminate\Database\Seeder;

class CurrencySeeder extends Seeder
{
    public function run(): void
    {
        Currency::insert([
            ['name' => 'US Dollar', 'code' => 'USD', 'symbol' => '$', 'rate' => 1.00, 'is_default' => true, 'status' => 'active'],
            ['name' => 'Euro', 'code' => 'EUR', 'symbol' => '€', 'rate' => 0.92, 'is_default' => false, 'status' => 'active'],
            ['name' => 'Pound', 'code' => 'GBP', 'symbol' => '£', 'rate' => 0.79, 'is_default' => false, 'status' => 'active'],
        ]);
    }
}
