<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Level;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class LevelController extends Controller
{
    public function index(): View
    {
        $levels = Level::orderBy('order')->get();
        return view('admin.course.level', compact('levels'));
    }

    public function store(Request $request): RedirectResponse
    {
        $data = $request->validate([
            'name' => ['required', 'string', 'max:100'],
            'order' => ['nullable', 'integer', 'min:0'],
        ]);

        $data['slug'] = \Illuminate\Support\Str::slug($data['name']);
        $data['order'] = $data['order'] ?? Level::max('order') + 1;

        Level::create($data);

        return redirect('/admin/course/level')->with('success', 'Level created successfully.');
    }

    public function update(Request $request, Level $level): RedirectResponse
    {
        $data = $request->validate([
            'name' => ['required', 'string', 'max:100'],
            'order' => ['nullable', 'integer', 'min:0'],
        ]);

        $level->update([
            'name' => $data['name'],
            'slug' => \Illuminate\Support\Str::slug($data['name']),
            'order' => $data['order'] ?? $level->order,
        ]);

        return redirect('/admin/course/level')->with('success', 'Level updated successfully.');
    }

    public function destroy(Level $level): RedirectResponse
    {
        $level->courses()->update(['level_id' => null]);
        $level->delete();
        return redirect('/admin/course/level')->with('success', 'Level deleted successfully.');
    }
}
