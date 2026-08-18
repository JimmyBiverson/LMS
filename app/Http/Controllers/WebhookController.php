<?php

namespace App\Http\Controllers;

use App\Models\Course;
use App\Models\Enrollment;
use App\Models\User;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class WebhookController extends Controller
{
    public function handle(Request $request): JsonResponse
    {
        $payload = $request->all();
        $event = $request->header('X-Webhook-Event', 'unknown');

        logger()->info('Webhook received', ['event' => $event, 'payload' => $payload]);

        match ($event) {
            'user.created' => $this->handleUserCreated($payload),
            'enrollment.created' => $this->handleEnrollmentCreated($payload),
            'course.updated' => $this->handleCourseUpdated($payload),
            default => logger()->warning('Unknown webhook event: ' . $event),
        };

        return response()->json(['status' => 'received']);
    }

    private function handleUserCreated(array $payload): void
    {
        // External system created a user
        logger()->info('Webhook: user.created', $payload);
    }

    private function handleEnrollmentCreated(array $payload): void
    {
        // External system created an enrollment
        logger()->info('Webhook: enrollment.created', $payload);
    }

    private function handleCourseUpdated(array $payload): void
    {
        // External system notified about course update
        logger()->info('Webhook: course.updated', $payload);
    }
}
