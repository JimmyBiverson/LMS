<?php

namespace App\Http\Controllers;

use App\Models\Wishlist;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class WishlistController extends Controller
{
    public function toggle(Request $request, int $courseId): RedirectResponse
    {
        $existing = Wishlist::where('user_id', auth()->id())
            ->where('course_id', $courseId)->first();

        if ($existing) {
            $existing->delete();
            return back()->with('info', 'Removed from wishlist.');
        }

        Wishlist::create([
            'user_id' => auth()->id(),
            'course_id' => $courseId,
        ]);

        return back()->with('success', 'Added to wishlist!');
    }

    public function index(): View
    {
        $wishlists = Wishlist::with('course.instructor', 'course.lessons')
            ->where('user_id', auth()->id())
            ->latest()->get();
        return view('dashboard.wishlists', compact('wishlists'));
    }
}
