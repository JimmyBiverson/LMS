<?php

namespace App\Services;

use App\Models\User;
use App\Traits\HandleUploads;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
use Intervention\Image\Laravel\Facades\Image;
use Exception;

/**
 * Profile Management Service
 * 
 * Handles user profile operations across all roles including profile updates,
 * password changes with validation, profile image management with resizing,
 * and user preferences management.
 */
class ProfileManagementService
{
    use HandleUploads;

    /**
     * Profile image configuration
     */
    private const PROFILE_IMAGE_FOLDER = 'profiles/images';
    private const MAX_IMAGE_SIZE = 5242880; // 5MB
    private const IMAGE_WIDTH = 500;
    private const IMAGE_HEIGHT = 500;
    private const IMAGE_QUALITY = 85;

    /**
     * Update user profile information.
     * 
     * Updates basic profile fields for all user roles. Validates and updates
     * name, email, phone, bio, and role-specific fields like designation.
     * 
     * @param User $user User to update
     * @param array $data Profile data to update
     * @return User Updated user model
     * @throws Exception If validation or update fails
     */
    public function updateProfile(User $user, array $data): User
    {
        try {
            // Define updatable fields
            $updatableFields = [
                'first_name',
                'last_name',
                'name',
                'phone',
                'bio',
                'designation',
                'address',
            ];

            // Filter only updatable fields from input data
            $updateData = array_filter(
                $data,
                fn($key) => in_array($key, $updatableFields),
                ARRAY_FILTER_USE_KEY
            );

            // Update user
            $user->update($updateData);

            Log::info('Profile updated successfully', [
                'user_id' => $user->id,
                'fields_updated' => array_keys($updateData)
            ]);

            return $user->fresh();
        } catch (Exception $e) {
            Log::error('Profile update failed', [
                'user_id' => $user->id,
                'error' => $e->getMessage()
            ]);
            throw new Exception('Failed to update profile: ' . $e->getMessage());
        }
    }

    /**
     * Update user password with validation.
     * 
     * Validates the current password before allowing password change.
     * Ensures new password meets security requirements.
     * 
     * @param User $user User whose password to update
     * @param string $currentPassword Current password for verification
     * @param string $newPassword New password to set
     * @return bool True on success
     * @throws Exception If current password is invalid or update fails
     */
    public function updatePassword(User $user, string $currentPassword, string $newPassword): bool
    {
        try {
            // Verify current password
            if (!Hash::check($currentPassword, $user->password)) {
                throw new Exception('Current password is incorrect');
            }

            // Validate new password length (Laravel's hashing handles this, but explicit check is good)
            if (strlen($newPassword) < 8) {
                throw new Exception('New password must be at least 8 characters');
            }

            // Update password - Laravel will automatically hash it via the User model's casts
            $user->update([
                'password' => Hash::make($newPassword)
            ]);

            Log::info('Password updated successfully', [
                'user_id' => $user->id
            ]);

            return true;
        } catch (Exception $e) {
            Log::error('Password update failed', [
                'user_id' => $user->id,
                'error' => $e->getMessage()
            ]);
            throw $e;
        }
    }

    /**
     * Upload and process profile image.
     * 
     * Validates image file, resizes to standard dimensions, and stores.
     * Automatically removes old profile image if exists.
     * 
     * @param User $user User to upload image for
     * @param UploadedFile $image Image file to upload
     * @return string Path to stored image
     * @throws Exception If upload or processing fails
     */
    public function uploadProfileImage(User $user, UploadedFile $image): string
    {
        try {
            // Validate image
            if (!$this->isValidImage($image)) {
                throw new Exception('File must be a valid image (JPEG, PNG, or WebP)');
            }

            // Validate file size
            if ($image->getSize() > self::MAX_IMAGE_SIZE) {
                throw new Exception('Image size must be less than 5MB');
            }

            // Delete old profile image if exists
            if ($user->hasProfileImage()) {
                $this->deleteProfileImage($user);
            }

            // Ensure directory exists
            $this->ensureDirectoryExists(self::PROFILE_IMAGE_FOLDER);

            // Generate unique filename
            $filename = $this->generateUniqueFilename($image);
            $path = self::PROFILE_IMAGE_FOLDER . '/' . $filename;

            // Process and resize image
            $processedImage = $this->resizeImage($image);

            // Store the processed image
            $stored = Storage::disk('public')->put($path, $processedImage);

            if (!$stored) {
                throw new Exception('Failed to store profile image');
            }

            // Update user's profile_image field
            $user->update(['profile_image' => $path]);

            Log::info('Profile image uploaded successfully', [
                'user_id' => $user->id,
                'path' => $path
            ]);

            return $path;
        } catch (Exception $e) {
            Log::error('Profile image upload failed', [
                'user_id' => $user->id,
                'error' => $e->getMessage()
            ]);
            throw $e;
        }
    }

    /**
     * Delete user's profile image.
     * 
     * Removes profile image file from storage and updates user record.
     * 
     * @param User $user User whose profile image to delete
     * @return bool True on success, false if no image exists
     * @throws Exception If deletion fails
     */
    public function deleteProfileImage(User $user): bool
    {
        try {
            // Check if user has a profile image
            if (!$user->hasProfileImage()) {
                return false;
            }

            $imagePath = $user->getProfileImagePath();

            // Delete file from storage
            if (Storage::disk('public')->exists($imagePath)) {
                Storage::disk('public')->delete($imagePath);
            }

            // Clear profile_image field
            $user->update(['profile_image' => null]);

            Log::info('Profile image deleted successfully', [
                'user_id' => $user->id,
                'path' => $imagePath
            ]);

            return true;
        } catch (Exception $e) {
            Log::error('Profile image deletion failed', [
                'user_id' => $user->id,
                'error' => $e->getMessage()
            ]);
            throw new Exception('Failed to delete profile image: ' . $e->getMessage());
        }
    }

    /**
     * Update user preferences.
     * 
     * Stores user-specific preferences as JSON. Can include notification settings,
     * UI preferences, language preferences, etc.
     * 
     * @param User $user User to update preferences for
     * @param array $preferences Preferences array to store
     * @return void
     * @throws Exception If update fails
     */
    public function updatePreferences(User $user, array $preferences): void
    {
        try {
            // Merge with existing preferences to avoid overwriting
            $currentPreferences = $user->preferences ?? [];
            $updatedPreferences = array_merge($currentPreferences, $preferences);

            $user->update(['preferences' => $updatedPreferences]);

            Log::info('Preferences updated successfully', [
                'user_id' => $user->id,
                'preferences_keys' => array_keys($preferences)
            ]);
        } catch (Exception $e) {
            Log::error('Preferences update failed', [
                'user_id' => $user->id,
                'error' => $e->getMessage()
            ]);
            throw new Exception('Failed to update preferences: ' . $e->getMessage());
        }
    }

    /**
     * Resize and optimize image for profile use.
     * 
     * Creates a square image at specified dimensions with optimized quality.
     * Uses Intervention Image if available, falls back to basic resizing.
     * 
     * @param UploadedFile $image Original image file
     * @return string Binary image data
     * @throws Exception If processing fails
     */
    private function resizeImage(UploadedFile $image): string
    {
        try {
            $img = Image::decode($image->getRealPath());
            $img->cover(self::IMAGE_WIDTH, self::IMAGE_HEIGHT);
            return $img->encodeUsingFileExtension('webp', quality: self::IMAGE_QUALITY)->toString();
        } catch (Exception $e) {
            Log::warning('Image resize failed, using original', [
                'error' => $e->getMessage()
            ]);
            return file_get_contents($image->getRealPath());
        }
    }

    /**
     * Get user's complete profile information.
     * 
     * Returns all profile data including computed attributes like full name
     * and profile image URL.
     * 
     * @param User $user User to get profile for
     * @return array Profile data array
     */
    public function getProfile(User $user): array
    {
        return [
            'id' => $user->id,
            'name' => $user->name,
            'first_name' => $user->first_name,
            'last_name' => $user->last_name,
            'full_name' => $user->full_name,
            'email' => $user->email,
            'phone' => $user->phone,
            'role' => $user->role,
            'designation' => $user->designation,
            'address' => $user->address,
            'bio' => $user->bio,
            'status' => $user->status,
            'profile_image' => $user->profile_image,
            'profile_image_url' => $user->profile_image_url,
            'preferences' => $user->preferences,
            'activity_notifications' => $user->activity_notifications,
            'last_activity_at' => $user->last_activity_at,
            'created_at' => $user->created_at,
        ];
    }

    /**
     * Validate profile data before update.
     * 
     * Performs additional validation beyond Laravel's form requests.
     * 
     * @param array $data Profile data to validate
     * @param User $user User being updated (for email uniqueness check)
     * @return array Validated and sanitized data
     * @throws Exception If validation fails
     */
    public function validateProfileData(array $data, User $user): array
    {
        $validated = [];

        // Validate name fields
        if (isset($data['first_name'])) {
            $validated['first_name'] = trim($data['first_name']);
        }

        if (isset($data['last_name'])) {
            $validated['last_name'] = trim($data['last_name']);
        }

        if (isset($data['name'])) {
            $validated['name'] = trim($data['name']);
        }

        // Validate phone (basic format check)
        if (isset($data['phone'])) {
            $phone = preg_replace('/[^0-9+\-() ]/', '', $data['phone']);
            $validated['phone'] = $phone;
        }

        // Validate bio (max length)
        if (isset($data['bio'])) {
            $validated['bio'] = substr(trim($data['bio']), 0, 1000);
        }

        // Validate designation
        if (isset($data['designation'])) {
            $validated['designation'] = trim($data['designation']);
        }

        // Validate address
        if (isset($data['address'])) {
            $validated['address'] = trim($data['address']);
        }

        return $validated;
    }

    /**
     * Check if user can update their profile.
     * 
     * Validates permissions and account status.
     * 
     * @param User $user User attempting to update profile
     * @return bool True if allowed
     * @throws Exception If not allowed
     */
    public function canUpdateProfile(User $user): bool
    {
        if ($user->status === User::STATUS_INACTIVE) {
            throw new Exception('Cannot update profile for inactive account');
        }

        return true;
    }
}

