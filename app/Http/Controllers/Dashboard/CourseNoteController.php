<?php

namespace App\Http\Controllers\Dashboard;

use App\Http\Controllers\Controller;
use App\Models\CourseNote;
use App\Models\Enrollment;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\View\View;
use Symfony\Component\HttpFoundation\BinaryFileResponse;

class CourseNoteController extends Controller
{
    use AuthorizesRequests;
    public function index(Request $request): View
    {
        $this->authorize('viewAny', CourseNote::class);

        $courseIds = Enrollment::where('user_id', auth()->id())->pluck('course_id');
        $query = CourseNote::with('course')->whereIn('course_id', $courseIds)->where('status', 'published');

        if ($request->filled('search')) {
            $search = $request->string('search');
            $query->where(function ($q) use ($search) {
                $q->where('title', 'like', "%{$search}%")
                    ->orWhere('summary', 'like', "%{$search}%")
                    ->orWhere('content', 'like', "%{$search}%");
            });
        }

        $notes = $query->orderBy('display_order')->orderBy('created_at', 'desc')->paginate(10)->withQueryString();

        return view('dashboard.course-notes.index', compact('notes'));
    }

    public function show(CourseNote $courseNote): View
    {
        $this->authorize('view', $courseNote);

        return view('dashboard.course-notes.show', compact('courseNote'));
    }

    public function download(CourseNote $courseNote): BinaryFileResponse
    {
        $this->authorize('download', $courseNote);

        if (!$courseNote->attachment_path || !Storage::disk('public')->exists($courseNote->attachment_path)) {
            abort(404, 'The attachment is not available.');
        }

        $downloadName = $courseNote->download_filename;

        return response()->download(
            Storage::disk('public')->path($courseNote->attachment_path),
            $downloadName,
            ['Content-Disposition' => 'attachment; filename="' . $downloadName . '"']
        );
    }
}
