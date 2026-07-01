<?php

namespace Database\Factories;

use App\Models\Course;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

class CourseFactory extends Factory
{
    protected $model = Course::class;

    public function definition(): array
    {
        $title = fake()->unique()->sentence(3);
        return [
            'user_id' => User::factory(),
            'title' => substr($title, 0, -1),
            'slug' => Str::slug($title) . '-' . Str::random(5),
            'description' => fake()->paragraphs(3, true),
            'category' => fake()->randomElement(['Web Development', 'Data Science', 'Design', 'Mobile Development']),
            'payment_type' => fake()->randomElement(['free', 'paid']),
            'price' => fake()->randomFloat(2, 0, 199),
            'sale_price' => null,
            'duration' => fake()->randomElement(['3h 00m', '5h 30m', '8h 15m', '12h 00m']),
            'status' => 'Active',
            'outcomes' => implode("\n", fake()->sentences(5)),
            'requirements' => implode("\n", fake()->sentences(3)),
        ];
    }

    public function free(): static
    {
        return $this->state(fn (array $attrs) => [
            'payment_type' => 'free',
            'price' => 0,
            'sale_price' => null,
        ]);
    }

    public function paid(): static
    {
        return $this->state(fn (array $attrs) => [
            'payment_type' => 'paid',
            'price' => fake()->randomFloat(2, 9.99, 199.99),
        ]);
    }
}
