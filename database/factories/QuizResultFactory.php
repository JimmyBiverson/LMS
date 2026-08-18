<?php

namespace Database\Factories;

use App\Models\Quiz;
use App\Models\QuizResult;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

class QuizResultFactory extends Factory
{
    protected $model = QuizResult::class;

    public function definition(): array
    {
        return [
            'quiz_id' => Quiz::factory(),
            'user_id' => User::factory(),
            'score' => $this->faker->numberBetween(0, 100),
            'total_marks' => 100,
            'answers' => [],
            'started_at' => now()->subMinutes(10),
            'completed_at' => now(),
            'passed' => $this->faker->boolean(),
        ];
    }
}
