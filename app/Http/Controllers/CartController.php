<?php

namespace App\Http\Controllers;

use App\Models\Bundle;
use App\Models\Course;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class CartController extends Controller
{
    public function index(): View
    {
        $cart = session()->get('cart', []);
        $items = [];
        $total = 0;

        foreach ($cart as $type => $ids) {
            if ($type === 'courses') {
                $courses = Course::whereIn('id', $ids)->get();
                foreach ($courses as $course) {
                    $price = $course->payment_type === 'free' ? 0 : ($course->sale_price ?? $course->price);
                    $items[] = [
                        'type' => 'course',
                        'id' => $course->id,
                        'title' => $course->title,
                        'slug' => $course->slug,
                        'price' => $price,
                        'image' => $course->thumbnail,
                    ];
                    $total += $price;
                }
            }
            if ($type === 'bundles') {
                $bundles = Bundle::whereIn('id', $ids)->get();
                foreach ($bundles as $bundle) {
                    $price = $bundle->sale_price ?? $bundle->price;
                    $items[] = [
                        'type' => 'bundle',
                        'id' => $bundle->id,
                        'title' => $bundle->title,
                        'slug' => $bundle->slug,
                        'price' => $price,
                        'image' => $bundle->thumbnail,
                    ];
                    $total += $price;
                }
            }
        }

        return view('cart', compact('items', 'total'));
    }

    public function addCourse(Request $request, int $courseId): RedirectResponse
    {
        $course = Course::findOrFail($courseId);
        $cart = session()->get('cart', []);
        $cart['courses'][$courseId] = $courseId;
        session()->put('cart', $cart);

        return back()->with('success', '"' . $course->title . '" added to cart!');
    }

    public function addBundle(Request $request, int $bundleId): RedirectResponse
    {
        $bundle = Bundle::findOrFail($bundleId);
        $cart = session()->get('cart', []);
        $cart['bundles'][$bundleId] = $bundleId;
        session()->put('cart', $cart);

        return back()->with('success', '"' . $bundle->title . '" added to cart!');
    }

    public function remove(Request $request, string $type, int $id): RedirectResponse
    {
        $cart = session()->get('cart', []);
        unset($cart[$type][$id]);
        session()->put('cart', $cart);

        return redirect('/cart')->with('info', 'Item removed from cart.');
    }

    public function clear(): RedirectResponse
    {
        session()->forget('cart');
        return redirect('/cart')->with('info', 'Cart cleared.');
    }

    public function checkout(): View
    {
        $cart = session()->get('cart', []);
        if (empty($cart['courses'] ?? []) && empty($cart['bundles'] ?? [])) {
            return redirect('/cart')->with('error', 'Your cart is empty.');
        }

        return view('checkout');
    }
}
