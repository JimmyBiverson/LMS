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
            Level::create([
                'name' => $name,
                'slug' => \Illuminate\Support\Str::slug($name),
                'order' => $i + 1,
            ]);
        }
    }
}
