<?php

namespace Tests\Unit;

use App\Models\User;
use App\Services\ProfileManagementService;
use Exception;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

class ProfileManagementServiceTest extends TestCase
{
    use RefreshDatabase;

    private ProfileManagementService $service;
    private User $user;

    protected function setUp(): void
    {
        parent::setUp();

        $this->service = new ProfileManagementService();
        
        // Create a test user
        $this->user = User::create([
            'name' => 'Test User',
            'first_name' => 'Test',
            'last_name' => 'User',
            'email' => 'test@example.com',
            'password' => Hash::make('password123'),
            'role' => User::ROLE_STUDENT,
            'status' => User::STATUS_ACTIVE,
        ]);

        // Fake storage for file uploads
        Storage::fake('public');
    }

    // ========== Profile Update Tests ==========

    public function test_updates_profile_successfully(): void
    {
        $data = [
            'first_name' => 'Updated',
            'last_name' => 'Name',
            'phone' => '123-456-7890',
            'bio' => 'This is my bio',
            'designation' => 'Software Developer',
        ];

        $updatedUser = $this->service->updateProfile($this->user, $data);

        $this->assertEquals('Updated', $updatedUser->first_name);
        $this->assertEquals('Name', $updatedUser->last_name);
        $this->assertEquals('123-456-7890', $updatedUser->phone);
        $this->assertEquals('This is my bio', $updatedUser->bio);
        $this->assertEquals('Software Developer', $updatedUser->designation);
    }

    public function test_updates_profile_with_partial_data(): void
    {
        $this->user->update(['bio' => 'Original bio']);

        $data = [
            'first_name' => 'NewFirst',
        ];

        $updatedUser = $this->service->updateProfile($this->user, $data);

        $this->assertEquals('NewFirst', $updatedUser->first_name);
        $this->assertEquals('User', $updatedUser->last_name); // Unchanged
        $this->assertEquals('Original bio', $updatedUser->bio); // Unchanged
    }

    public function test_ignores_non_updatable_fields(): void
    {
        $data = [
            'first_name' => 'Updated',
            'email' => 'newemail@example.com', // Should be ignored
            'role' => User::ROLE_ADMIN, // Should be ignored
            'password' => 'newpassword', // Should be ignored
        ];

        $updatedUser = $this->service->updateProfile($this->user, $data);

        $this->assertEquals('Updated', $updatedUser->first_name);
        $this->assertEquals('test@example.com', $updatedUser->email); // Unchanged
        $this->assertEquals(User::ROLE_STUDENT, $updatedUser->role); // Unchanged
        $this->assertTrue(Hash::check('password123', $updatedUser->password)); // Unchanged
    }

    public function test_returns_fresh_user_after_update(): void
    {
        $data = ['first_name' => 'Fresh'];

        $updatedUser = $this->service->updateProfile($this->user, $data);

        // Verify it's a fresh instance from database
        $this->assertEquals('Fresh', $updatedUser->first_name);
        $this->assertNotSame($this->user, $updatedUser);
    }

    // ========== Password Update Tests ==========

    public function test_updates_password_successfully(): void
    {
        $result = $this->service->updatePassword(
            $this->user,
            'password123',
            'newpassword456'
        );

        $this->assertTrue($result);
        
        // Verify new password works
        $freshUser = User::find($this->user->id);
        $this->assertTrue(Hash::check('newpassword456', $freshUser->password));
    }

    public function test_fails_with_incorrect_current_password(): void
    {
        $this->expectException(Exception::class);
        $this->expectExceptionMessage('Current password is incorrect');

        $this->service->updatePassword(
            $this->user,
            'wrongpassword',
            'newpassword456'
        );
    }

    public function test_fails_with_short_new_password(): void
    {
        $this->expectException(Exception::class);
        $this->expectExceptionMessage('New password must be at least 8 characters');

        $this->service->updatePassword(
            $this->user,
            'password123',
            'short'
        );
    }

    public function test_old_password_stops_working_after_change(): void
    {
        $this->service->updatePassword(
            $this->user,
            'password123',
            'newpassword456'
        );

        $freshUser = User::find($this->user->id);
        $this->assertFalse(Hash::check('password123', $freshUser->password));
    }

    // ========== Profile Image Upload Tests ==========

    public function test_uploads_profile_image_successfully(): void
    {
        $image = UploadedFile::fake()->image('profile.jpg', 600, 600);

        $path = $this->service->uploadProfileImage($this->user, $image);

        $this->assertNotNull($path);
        $this->assertStringContainsString('profiles/images/', $path);
        Storage::disk('public')->assertExists($path);

        // Verify user record is updated
        $freshUser = User::find($this->user->id);
        $this->assertEquals($path, $freshUser->profile_image);
    }

    public function test_deletes_old_image_when_uploading_new_one(): void
    {
        // Upload first image
        $image1 = UploadedFile::fake()->image('profile1.jpg');
        $path1 = $this->service->uploadProfileImage($this->user, $image1);
        Storage::disk('public')->assertExists($path1);

        // Upload second image
        $image2 = UploadedFile::fake()->image('profile2.jpg');
        $path2 = $this->service->uploadProfileImage($this->user, $image2);

        // Old image should be deleted
        Storage::disk('public')->assertMissing($path1);
        // New image should exist
        Storage::disk('public')->assertExists($path2);
    }

    public function test_fails_to_upload_non_image_file(): void
    {
        $file = UploadedFile::fake()->create('document.pdf', 100);

        $this->expectException(Exception::class);
        $this->expectExceptionMessage('File must be a valid image');

        $this->service->uploadProfileImage($this->user, $file);
    }

    public function test_fails_to_upload_oversized_image(): void
    {
        // Create a 6MB image (over the 5MB limit)
        $image = UploadedFile::fake()->image('large.jpg')->size(6144);

        $this->expectException(Exception::class);
        $this->expectExceptionMessage('Image size must be less than 5MB');

        $this->service->uploadProfileImage($this->user, $image);
    }

    public function test_accepts_different_image_formats(): void
    {
        $formats = ['jpg', 'png', 'webp'];

        foreach ($formats as $format) {
            $user = User::factory()->create();
            $image = UploadedFile::fake()->image("profile.{$format}");

            $path = $this->service->uploadProfileImage($user, $image);

            $this->assertNotNull($path);
            Storage::disk('public')->assertExists($path);
        }
    }

    public function test_generates_unique_filenames_for_images(): void
    {
        $user1 = User::factory()->create();
        $user2 = User::factory()->create();

        $image1 = UploadedFile::fake()->image('profile.jpg');
        $image2 = UploadedFile::fake()->image('profile.jpg');

        $path1 = $this->service->uploadProfileImage($user1, $image1);
        $path2 = $this->service->uploadProfileImage($user2, $image2);

        $this->assertNotEquals($path1, $path2);
    }

    // ========== Profile Image Deletion Tests ==========

    public function test_deletes_profile_image_successfully(): void
    {
        // Upload an image first
        $image = UploadedFile::fake()->image('profile.jpg');
        $path = $this->service->uploadProfileImage($this->user, $image);
        Storage::disk('public')->assertExists($path);

        // Delete the image
        $result = $this->service->deleteProfileImage($this->user);

        $this->assertTrue($result);
        Storage::disk('public')->assertMissing($path);

        // Verify user record is updated
        $freshUser = User::find($this->user->id);
        $this->assertNull($freshUser->profile_image);
    }

    public function test_returns_false_when_deleting_non_existent_image(): void
    {
        $result = $this->service->deleteProfileImage($this->user);

        $this->assertFalse($result);
    }

    public function test_handles_missing_file_gracefully_during_deletion(): void
    {
        // Set profile_image without actually creating the file
        $this->user->update(['profile_image' => 'profiles/images/nonexistent.jpg']);

        $result = $this->service->deleteProfileImage($this->user);

        $this->assertTrue($result);
        
        // Verify user record is cleared
        $freshUser = User::find($this->user->id);
        $this->assertNull($freshUser->profile_image);
    }

    // ========== Preferences Management Tests ==========

    public function test_updates_preferences_successfully(): void
    {
        $preferences = [
            'theme' => 'dark',
            'language' => 'en',
            'notifications' => true,
        ];

        $this->service->updatePreferences($this->user, $preferences);

        $freshUser = User::find($this->user->id);
        $this->assertEquals('dark', $freshUser->preferences['theme']);
        $this->assertEquals('en', $freshUser->preferences['language']);
        $this->assertTrue($freshUser->preferences['notifications']);
    }

    public function test_merges_preferences_with_existing_ones(): void
    {
        // Set initial preferences
        $this->user->update([
            'preferences' => [
                'theme' => 'light',
                'language' => 'en',
            ]
        ]);

        // Update with new preferences
        $newPreferences = [
            'theme' => 'dark', // Update existing
            'notifications' => true, // Add new
        ];

        $this->service->updatePreferences($this->user, $newPreferences);

        $freshUser = User::find($this->user->id);
        $this->assertEquals('dark', $freshUser->preferences['theme']); // Updated
        $this->assertEquals('en', $freshUser->preferences['language']); // Preserved
        $this->assertTrue($freshUser->preferences['notifications']); // Added
    }

    public function test_handles_null_existing_preferences(): void
    {
        $this->user->update(['preferences' => null]);

        $preferences = ['theme' => 'dark'];

        $this->service->updatePreferences($this->user, $preferences);

        $freshUser = User::find($this->user->id);
        $this->assertEquals('dark', $freshUser->preferences['theme']);
    }

    public function test_updates_empty_preferences_array(): void
    {
        $this->service->updatePreferences($this->user, []);

        $freshUser = User::find($this->user->id);
        $this->assertIsArray($freshUser->preferences);
    }

    // ========== Get Profile Tests ==========

    public function test_gets_complete_profile_information(): void
    {
        $this->user->update([
            'first_name' => 'John',
            'last_name' => 'Doe',
            'phone' => '123-456-7890',
            'bio' => 'Test bio',
            'designation' => 'Developer',
            'preferences' => ['theme' => 'dark'],
        ]);

        $profile = $this->service->getProfile($this->user);

        $this->assertEquals($this->user->id, $profile['id']);
        $this->assertEquals('John', $profile['first_name']);
        $this->assertEquals('Doe', $profile['last_name']);
        $this->assertEquals('John Doe', $profile['full_name']);
        $this->assertEquals('test@example.com', $profile['email']);
        $this->assertEquals('123-456-7890', $profile['phone']);
        $this->assertEquals(User::ROLE_STUDENT, $profile['role']);
        $this->assertEquals('Developer', $profile['designation']);
        $this->assertEquals('Test bio', $profile['bio']);
        $this->assertEquals(User::STATUS_ACTIVE, $profile['status']);
        $this->assertEquals(['theme' => 'dark'], $profile['preferences']);
    }

    public function test_get_profile_includes_image_url(): void
    {
        $image = UploadedFile::fake()->image('profile.jpg');
        $this->service->uploadProfileImage($this->user, $image);

        $profile = $this->service->getProfile($this->user);

        $this->assertNotNull($profile['profile_image']);
        $this->assertNotNull($profile['profile_image_url']);
        $this->assertStringContainsString('storage', $profile['profile_image_url']);
    }

    // ========== Profile Data Validation Tests ==========

    public function test_validates_and_sanitizes_profile_data(): void
    {
        $data = [
            'first_name' => '  John  ',
            'last_name' => '  Doe  ',
            'phone' => '(123) 456-7890',
            'bio' => '  This is my bio  ',
        ];

        $validated = $this->service->validateProfileData($data, $this->user);

        $this->assertEquals('John', $validated['first_name']);
        $this->assertEquals('Doe', $validated['last_name']);
        $this->assertEquals('(123) 456-7890', $validated['phone']);
        $this->assertEquals('This is my bio', $validated['bio']);
    }

    public function test_validates_phone_number_format(): void
    {
        $data = [
            'phone' => '123-456-7890 ext. 123',
        ];

        $validated = $this->service->validateProfileData($data, $this->user);

        // Should remove non-numeric characters except allowed ones
        $this->assertNotEmpty($validated['phone']);
        $this->assertStringNotContainsString('ext', $validated['phone']);
    }

    public function test_truncates_bio_to_max_length(): void
    {
        $longBio = str_repeat('a', 1500);
        $data = ['bio' => $longBio];

        $validated = $this->service->validateProfileData($data, $this->user);

        $this->assertEquals(1000, strlen($validated['bio']));
    }

    // ========== Permission Tests ==========

    public function test_can_update_profile_for_active_user(): void
    {
        $result = $this->service->canUpdateProfile($this->user);

        $this->assertTrue($result);
    }

    public function test_cannot_update_profile_for_inactive_user(): void
    {
        $this->user->update(['status' => User::STATUS_INACTIVE]);

        $this->expectException(Exception::class);
        $this->expectExceptionMessage('Cannot update profile for inactive account');

        $this->service->canUpdateProfile($this->user);
    }

    // ========== Integration Tests ==========

    public function test_complete_profile_update_workflow(): void
    {
        // Update basic profile
        $profileData = [
            'first_name' => 'Jane',
            'last_name' => 'Smith',
            'phone' => '555-1234',
            'bio' => 'Software Engineer',
        ];
        $this->service->updateProfile($this->user, $profileData);

        // Upload profile image
        $image = UploadedFile::fake()->image('profile.jpg');
        $this->service->uploadProfileImage($this->user, $image);

        // Update preferences
        $preferences = ['theme' => 'dark', 'language' => 'en'];
        $this->service->updatePreferences($this->user, $preferences);

        // Update password
        $this->service->updatePassword($this->user, 'password123', 'newpassword456');

        // Verify everything
        $freshUser = User::find($this->user->id);
        $this->assertEquals('Jane', $freshUser->first_name);
        $this->assertEquals('Smith', $freshUser->last_name);
        $this->assertNotNull($freshUser->profile_image);
        $this->assertEquals('dark', $freshUser->preferences['theme']);
        $this->assertTrue(Hash::check('newpassword456', $freshUser->password));
    }

    public function test_handles_multiple_role_types(): void
    {
        $roles = [
            User::ROLE_STUDENT,
            User::ROLE_INSTRUCTOR,
            User::ROLE_ORGANIZATION,
            User::ROLE_ADMIN,
        ];

        foreach ($roles as $role) {
            $user = User::factory()->create(['role' => $role]);
            
            $data = ['first_name' => 'Updated', 'designation' => 'Role-specific'];
            $updatedUser = $this->service->updateProfile($user, $data);

            $this->assertEquals('Updated', $updatedUser->first_name);
            $this->assertEquals('Role-specific', $updatedUser->designation);
        }
    }
}

