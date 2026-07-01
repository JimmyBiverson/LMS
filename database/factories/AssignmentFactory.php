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
            'time_limit_minutes' => fake()->optional(0.3)->randomElement([30, 60, 90, 120]),
            'max_file_size_mb' => fake()->randomElement([5, 10, 20, 50]),
            'allowed_file_types' => fake()->optional(0.7)->randomElement([
                ['pdf', 'docx'],
                ['pdf', 'docx', 'txt'],
                ['zip', 'rar'],
                ['jpg', 'png', 'pdf'],
            ]),
            'late_submission_allowed' => fake()->boolean(40),
            'late_penalty_percent' => fake()->optional(0.5)->randomFloat(2, 5, 30),
            'grading_rubric' => fake()->optional(0.4)->passthrough([
                [
                    'criteria' => 'Content Quality',
                    'points' => 40,
                    'description' => 'Quality and accuracy of content',
                ],
                [
                    'criteria' => 'Structure and Organization',
                    'points' => 30,
                    'description' => 'Logical flow and organization',
                ],
                [
                    'criteria' => 'Formatting',
                    'points' => 30,
                    'description' => 'Proper formatting and citations',
                ],
            ]),
        ];
    }

    public function published(): static
    {
        return $this->state(fn (array $attrs) => ['status' => 'published']);
    }
}
