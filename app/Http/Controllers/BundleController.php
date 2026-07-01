<?php

namespace App\Http\Controllers;

use App\Models\Bundle;
use App\Models\Course;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class BundleController extends Controller
{
    public function index(): View
    {
        $bundles = Bundle::withCount('courses')->where('status', 'active')->latest()->get();
        return view('bundles.index', compact('bundles'));
    }

    public function show(string $slug): View
    {
        $bundle = Bundle::with(['courses' => function ($query) {
            $query->withCount('enrollments')->with(['instructor', 'lessons', 'level', 'categoryRelation']);
        }])->where('slug', $slug)
            ->where('status', 'active')
            ->firstOrFail();
        return view('bundles.show', compact('bundle'));
    }

    public function adminIndex(): View
    {
        $bundles = Bundle::withCount('courses')->with('creator')->latest()->get();
        $courses = Course::where('status', 'Active')->get();
        return view('admin.course.bundle', compact('bundles', 'courses'));
    }

    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'title' => ['required', 'string', 'max:255'],
            'description' => ['nullable', 'string'],
            'price' => ['required', 'numeric', 'min:0'],
            'sale_price' => ['nullable', 'numeric', 'min:0'],
            'level' => ['nullable', 'string', 'max:100'],
            'status' => ['required', 'in:active,inactive'],
            'course_ids' => ['nullable', 'array'],
            'course_ids.*' => ['exists:courses,id'],
        ]);

        $validated['user_id'] = auth()->id();

        $bundle = Bundle::create($validated);

        if (!empty($validated['course_ids'])) {
            $bundle->courses()->sync($validated['course_ids']);
        }

        return back()->with('success', 'Bundle created successfully!');
    }

    public function update(Request $request, Bundle $bundle): RedirectResponse
    {
        $validated = $request->validate([
            'title' => ['required', 'string', 'max:255'],
            'description' => ['nullable', 'string'],
            'price' => ['required', 'numeric', 'min:0'],
            'sale_price' => ['nullable', 'numeric', 'min:0'],
            'level' => ['nullable', 'string', 'max:100'],
            'status' => ['required', 'in:active,inactive'],
            'course_ids' => ['nullable', 'array'],
            'course_ids.*' => ['exists:courses,id'],
        ]);

        $bundle->update($validated);

        if (!empty($validated['course_ids'])) {
            $bundle->courses()->sync($validated['course_ids']);
        }

        return back()->with('success', 'Bundle updated successfully!');
    }

    public function destroy(Bundle $bundle): RedirectResponse
    {
        $bundle->courses()->detach();
        $bundle->delete();
        return back()->with('success', 'Bundle deleted successfully!');
    }
}
