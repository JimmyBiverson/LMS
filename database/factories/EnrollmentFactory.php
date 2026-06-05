<?php

namespace Database\Factories;

use App\Models\Enrollment;
use App\Models\User;
use App\Models\Course;
use Illuminate\Database\Eloquent\Factories\Factory;

class EnrollmentFactory extends Factory
{
    protected $model = Enrollment::class;

    public function definition(): array
    {
        return [
            'user_id' => User::factory(),
            'course_id' => Course::factory(),
            'amount_paid' => 0,
            'status' => 'in_progress',
        ];
    }

    public function completed(): static
    {
        return $this->state(fn (array $attrs) => [
            'status' => 'completed',
            'completed_at' => now()->subDays(rand(1, 30)),
        ]);
    }

    public function paid(float $amount): static
    {
        return $this->state(fn (array $attrs) => [
            'amount_paid' => $amount,
        ]);
    }
}
