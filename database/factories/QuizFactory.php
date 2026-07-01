<?php

namespace Database\Factories;

use App\Models\Quiz;
use App\Models\Course;
use Illuminate\Database\Eloquent\Factories\Factory;

class QuizFactory extends Factory
{
    protected $model = Quiz::class;

    public function definition(): array
    {
        return [
            'course_id' => Course::factory(),
            'title' => fake()->sentence(3),
            'instructions' => fake()->paragraph(),
            'time_limit' => fake()->randomElement([10, 15, 20, 30, 45, 60]),
            'passing_score' => fake()->randomElement([40, 50, 60, 70, 80]),
            'total_marks' => 0,
            'status' => fake()->randomElement(['draft', 'published']),
        ];
    }

    public function published(): static
    {
        return $this->state(fn (array $attrs) => ['status' => 'published']);
    }
}
