<?php

namespace App\Http\Controllers;

use App\Models\SupportTicketCategory;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\View\View;

class SupportTicketCategoryController extends Controller
{
    public function index(): View
    {
        $categories = SupportTicketCategory::latest()->get();
        return view('admin.support-ticket.category', compact('categories'));
    }

    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'status' => 'required|in:active,inactive',
        ]);

        $validated['slug'] = Str::slug($validated['name']);

        SupportTicketCategory::create($validated);

        return back()->with('success', 'Category created successfully!');
    }

    public function update(Request $request, SupportTicketCategory $supportTicketCategory): RedirectResponse
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'status' => 'required|in:active,inactive',
        ]);

        $validated['slug'] = Str::slug($validated['name']);

        $supportTicketCategory->update($validated);

        return back()->with('success', 'Category updated successfully!');
    }

    public function destroy(SupportTicketCategory $supportTicketCategory): RedirectResponse
    {
        $supportTicketCategory->delete();

        return back()->with('success', 'Category deleted successfully!');
    }
}
