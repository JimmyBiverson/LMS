<?php

namespace Tests\Feature;

use App\Models\User;
use Database\Seeders\DatabaseSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Hash;
use Tests\TestCase;

class AuthenticationFlowTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        $this->seed(DatabaseSeeder::class);
    }

    public function test_admin_login_succeeds_and_redirects_to_admin(): void
    {
        $response = $this->post('/admin/admin-login', [
            'email' => 'admin@gmail.com',
            'password' => 'Password123@',
        ]);

        $response->assertRedirect(route('admin.dashboard.dashboard'));
        $this->assertAuthenticated();
    }

    public function test_student_login_redirects_to_student_dashboard(): void
    {
        $response = $this->post('/login', [
            'email' => 'student@edulab.test',
            'password' => 'password',
            'selected_role' => 'student',
        ]);

        $response->assertRedirect(route('dashboard'));
        $this->assertAuthenticated();
    }

    public function test_instructor_login_redirects_to_instructor_dashboard(): void
    {
        $response = $this->post('/login', [
            'email' => 'instructor@edulab.test',
            'password' => 'password',
            'selected_role' => 'instructor',
        ]);

        $response->assertRedirect(route('instructor.dashboard.dashboard'));
        $this->assertAuthenticated();
    }

    public function test_specific_instructor_gmail_login_redirects_to_instructor_dashboard(): void
    {
        $response = $this->post('/login', [
            'email' => 'instructor@gmail.com',
            'password' => '12345654321',
            'selected_role' => 'instructor',
        ]);

        $response->assertRedirect(route('instructor.dashboard.dashboard'));
        $this->assertAuthenticated();
    }

    public function test_specific_org_gmail_login_redirects_to_org_dashboard(): void
    {
        $response = $this->post('/login', [
            'email' => 'org@gmail.com',
            'password' => '12345654321',
            'selected_role' => 'organization',
        ]);

        $response->assertRedirect(route('org.dashboard.dashboard'));
        $this->assertAuthenticated();
    }

    public function test_instructor_login_ignores_student_intended_url(): void
    {
        session()->put('url.intended', 'http://localhost/dashboard');

        $response = $this->post('/login', [
            'email' => 'instructor@edulab.test',
            'password' => 'password',
            'selected_role' => 'instructor',
        ]);

        $response->assertRedirect(route('instructor.dashboard.dashboard'));
        $this->assertAuthenticated();
    }

    public function test_instructor_login_respects_instructor_intended_url(): void
    {
        session()->put('url.intended', 'http://localhost/instructor/courses');

        $response = $this->post('/login', [
            'email' => 'instructor@edulab.test',
            'password' => 'password',
            'selected_role' => 'instructor',
        ]);

        $response->assertRedirect('http://localhost/instructor/courses');
        $this->assertAuthenticated();
    }

    public function test_failed_login_persists_selected_role(): void
    {
        $response = $this->post('/login', [
            'email' => 'nonexistent@edulab.test',
            'password' => 'wrongpassword',
            'selected_role' => 'instructor',
        ]);

        $response->assertRedirect();
        $response->assertSessionHasErrors('email');
        $this->assertEquals('instructor', session()->get('_old_input')['selected_role']);
    }

    public function test_student_registration_redirects_to_student_dashboard(): void
    {
        $response = $this->post('/register', [
            'first_name' => 'Jane',
            'last_name' => 'Student',
            'email' => 'jane.student@lms.test',
            'phone' => '+12345678912',
            'password' => 'Password123@',
            'password_confirmation' => 'Password123@',
            'role' => User::ROLE_STUDENT,
        ]);

        $response->assertRedirect(route('dashboard'));
        $this->assertAuthenticated();
    }

    public function test_instructor_registration_redirects_to_instructor_dashboard(): void
    {
        $response = $this->post('/register', [
            'first_name' => 'Bob',
            'last_name' => 'Instructor',
            'email' => 'bob.instructor@lms.test',
            'phone' => '+12345678913',
            'password' => 'Password123@',
            'password_confirmation' => 'Password123@',
            'role' => User::ROLE_INSTRUCTOR,
            'designation' => 'Web Design Specialist',
        ]);

        $response->assertRedirect(route('instructor.dashboard.dashboard'));
        $this->assertAuthenticated();
    }

    public function test_org_registration_redirects_to_org_dashboard(): void
    {
        $response = $this->post('/register', [
            'name' => 'EduCorp',
            'email' => 'educorp@lms.test',
            'phone' => '+12345678914',
            'password' => 'Password123@',
            'password_confirmation' => 'Password123@',
            'role' => User::ROLE_ORGANIZATION,
            'address' => 'Kampala, Uganda',
        ]);

        $response->assertRedirect(route('org.dashboard.dashboard'));
        $this->assertAuthenticated();
    }

    public function test_profile_update_works(): void
    {
        $user = User::where('email', 'student@edulab.test')->first();

        $response = $this->actingAs($user)->post('/profile', [
            'first_name' => 'Updated',
            'last_name' => 'Name',
            'name' => 'Updated Name',
            'phone' => '+1111111111',
            'address' => 'New Address',
        ]);

        $response->assertSessionHas('success');
        $user->refresh();
        $this->assertEquals('Updated', $user->first_name);
        $this->assertEquals('Name', $user->last_name);
        $this->assertEquals('+1111111111', $user->phone);
    }
}
