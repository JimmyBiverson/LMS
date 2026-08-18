<?php

namespace Database\Factories;

use App\Models\Review;
use App\Models\User;
use App\Models\Course;
use Illuminate\Database\Eloquent\Factories\Factory;

class ReviewFactory extends Factory
{
    protected $model = Review::class;

    public function definition(): array
    {
        return [
            'user_id' => User::factory(),
            'course_id' => Course::factory(),
            'rating' => fake()->numberBetween(3, 5),
            'review' => fake()->paragraph(),
            'is_approved' => fake()->boolean(80),
        ];
    }
}
