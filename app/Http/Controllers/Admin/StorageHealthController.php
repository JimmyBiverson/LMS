<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class StorageHealthController extends Controller
{
    public function index()
    {
        $storagePath = public_path('storage');
        $targetPath = storage_path('app/public');
        
        $symlinkExists = file_exists($storagePath);
        $isSymlink = is_link($storagePath);
        
        $isWritable = is_writable($targetPath);
        
        $requiredDirs = [
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
        
        $dirsStatus = [];
        foreach ($requiredDirs as $dir) {
            $path = storage_path('app/public/' . $dir);
            $dirsStatus[$dir] = [
                'exists' => file_exists($path),
                'writable' => file_exists($path) && is_writable($path),
                'full_path' => $path
            ];
        }

        // Try writing a temp check file 
        $testFileWritten = false;
        $testFileReadable = false;
        $testFileUrl = null;
        
        if ($symlinkExists && $isWritable) {
            $filename = 'health-check.txt';
            $content = 'LMS Storage Connection OK: ' . now()->toIso8601String();
            
            try {
                Storage::disk('public')->put($filename, $content);
                $testFileWritten = true;
                
                // Try reading via HTTP or local public path
                $testFileLocal = public_path('storage/' . $filename);
                if (file_exists($testFileLocal) && is_readable($testFileLocal)) {
                    $testFileReadable = true;
                }
                
                $testFileUrl = asset('storage/' . $filename);
                // Clean up check file
                Storage::disk('public')->delete($filename);
            } catch (\Exception $e) {
                // Squelch warning
            }
        }
        
        return view('admin.storage-health', compact(
            'symlinkExists',
            'isSymlink',
            'storagePath',
            'targetPath',
            'isWritable',
            'dirsStatus',
            'testFileWritten',
            'testFileReadable',
            'testFileUrl'
        ));
    }

    public function fix()
    {
        try {
            // Run setup:upload-directories
            Artisan::call('setup:upload-directories');
            $output = Artisan::output();
            
            return back()->with('success', 'Storage issues resolved successfully! Details: ' . trim($output));
        } catch (\Exception $e) {
            return back()->with('error', 'Execution failed: ' . $e->getMessage());
        }
    }
}
