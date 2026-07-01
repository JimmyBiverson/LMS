<?php

namespace App\Http\Controllers;

use App\Models\SubscriptionPlan;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\View\View;

class SubscriptionController extends Controller
{
    public function index(): View
    {
        $plans = SubscriptionPlan::withCount('userSubscriptions')->latest()->get();
        return view('admin.lms-module.subscription', compact('plans'));
    }

    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'price' => 'required|numeric|min:0',
            'duration' => 'required|in:monthly,yearly',
            'duration_months' => 'required|integer|min:1',
            'features' => 'nullable|string',
            'status' => 'required|in:active,inactive',
        ]);

        $validated['slug'] = Str::slug($validated['name']);
        $validated['features'] = $validated['features'] ? array_map('trim', explode("\n", $validated['features'])) : null;

        SubscriptionPlan::create($validated);

        return back()->with('success', 'Subscription plan created successfully!');
    }

    public function update(Request $request, SubscriptionPlan $subscriptionPlan): RedirectResponse
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'price' => 'required|numeric|min:0',
            'duration' => 'required|in:monthly,yearly',
            'duration_months' => 'required|integer|min:1',
            'features' => 'nullable|string',
            'status' => 'required|in:active,inactive',
        ]);

        $validated['slug'] = Str::slug($validated['name']);
        $validated['features'] = $validated['features'] ? array_map('trim', explode("\n", $validated['features'])) : null;

        $subscriptionPlan->update($validated);

        return back()->with('success', 'Subscription plan updated successfully!');
    }

    public function destroy(SubscriptionPlan $subscriptionPlan): RedirectResponse
    {
        $subscriptionPlan->delete();

        return back()->with('success', 'Subscription plan removed successfully!');
    }
}
