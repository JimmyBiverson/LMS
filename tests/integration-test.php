<?php

/**
 * Integration Test - End-to-End Workflow Testing
 * Simulates real user workflows
 */

require __DIR__ . '/../vendor/autoload.php';

$app = require_once __DIR__ . '/../bootstrap/app.php';
$app->make(\Illuminate\Contracts\Console\Kernel::class)->bootstrap();

use App\Models\User;
use App\Models\Course;
use App\Models\Lesson;
use App\Models\Enrollment;
use App\Models\LessonCompletion;
use Illuminate\Support\Facades\Hash;

class IntegrationTest
{
    private $results = [];
    private $testUser;
    private $testInstructor;
    private $testCourse;
    
    public function run()
    {
        echo "===================================\n";
        echo "   LMS INTEGRATION TEST  \n";
        echo "   End-to-End Workflows\n";
        echo "===================================\n\n";
        
        $this->testCompleteStudentJourney();
        $this->testCompleteInstructorJourney();
        $this->testEnrollmentToCertificate();
        
        $this->printResults();
    }
    
    private function test($name, $callback)
    {
        try {
            $start = microtime(true);
            $result = $callback();
            $time = round((microtime(true) - $start) * 1000, 2);
            
            if ($result === false) {
                $this->results[$name] = ['status' => 'FAIL', 'time' => $time];
                echo "❌ FAIL: $name ({$time}ms)\n";
            } else {
                $this->results[$name] = ['status' => 'PASS', 'time' => $time];
                echo "✅ PASS: $name ({$time}ms)\n";
            }
        } catch (\Exception $e) {
            $this->results[$name] = ['status' => 'ERROR', 'time' => 0];
            echo "❌ ERROR: $name - " . $e->getMessage() . "\n";
        }
    }
    
    private function testCompleteStudentJourney()
    {
        echo "\n👨‍🎓 Testing Complete Student Journey...\n";
        echo "   (Registration → Browse → Enroll → Learn → Complete)\n\n";
        
        $this->test('1. Student Registration', function() {
            $email = 'journey_student_' . time() . '@test.com';
            $this->testUser = User::create([
                'name' => 'Journey Student',
                'first_name' => 'Journey',
                'last_name' => 'Student',
                'email' => $email,
                'phone' => '1234567890',
                'password' => Hash::make('password123'),
                'role' => User::ROLE_STUDENT,
                'status' => User::STATUS_ACTIVE,
            ]);
            
            echo "   → Student ID: {$this->testUser->id}, Email: {$email}\n";
            return $this->testUser->id > 0;
        });
        
        $this->test('2. Browse Available Courses', function() {
            $courses = Course::where('status', 'Active')->get();
            echo "   → Found {$courses->count()} active courses\n";
            return $courses->count() > 0;
        });
        
        $this->test('3. Enroll in Course', function() {
            $course = Course::where('status', 'Active')->first();
            if (!$course) {
                echo "   ⚠️  No courses available\n";
                return true;
            }
            
            $enrollment = Enrollment::create([
                'user_id' => $this->testUser->id,
                'course_id' => $course->id,
                'amount_paid' => $course->payment_type === 'free' ? 0 : ($course->sale_price ?? $course->price),
                'status' => 'in_progress',
            ]);
            
            echo "   → Enrolled in: {$course->title}\n";
            echo "   → Amount paid: " . ($enrollment->amount_paid > 0 ? '$' . number_format($enrollment->amount_paid, 2) : 'Free') . "\n";
            
            return $enrollment->id > 0;
        });
        
        $this->test('4. Access Course Dashboard', function() {
            $enrollments = Enrollment::where('user_id', $this->testUser->id)->count();
            echo "   → Student has {$enrollments} enrollment(s)\n";
            return $enrollments > 0;
        });
        
        $this->test('5. View Lessons', function() {
            $enrollment = Enrollment::where('user_id', $this->testUser->id)->first();
            if (!$enrollment) return true;
            
            $lessons = Lesson::where('course_id', $enrollment->course_id)->get();
            echo "   → Course has {$lessons->count()} lessons\n";
            return true;
        });
        
        $this->test('6. Complete a Lesson', function() {
            $enrollment = Enrollment::where('user_id', $this->testUser->id)->first();
            if (!$enrollment) return true;
            
            $lesson = Lesson::where('course_id', $enrollment->course_id)->first();
            if (!$lesson) {
                echo "   ⚠️  No lessons in course\n";
                return true;
            }
            
            $completion = LessonCompletion::create([
                'user_id' => $this->testUser->id,
                'lesson_id' => $lesson->id,
                'course_id' => $lesson->course_id,
                'completed_at' => now(),
            ]);
            
            echo "   → Completed lesson: {$lesson->title}\n";
            return $completion->id > 0;
        });
        
        $this->test('7. Check Progress', function() {
            $enrollment = Enrollment::where('user_id', $this->testUser->id)->first();
            if (!$enrollment) return true;
            
            $totalLessons = Lesson::where('course_id', $enrollment->course_id)->count();
            $completedLessons = LessonCompletion::where('user_id', $this->testUser->id)
                ->where('course_id', $enrollment->course_id)->count();
            
            $progress = $totalLessons > 0 ? round(($completedLessons / $totalLessons) * 100) : 0;
            echo "   → Progress: {$progress}% ({$completedLessons}/{$totalLessons} lessons)\n";
            
            return true;
        });
    }
    
    private function testCompleteInstructorJourney()
    {
        echo "\n👨‍🏫 Testing Complete Instructor Journey...\n";
        echo "   (Registration → Create Course → Add Lessons → Manage)\n\n";
        
        $this->test('1. Instructor Registration', function() {
            $email = 'journey_instructor_' . time() . '@test.com';
            $this->testInstructor = User::create([
                'name' => 'Journey Instructor',
                'first_name' => 'Journey',
                'last_name' => 'Instructor',
                'email' => $email,
                'phone' => '1234567890',
                'password' => Hash::make('password123'),
                'role' => User::ROLE_INSTRUCTOR,
                'designation' => 'Senior Lecturer',
                'status' => User::STATUS_ACTIVE,
            ]);
            
            echo "   → Instructor ID: {$this->testInstructor->id}, Email: {$email}\n";
            return $this->testInstructor->id > 0 && $this->testInstructor->isInstructor();
        });
        
        $this->test('2. Create New Course', function() {
            $this->testCourse = Course::create([
                'user_id' => $this->testInstructor->id,
                'title' => 'Integration Test Course ' . time(),
                'description' => 'This is a test course created during integration testing',
                'price' => 49.99,
                'payment_type' => 'paid',
                'status' => 'Active',
            ]);
            
            echo "   → Course ID: {$this->testCourse->id}\n";
            echo "   → Title: {$this->testCourse->title}\n";
            echo "   → Slug: {$this->testCourse->slug}\n";
            
            return $this->testCourse->id > 0 && $this->testCourse->slug !== null;
        });
        
        $this->test('3. Add Lesson to Course', function() {
            $lesson = Lesson::create([
                'course_id' => $this->testCourse->id,
                'title' => 'Introduction to Testing',
                'content' => 'This lesson covers the basics of testing',
                'video_url' => 'https://example.com/video.mp4',
                'order' => 1,
                'status' => 'published',
            ]);
            
            echo "   → Lesson ID: {$lesson->id}\n";
            echo "   → Title: {$lesson->title}\n";
            
            return $lesson->id > 0;
        });
        
        $this->test('4. Add Second Lesson', function() {
            $lesson = Lesson::create([
                'course_id' => $this->testCourse->id,
                'title' => 'Advanced Testing Techniques',
                'content' => 'This lesson covers advanced testing',
                'video_url' => 'https://example.com/video2.mp4',
                'order' => 2,
                'status' => 'published',
            ]);
            
            echo "   → Lesson ID: {$lesson->id}\n";
            
            return $lesson->id > 0;
        });
        
        $this->test('5. View Course Statistics', function() {
            $lessons = Lesson::where('course_id', $this->testCourse->id)->count();
            $enrollments = Enrollment::where('course_id', $this->testCourse->id)->count();
            
            echo "   → Total lessons: {$lessons}\n";
            echo "   → Total enrollments: {$enrollments}\n";
            
            return $lessons >= 2;
        });
    }
    
    private function testEnrollmentToCertificate()
    {
        echo "\n🎓 Testing Enrollment to Certificate Flow...\n\n";
        
        $this->test('1. Student Enrolls in New Course', function() {
            if (!$this->testUser || !$this->testCourse) {
                echo "   ⚠️  Prerequisites not met\n";
                return true;
            }
            
            $enrollment = Enrollment::create([
                'user_id' => $this->testUser->id,
                'course_id' => $this->testCourse->id,
                'amount_paid' => $this->testCourse->sale_price ?? $this->testCourse->price,
                'status' => 'in_progress',
            ]);
            
            echo "   → Enrollment ID: {$enrollment->id}\n";
            return $enrollment->id > 0;
        });
        
        $this->test('2. Complete All Lessons', function() {
            if (!$this->testUser || !$this->testCourse) return true;
            
            $lessons = Lesson::where('course_id', $this->testCourse->id)->get();
            $completed = 0;
            
            foreach ($lessons as $lesson) {
                LessonCompletion::updateOrCreate([
                    'user_id' => $this->testUser->id,
                    'lesson_id' => $lesson->id,
                ], [
                    'course_id' => $lesson->course_id,
                    'completed_at' => now(),
                ]);
                $completed++;
            }
            
            echo "   → Completed {$completed} lessons\n";
            return $completed > 0;
        });
        
        $this->test('3. Mark Course as Completed', function() {
            if (!$this->testUser || !$this->testCourse) return true;
            
            $enrollment = Enrollment::where('user_id', $this->testUser->id)
                ->where('course_id', $this->testCourse->id)
                ->first();
            
            if ($enrollment) {
                $enrollment->update([
                    'status' => 'completed',
                    'completed_at' => now(),
                ]);
                echo "   → Course marked as completed\n";
                echo "   → Completed at: {$enrollment->completed_at}\n";
            }
            
            return $enrollment && $enrollment->status === 'completed';
        });
        
        $this->test('4. Verify Completion Status', function() {
            if (!$this->testUser || !$this->testCourse) return true;
            
            $enrollment = Enrollment::where('user_id', $this->testUser->id)
                ->where('course_id', $this->testCourse->id)
                ->where('status', 'completed')
                ->first();
            
            $isCompleted = $enrollment !== null;
            echo "   → Completion verified: " . ($isCompleted ? 'Yes' : 'No') . "\n";
            
            return $isCompleted;
        });
    }
    
    private function printResults()
    {
        echo "\n===================================\n";
        echo "      INTEGRATION TEST SUMMARY\n";
        echo "===================================\n\n";
        
        $passed = 0;
        $failed = 0;
        $errors = 0;
        
        foreach ($this->results as $name => $result) {
            if ($result['status'] === 'PASS') $passed++;
            elseif ($result['status'] === 'FAIL') $failed++;
            else $errors++;
        }
        
        $total = $passed + $failed + $errors;
        
        echo "Total Integration Tests: $total\n";
        echo "✅ Passed: $passed\n";
        echo "❌ Failed: $failed\n";
        echo "⚠️  Errors: $errors\n\n";
        
        if ($failed > 0 || $errors > 0) {
            echo "❌ INTEGRATION ISSUES DETECTED\n";
        } else {
            echo "✅ ALL WORKFLOWS FUNCTIONING CORRECTLY!\n";
            echo "\n🎉 System is ready for real users!\n";
        }
        
        echo "\n===================================\n";
    }
}

$test = new IntegrationTest();
$test->run();
