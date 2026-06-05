<?php

namespace Database\Factories;

use App\Models\Lesson;
use App\Models\Course;
use Illuminate\Database\Eloquent\Factories\Factory;

class LessonFactory extends Factory
{
    protected $model = Lesson::class;

    public function definition(): array
    {
        return [
            'course_id' => Course::factory(),
            'title' => fake()->sentence(4),
            'content' => fake()->paragraphs(3, true),
            'video_url' => fake()->randomElement([
                'https://www.w3schools.com/html/mov_bbb.mp4',
                'https://sample-videos.com/video321/mp4/240/big_buck_bunny_240p_1mb.mp4',
            ]),
            'duration' => fake()->randomElement(['10:00', '15:30', '20:00', '25:45', '30:00']),
            'order' => 0,
            'is_free_preview' => fake()->boolean(20),
            'status' => 'published',
        ];
    }

    public function freePreview(): static
    {
        return $this->state(fn (array $attrs) => ['is_free_preview' => true]);
    }
}
