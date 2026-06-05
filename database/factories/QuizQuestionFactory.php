<?php

namespace Database\Factories;

use App\Models\QuizQuestion;
use App\Models\Quiz;
use Illuminate\Database\Eloquent\Factories\Factory;

class QuizQuestionFactory extends Factory
{
    protected $model = QuizQuestion::class;

    public function definition(): array
    {
        $options = [
            fake()->sentence(4),
            fake()->sentence(4),
            fake()->sentence(4),
            fake()->sentence(4),
        ];
        return [
            'quiz_id' => Quiz::factory(),
            'question' => fake()->sentence() . '?',
            'type' => 'multiple_choice',
            'options' => $options,
            'correct_answer' => $options[0],
            'marks' => fake()->randomElement([5, 10, 15, 20]),
            'order' => 1,
        ];
    }
}
