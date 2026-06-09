<?php

namespace App\Http\Controllers;

use App\Models\MeetProvider;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\View\View;

class MeetProviderController extends Controller
{
    public function index(): View
    {
        $providers = MeetProvider::latest()->get();
        return view('admin.meet-provider', compact('providers'));
    }

    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'api_key' => 'nullable|string',
            'status' => 'required|in:active,inactive',
        ]);

        $validated['slug'] = Str::slug($validated['name']);

        MeetProvider::create($validated);

        return back()->with('success', 'Meet provider added successfully!');
    }

    public function update(Request $request, MeetProvider $meetProvider): RedirectResponse
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'api_key' => 'nullable|string',
            'status' => 'required|in:active,inactive',
        ]);

        $validated['slug'] = Str::slug($validated['name']);

        $meetProvider->update($validated);

        return back()->with('success', 'Meet provider updated successfully!');
    }

    public function destroy(MeetProvider $meetProvider): RedirectResponse
    {
        $meetProvider->delete();

        return back()->with('success', 'Meet provider removed successfully!');
    }
}
