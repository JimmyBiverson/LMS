<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;

class SetupUploadDirectories extends Command
{
    protected $signature = 'setup:upload-directories';
    protected $description = 'Create required upload directories and ensure storage symlink is set up';

    public function handle(): int
    {
        $this->info('Setting up upload directories...');

        $directories = [
            'courses/thumbnails',
            'lessons/videos',
            'lessons/documents',
            'assignments/submissions',
            'assignments/instructions',
            'quizzes/media',
            'quizzes/instructions',
            'settings',
            'settings/videos',
            'profiles/images',
            'sliders',
            'blogs',
        ];

        $extraDirectories = [
            storage_path('tmp'),
        ];

        // Ensure storage link exists
        $storagePath = public_path('storage');
        $targetPath = storage_path('app/public');

        if (!file_exists($storagePath) || !is_link($storagePath)) {
            if (file_exists($storagePath) && !is_link($storagePath)) {
                $this->warn('public/storage already exists but is not a symlink. Removing it...');
                if (is_file($storagePath)) {
                    unlink($storagePath);
                } elseif (is_dir($storagePath)) {
                    // On Windows, junctions may appear as directories; remove if empty
                    try {
                        rmdir($storagePath);
                    } catch (\Exception $e) {
                        $this->error('public/storage is a directory and must be removed manually if not a symlink.');
                    }
                }
            }

            if (!file_exists($storagePath)) {
                $this->info('Creating storage symlink...');
                try {
                    // Trigger storage:link artist command which is cross-platform/highly compatible
                    \Illuminate\Support\Facades\Artisan::call('storage:link');
                    $this->info(\Illuminate\Support\Facades\Artisan::output());
                    $this->info('✓ Storage symlink created via storage:link');
                } catch (\Exception $e) {
                    try {
                        symlink($targetPath, $storagePath);
                        $this->info('✓ Storage symlink created successfully');
                    } catch (\Exception $e2) {
                        $this->error('Failed to create symlink: ' . $e2->getMessage());
                        $this->warn('Please run: mklink /D "' . $storagePath . '" "' . $targetPath . '"');
                    }
                }
            }
        } elseif (is_link($storagePath)) {
            $this->info('✓ Storage symlink already exists');
        }

        // Create upload directories under storage/app/public
        foreach ($directories as $dir) {
            try {
                $path = storage_path('app/public/' . $dir);
                if (!is_dir($path)) {
                    mkdir($path, 0755, true);
                    $this->info("✓ Created directory: {$dir}");
                } else {
                    $this->info("✓ Directory already exists: {$dir}");
                }

                // Set permissions
                @chmod($path, 0755);
            } catch (\Exception $e) {
                $this->error("Failed to create directory {$dir}: " . $e->getMessage());
            }
        }

        // Create extra runtime directories
        foreach ($extraDirectories as $path) {
            try {
                if (!is_dir($path)) {
                    mkdir($path, 0755, true);
                    $this->info("✓ Created runtime directory: {$path}");
                } else {
                    $this->info("✓ Runtime directory already exists: {$path}");
                }
                @chmod($path, 0755);
            } catch (\Exception $e) {
                $this->error("Failed to create runtime directory {$path}: " . $e->getMessage());
            }
        }

        // Set permissions on main storage directories
        try {
            @chmod(storage_path('app/public'), 0755);
            @chmod(storage_path('app'), 0755);
            $this->info('✓ Set storage directory permissions');
        } catch (\Exception $e) {
            $this->warn('Could not set permissions: ' . $e->getMessage());
        }

        $this->info('Setup complete!');
        $this->info('');
        $this->info('Upload directories created:');
        foreach ($directories as $dir) {
            $this->info("  - {$dir}");
        }

        return self::SUCCESS;
    }
}
