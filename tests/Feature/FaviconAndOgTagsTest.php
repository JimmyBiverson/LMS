<?php

namespace Tests\Feature;

use App\Models\SchoolSetting;
use Database\Seeders\DatabaseSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class FaviconAndOgTagsTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        $this->seed(DatabaseSeeder::class);
    }

    public function test_page_renders_favicon_and_og_meta_tags(): void
    {
        $school = SchoolSetting::getInstance();
        $school->school_name = 'Test University';
        $school->favicon = 'settings/test-favicon.png';
        $school->site_logo = 'settings/test-logo.png';
        $school->save();

        $response = $this->get('/');
        $response->assertStatus(200);

        // Check favicon is dynamically outputted on app index
        $response->assertSee('rel="icon"', false);
        $response->assertSee($school->favicon, false);

        // Check Open Graph preview tags are outputted correctly
        $response->assertSee('<meta property="og:title" content="Home - Test University">', false);
        $response->assertSee('<meta property="og:description" content="Discover, learn, and thrive with us. Experience a smooth and rewarding educational adventure.">', false);
        $response->assertSee('property="og:image"', false);
        $response->assertSee($school->site_logo, false);
        $response->assertSee('name="twitter:card" content="summary_large_image"', false);
        $response->assertSee('name="twitter:title" content="Home - Test University">', false);
        $response->assertSee($school->favicon, false);
    }

    public function test_admin_dashboard_renders_metadata_correctly(): void
    {
        $school = SchoolSetting::getInstance();
        $school->school_name = 'Test University Admin';
        $school->favicon = 'settings/test-admin-favicon.svg';
        $school->save();

        // Login as admin
        $response = $this->post('/admin/admin-login', [
            'email' => 'admin@gmail.com',
            'password' => 'Password123@',
        ]);
        $response->assertRedirect(route('admin.dashboard.dashboard'));
        $this->assertAuthenticated();

        // Request dashboard
        $dashboardResponse = $this->get(route('admin.dashboard.dashboard'));
        $dashboardResponse->assertStatus(200);

        // Check favicon references
        $dashboardResponse->assertSee($school->favicon, false);
        $dashboardResponse->assertSee('property="og:title"', false);
    }
}
