<?php

namespace Database\Factories;

use App\Models\Blog;
use App\Models\User;
use App\Models\BlogCategory;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

class BlogFactory extends Factory
{
    protected $model = Blog::class;

    public function definition(): array
    {
        $title = fake()->unique()->sentence(5);
        return [
            'title' => substr($title, 0, -1),
            'slug' => Str::slug(substr($title, 0, -1)),
            'content' => fake()->paragraphs(6, true),
            'excerpt' => fake()->paragraph(2),
            'blog_category_id' => BlogCategory::factory(),
            'user_id' => User::factory(),
            'status' => fake()->randomElement(['draft', 'published']),
        ];
    }

    public function published(): static
    {
        return $this->state(fn (array $attrs) => ['status' => 'published']);
    }
}
