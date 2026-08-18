<?php

namespace Database\Factories;

use App\Models\Level;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Level>
 */
class LevelFactory extends Factory
{
    protected $model = Level::class;

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $levels = ['Beginner', 'Intermediate', 'Advanced', 'Expert'];
        $name = fake()->randomElement($levels);
        
        return [
            'name' => $name,
            'slug' => Str::slug($name) . '-' . fake()->unique()->randomNumber(3),
            'order' => fake()->numberBetween(1, 10),
        ];
    }
}
