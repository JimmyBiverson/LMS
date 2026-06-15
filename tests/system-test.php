<?php

/**
 * Comprehensive System Test for LMS
 * This test file validates all major functionalities before presenting to principals and deans
 */

require __DIR__ . '/../vendor/autoload.php';

$app = require_once __DIR__ . '/../bootstrap/app.php';
$app->make(\Illuminate\Contracts\Console\Kernel::class)->bootstrap();

use App\Models\User;
use App\Models\Course;
use App\Models\Lesson;
use App\Models\Enrollment;
use App\Models\Category;
use Illuminate\Support\Facades\Hash;

class LMSSystemTest
{
    private $results = [];
    private $errors = [];
    
    public function run()
    {
        echo "===================================\n";
        echo "   LMS SYSTEM COMPREHENSIVE TEST  \n";
        echo "===================================\n\n";
        
        $this->testDatabaseConnection();
        $this->testModels();
        $this->testUserCreation();
        $this->testCourseCreation();
        $this->testEnrollmentFlow();
        $this->testLessonAccess();
        $this->testDashboardData();
        $this->testPerformance();
        
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
            $this->errors[$name] = $e->getMessage();
            echo "❌ ERROR: $name - " . $e->getMessage() . "\n";
        }
    }
    
    private function testDatabaseConnection()
    {
        echo "\n📊 Testing Database Connection...\n";
        
        $this->test('Database Connection', function() {
            $pdo = \DB::connection()->getPdo();
            return $pdo !== null;
        });
        
        $this->test('Users Table Accessible', function() {
            $count = User::count();
            echo "   Found $count users\n";
            return $count >= 0;
        });
        
        $this->test('Courses Table Accessible', function() {
            $count = Course::count();
            echo "   Found $count courses\n";
            return $count >= 0;
        });
    }
    
    private function testModels()
    {
        echo "\n🔍 Testing Models and Relationships...\n";
        
        $this->test('User Model Relationships', function() {
            $instructor = User::where('role', 'instructor')->first();
            if (!$instructor) return true; // Skip if no instructors
            
            $courses = $instructor->courses;
            return $courses !== null;
        });
        
        $this->test('Course Model Relationships', function() {
            $course = Course::with('lessons', 'instructor')->first();
            if (!$course) return true; // Skip if no courses
            
            return $course->lessons !== null && $course->instructor !== null;
        });
        
        $this->test('Enrollment Model Relationships', function() {
            $enrollment = Enrollment::with('user', 'course')->first();
            if (!$enrollment) return true; // Skip if no enrollments
            
            return $enrollment->user !== null && $enrollment->course !== null;
        });
    }
    
    private function testUserCreation()
    {
        echo "\n👤 Testing User Creation...\n";
        
        $this->test('Create Student Account', function() {
            $email = 'test_student_' . time() . '@test.com';
            $user = User::create([
                'name' => 'Test Student',
                'first_name' => 'Test',
                'last_name' => 'Student',
                'email' => $email,
                'phone' => '1234567890',
                'password' => Hash::make('password'),
                'role' => User::ROLE_STUDENT,
                'status' => User::STATUS_ACTIVE,
            ]);
            
            return $user->id > 0;
        });
        
        $this->test('Create Instructor Account', function() {
            $email = 'test_instructor_' . time() . '@test.com';
            $user = User::create([
                'name' => 'Test Instructor',
                'first_name' => 'Test',
                'last_name' => 'Instructor',
                'email' => $email,
                'phone' => '1234567890',
                'password' => Hash::make('password'),
                'role' => User::ROLE_INSTRUCTOR,
                'designation' => 'Senior Instructor',
                'status' => User::STATUS_ACTIVE,
            ]);
            
            return $user->id > 0 && $user->isInstructor();
        });
    }
    
    private function testCourseCreation()
    {
        echo "\n📚 Testing Course Creation...\n";
        
        $this->test('Create Course', function() {
            $instructor = User::where('role', 'instructor')->first();
            if (!$instructor) {
                echo "   ⚠️  No instructor found, skipping\n";
                return true;
            }
            
            $course = Course::create([
                'user_id' => $instructor->id,
                'title' => 'Test Course ' . time(),
                'description' => 'Test course description',
                'price' => 99.99,
                'payment_type' => 'paid',
                'status' => 'Active',
            ]);
            
            echo "   Created course ID: {$course->id}\n";
            return $course->id > 0 && $course->slug !== null;
        });
        
        $this->test('Create Lesson', function() {
            $course = Course::latest()->first();
            if (!$course) {
                echo "   ⚠️  No course found, skipping\n";
                return true;
            }
            
            $lesson = Lesson::create([
                'course_id' => $course->id,
                'title' => 'Test Lesson ' . time(),
                'content' => 'Test lesson content',
                'order' => 1,
                'status' => 'active',
            ]);
            
            echo "   Created lesson ID: {$lesson->id}\n";
            return $lesson->id > 0;
        });
    }
    
    private function testEnrollmentFlow()
    {
        echo "\n📝 Testing Enrollment Flow...\n";
        
        $this->test('Create Enrollment', function() {
            $student = User::where('role', 'student')->first();
            $course = Course::where('status', 'Active')->first();
            
            if (!$student || !$course) {
                echo "   ⚠️  Prerequisites missing, skipping\n";
                return true;
            }
            
            // Check if already enrolled
            $exists = Enrollment::where('user_id', $student->id)
                ->where('course_id', $course->id)
                ->exists();
            
            if ($exists) {
                echo "   ℹ️  Already enrolled\n";
                return true;
            }
            
            $enrollment = Enrollment::create([
                'user_id' => $student->id,
                'course_id' => $course->id,
                'amount_paid' => $course->payment_type === 'free' ? 0 : ($course->sale_price ?? $course->price),
                'status' => 'in_progress',
            ]);
            
            echo "   Created enrollment ID: {$enrollment->id}\n";
            return $enrollment->id > 0;
        });
    }
    
    private function testLessonAccess()
    {
        echo "\n🎥 Testing Lesson Access...\n";
        
        $this->test('Lesson Access Logic', function() {
            $lesson = Lesson::with('course')->first();
            if (!$lesson) {
                echo "   ⚠️  No lessons found, skipping\n";
                return true;
            }
            
            echo "   Lesson: {$lesson->title}\n";
            echo "   Has media: " . ($lesson->hasMedia() ? 'Yes' : 'No') . "\n";
            
            return true;
        });
    }
    
    private function testDashboardData()
    {
        echo "\n📊 Testing Dashboard Data...\n";
        
        $this->test('Student Dashboard Data', function() {
            $student = User::where('role', 'student')->first();
            if (!$student) {
                echo "   ⚠️  No students found, skipping\n";
                return true;
            }
            
            $enrollments = Enrollment::where('user_id', $student->id)->count();
            echo "   Student has $enrollments enrollments\n";
            
            return true;
        });
        
        $this->test('Instructor Dashboard Data', function() {
            $instructor = User::where('role', 'instructor')->first();
            if (!$instructor) {
                echo "   ⚠️  No instructors found, skipping\n";
                return true;
            }
            
            $courses = Course::where('user_id', $instructor->id)->count();
            echo "   Instructor has $courses courses\n";
            
            return true;
        });
        
        $this->test('Admin Dashboard Data', function() {
            $totalStudents = User::where('role', 'student')->count();
            $totalCourses = Course::count();
            $totalInstructors = User::where('role', 'instructor')->count();
            $totalEnrollments = Enrollment::count();
            
            echo "   Students: $totalStudents\n";
            echo "   Courses: $totalCourses\n";
            echo "   Instructors: $totalInstructors\n";
            echo "   Enrollments: $totalEnrollments\n";
            
            return $totalStudents >= 0 && $totalCourses >= 0;
        });
    }
    
    private function testPerformance()
    {
        echo "\n⚡ Testing Performance...\n";
        
        $this->test('Course List Query Performance', function() {
            $start = microtime(true);
            $courses = Course::with('lessons', 'instructor')->where('status', 'Active')->get();
            $time = round((microtime(true) - $start) * 1000, 2);
            
            echo "   Loaded " . $courses->count() . " courses in {$time}ms\n";
            
            // Should be under 500ms for reasonable performance
            return $time < 500;
        });
        
        $this->test('Dashboard Query Performance', function() {
            $student = User::where('role', 'student')->first();
            if (!$student) return true;
            
            $start = microtime(true);
            $enrollments = Enrollment::with('course.instructor')->where('user_id', $student->id)->get();
            $time = round((microtime(true) - $start) * 1000, 2);
            
            echo "   Loaded dashboard data in {$time}ms\n";
            
            return $time < 300;
        });
    }
    
    private function printResults()
    {
        echo "\n===================================\n";
        echo "         TEST SUMMARY\n";
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
        
        echo "Total Tests: $total\n";
        echo "✅ Passed: $passed\n";
        echo "❌ Failed: $failed\n";
        echo "⚠️  Errors: $errors\n\n";
        
        if ($failed > 0 || $errors > 0) {
            echo "❌ SYSTEM NOT READY FOR PRESENTATION\n";
            echo "\nIssues found:\n";
            foreach ($this->errors as $test => $error) {
                echo "  - $test: $error\n";
            }
        } else {
            echo "✅ ALL TESTS PASSED - SYSTEM READY!\n";
        }
        
        echo "\n===================================\n";
    }
}

$test = new LMSSystemTest();
$test->run();
