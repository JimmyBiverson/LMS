<?php

namespace Database\Factories;

use App\Models\Assignment;
use App\Models\Course;
use Illuminate\Database\Eloquent\Factories\Factory;

class AssignmentFactory extends Factory
{
    protected $model = Assignment::class;

    public function definition(): array
    {
        return [
            'course_id' => Course::factory(),
            'title' => fake()->sentence(4),
            'description' => fake()->paragraphs(2, true),
            'instructions' => fake()->paragraphs(3, true),
            'due_date' => fake()->dateTimeBetween('+1 week', '+1 month'),
            'total_marks' => fake()->randomElement([50, 100]),
            'status' => fake()->randomElement(['draft', 'published']),
        ];
    }

    public function published(): static
    {
        return $this->state(fn (array $attrs) => ['status' => 'published']);
    }
}
