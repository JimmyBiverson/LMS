<?php

namespace Database\Seeders;

use App\Models\Level;
use Illuminate\Database\Seeder;

class LevelSeeder extends Seeder
{
    public function run(): void
    {
        $levels = ['Beginner', 'Intermediate', 'Advanced', 'Expert'];
        foreach ($levels as $i => $name) {
            Level::updateOrCreate(['slug' => \Illuminate\Support\Str::slug($name)], [
                'name' => $name,
                'order' => $i + 1,
            ]);
        }
    }
}
