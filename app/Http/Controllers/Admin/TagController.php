<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Tag;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class TagController extends Controller
{
    public function index(): View
    {
        $tags = Tag::orderBy('name')->get();
        return view('admin.course.tag', compact('tags'));
    }

    public function store(Request $request): RedirectResponse
    {
        $data = $request->validate([
            'name' => ['required', 'string', 'max:100', 'unique:tags,name'],
        ]);

        Tag::create([
            'name' => $data['name'],
            'slug' => \Illuminate\Support\Str::slug($data['name']),
        ]);

        return redirect('/admin/course/tag')->with('success', 'Tag created successfully.');
    }

    public function update(Request $request, Tag $tag): RedirectResponse
    {
        $data = $request->validate([
            'name' => ['required', 'string', 'max:100', 'unique:tags,name,' . $tag->id],
        ]);

        $tag->update([
            'name' => $data['name'],
            'slug' => \Illuminate\Support\Str::slug($data['name']),
        ]);

        return redirect('/admin/course/tag')->with('success', 'Tag updated successfully.');
    }

    public function destroy(Tag $tag): RedirectResponse
    {
        $tag->courses()->detach();
        $tag->delete();
        return redirect('/admin/course/tag')->with('success', 'Tag deleted successfully.');
    }
}
