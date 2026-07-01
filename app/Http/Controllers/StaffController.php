<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\View\View;

class StaffController extends Controller
{
    public function index(): View
    {
        $staffMembers = User::where('role', User::ROLE_STAFF)->latest()->get();
        return view('admin.staff', compact('staffMembers'));
    }

    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email',
            'password' => 'required|string|min:8|confirmed',
            'designation' => 'nullable|string|max:255',
            'phone' => 'nullable|string|max:20',
        ]);

        User::create([
            'name' => $validated['name'],
            'email' => $validated['email'],
            'password' => Hash::make($validated['password']),
            'role' => User::ROLE_STAFF,
            'designation' => $validated['designation'] ?? 'Staff',
            'phone' => $validated['phone'] ?? null,
            'status' => User::STATUS_ACTIVE,
        ]);

        return back()->with('success', 'Staff member created successfully!');
    }

    public function update(Request $request, User $staff): RedirectResponse
    {
        if ($staff->role !== User::ROLE_STAFF) {
            abort(404);
        }

        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email,' . $staff->id,
            'designation' => 'nullable|string|max:255',
            'phone' => 'nullable|string|max:20',
            'status' => 'required|in:active,inactive',
        ]);

        if ($request->filled('password')) {
            $request->validate(['password' => 'string|min:8|confirmed']);
            $validated['password'] = Hash::make($request->password);
        }

        $staff->update($validated);

        return back()->with('success', 'Staff member updated successfully!');
    }

    public function destroy(User $staff): RedirectResponse
    {
        if ($staff->role !== User::ROLE_STAFF) {
            abort(404);
        }

        $staff->delete();

        return back()->with('success', 'Staff member removed successfully!');
    }
}
