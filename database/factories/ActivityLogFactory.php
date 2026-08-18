<?php

namespace Database\Factories;

use App\Models\ActivityLog;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

class ActivityLogFactory extends Factory
{
    protected $model = ActivityLog::class;

    public function definition(): array
    {
        $actions = [
            'user.login',
            'user.logout',
            'course.viewed',
            'course.enrolled',
            'lesson.completed',
            'assignment.submitted',
            'assignment.graded',
            'quiz.started',
            'quiz.completed',
            'profile.updated',
            'certificate.generated',
        ];

        return [
            'user_id' => User::factory(),
            'action' => fake()->randomElement($actions),
            'subject_type' => null,
            'subject_id' => null,
            'metadata' => null,
            'ip_address' => fake()->ipv4(),
            'user_agent' => fake()->userAgent(),
            'created_at' => fake()->dateTimeBetween('-1 month', 'now'),
        ];
    }

    /**
     * Indicate that the activity log has metadata.
     */
    public function withMetadata(array $metadata = null): static
    {
        return $this->state(fn (array $attrs) => [
            'metadata' => $metadata ?? [
                'duration' => fake()->numberBetween(1, 3600),
                'source' => fake()->randomElement(['web', 'mobile', 'api']),
            ],
        ]);
    }

    /**
     * Indicate that the activity log has a subject.
     */
    public function withSubject(string $subjectType, int $subjectId): static
    {
        return $this->state(fn (array $attrs) => [
            'subject_type' => $subjectType,
            'subject_id' => $subjectId,
        ]);
    }

    /**
     * Indicate that the activity happened today.
     */
    public function today(): static
    {
        return $this->state(fn (array $attrs) => [
            'created_at' => now(),
        ]);
    }

    /**
     * Indicate that the activity happened yesterday.
     */
    public function yesterday(): static
    {
        return $this->state(fn (array $attrs) => [
            'created_at' => now()->subDay(),
        ]);
    }

    /**
     * Indicate that the activity happened last week.
     */
    public function lastWeek(): static
    {
        return $this->state(fn (array $attrs) => [
            'created_at' => now()->subWeek(),
        ]);
    }
}
