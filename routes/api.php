<?php

use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\CourseController;
use App\Http\Controllers\Api\EnrollmentController;
use Illuminate\Support\Facades\Route;

// Public
Route::get('courses', [CourseController::class, 'index']);
Route::get('courses/featured', [CourseController::class, 'featured']);
Route::get('courses/{course}', [CourseController::class, 'show']);
Route::get('courses/{course}/lessons', [CourseController::class, 'lessons']);

// Auth
Route::post('auth/register', [AuthController::class, 'register']);
Route::post('auth/login', [AuthController::class, 'login']);

// Protected
Route::middleware('auth:sanctum')->group(function () {
    Route::get('user', [AuthController::class, 'user']);
    Route::post('auth/logout', [AuthController::class, 'logout']);

    Route::get('enrollments', [EnrollmentController::class, 'index']);
    Route::post('enrollments/{course}', [EnrollmentController::class, 'enroll']);
    Route::get('enrollments/{course}/progress', [EnrollmentController::class, 'progress']);
    Route::post('lessons/{lesson}/complete', [EnrollmentController::class, 'completeLesson']);

    Route::get('analytics', [\App\Http\Controllers\Api\AnalyticsController::class, 'index']);
});
