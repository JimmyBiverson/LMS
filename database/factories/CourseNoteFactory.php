<?php

namespace Database\Factories;

use App\Models\Course;
use App\Models\CourseNote;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

class CourseNoteFactory extends Factory
{
    protected $model = CourseNote::class;

    public function definition(): array
    {
        return [
            'course_id' => Course::factory(),
            'user_id' => User::factory(),
            'title' => $this->faker->sentence(4),
            'summary' => $this->faker->paragraph(),
            'content' => '<p>' . $this->faker->paragraph() . '</p>',
            'display_order' => 1,
            'status' => 'published',
            'allow_download' => true,
        ];
    }
}
