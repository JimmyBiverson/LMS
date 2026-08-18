<?php

namespace App\Http\Controllers;

use App\Models\Noticeboard;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class NoticeboardController extends Controller
{
    public function adminIndex(): View
    {
        $notices = Noticeboard::with('user')->latest()->get();
        return view('admin.noticeboard', compact('notices'));
    }

    public function orgIndex(): View
    {
        $notices = Noticeboard::with('user')->latest()->get();
        return view('org.noticeboard', compact('notices'));
    }

    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'title' => ['required', 'string', 'max:255'],
            'content' => ['nullable', 'string'],
            'status' => ['required', 'in:active,inactive'],
        ]);

        $validated['user_id'] = auth()->id();

        Noticeboard::create($validated);

        return back()->with('success', 'Notice created successfully!');
    }

    public function update(Request $request, Noticeboard $noticeboard): RedirectResponse
    {
        $validated = $request->validate([
            'title' => ['required', 'string', 'max:255'],
            'content' => ['nullable', 'string'],
            'status' => ['required', 'in:active,inactive'],
        ]);

        $noticeboard->update($validated);

        return back()->with('success', 'Notice updated successfully!');
    }

    public function destroy(Noticeboard $noticeboard): RedirectResponse
    {
        $noticeboard->delete();
        return back()->with('success', 'Notice deleted successfully!');
    }
}
