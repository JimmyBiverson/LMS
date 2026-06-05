<?php

namespace Database\Seeders;

use App\Models\Category;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class CategorySeeder extends Seeder
{
    public function run(): void
    {
        $categories = ['Web Development', 'Data Science', 'Design', 'Mobile Development', 'Business'];

        foreach ($categories as $name) {
            Category::updateOrCreate(['slug' => Str::slug($name)], [
                'name' => $name,
                'description' => "Courses related to {$name}",
                'status' => 'active',
            ]);
        }
    }
}
