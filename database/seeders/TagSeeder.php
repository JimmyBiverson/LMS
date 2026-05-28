<?php

namespace Database\Seeders;

use App\Models\Tag;
use Illuminate\Database\Seeder;

class TagSeeder extends Seeder
{
    public function run(): void
    {
        $tags = ['PHP', 'Laravel', 'JavaScript', 'Python', 'CSS', 'HTML', 'React', 'Vue.js', 'Node.js', 'MySQL'];
        foreach ($tags as $name) {
            Tag::create([
                'name' => $name,
                'slug' => \Illuminate\Support\Str::slug($name),
            ]);
        }
    }
}
