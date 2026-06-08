<?php

namespace App\Http\Controllers;

use App\Models\Bundle;
use App\Models\Coupon;
use App\Models\Course;
use App\Models\Enrollment;
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

    public function checkout(Request $request): View
    {
        $cart = session()->get('cart', []);
        if (empty($cart['courses'] ?? []) && empty($cart['bundles'] ?? [])) {
            return redirect('/cart')->with('error', 'Your cart is empty.');
        }

        $items = [];
        $subtotal = 0;

        foreach ($cart as $type => $ids) {
            if ($type === 'courses') {
                $courses = Course::whereIn('id', $ids)->get();
                foreach ($courses as $course) {
                    $price = $course->payment_type === 'free' ? 0 : ($course->sale_price ?? $course->price);
                    $items[] = ['type' => 'course', 'id' => $course->id, 'title' => $course->title, 'price' => $price];
                    $subtotal += $price;
                }
            }
            if ($type === 'bundles') {
                $bundles = Bundle::whereIn('id', $ids)->get();
                foreach ($bundles as $bundle) {
                    $price = $bundle->sale_price ?? $bundle->price;
                    $items[] = ['type' => 'bundle', 'id' => $bundle->id, 'title' => $bundle->title, 'price' => $price];
                    $subtotal += $price;
                }
            }
        }

        $discount = 0;
        $couponCode = session('coupon_code');
        $coupon = null;

        if ($couponCode) {
            $coupon = Coupon::where('code', $couponCode)->first();
            if ($coupon && $coupon->isValid() && $subtotal >= ($coupon->min_amount ?? 0)) {
                $discount = $coupon->discount_type === 'percentage'
                    ? round($subtotal * $coupon->discount / 100, 2)
                    : min($coupon->discount, $subtotal);
            } else {
                session()->forget('coupon_code');
                $coupon = null;
            }
        }

        $taxRate = config('lms.tax_rate', 0);
        $tax = round($subtotal * $taxRate, 2);
        $total = max(0, $subtotal - $discount + $tax);

        return view('checkout', compact('items', 'subtotal', 'discount', 'coupon', 'tax', 'total'));
    }

    public function applyCoupon(Request $request): RedirectResponse
    {
        $request->validate(['code' => 'required|string|max:50']);

        $coupon = Coupon::where('code', $request->code)->first();

        if (!$coupon || !$coupon->isValid()) {
            return back()->withErrors(['code' => 'Invalid or expired coupon code.']);
        }

        session()->put('coupon_code', $coupon->code);
        return back()->with('success', 'Coupon applied!');
    }

    public function removeCoupon(): RedirectResponse
    {
        session()->forget('coupon_code');
        return back()->with('info', 'Coupon removed.');
    }

    public function placeOrder(Request $request): RedirectResponse
    {
        $cart = session()->get('cart', []);
        if (empty($cart['courses'] ?? []) && empty($cart['bundles'] ?? [])) {
            return redirect('/cart')->with('error', 'Your cart is empty.');
        }

        $user = $request->user();
        $enrolled = [];
        $subtotal = 0;

        $courseIds = $cart['courses'] ?? [];
        if (!empty($courseIds)) {
            $courses = Course::whereIn('id', $courseIds)->where('status', 'Active')->get();
            foreach ($courses as $course) {
                $exists = Enrollment::where('user_id', $user->id)->where('course_id', $course->id)->exists();
                if ($exists) continue;

                $price = $course->payment_type === 'free' ? 0 : ($course->sale_price ?? $course->price);
                Enrollment::create([
                    'user_id' => $user->id,
                    'course_id' => $course->id,
                    'amount_paid' => $price,
                    'status' => 'in_progress',
                ]);
                \App\Notifications\CourseEnrolled::send($user, $course);
                $enrolled[] = $course->title;
                $subtotal += $price;
            }
        }

        $bundleIds = $cart['bundles'] ?? [];
        if (!empty($bundleIds)) {
            $bundles = Bundle::with('courses')->whereIn('id', $bundleIds)->get();
            foreach ($bundles as $bundle) {
                foreach ($bundle->courses as $course) {
                    $exists = Enrollment::where('user_id', $user->id)->where('course_id', $course->id)->exists();
                    if ($exists) continue;

                    $price = $course->payment_type === 'free' ? 0 : ($course->sale_price ?? $course->price);
                    Enrollment::create([
                        'user_id' => $user->id,
                        'course_id' => $course->id,
                        'amount_paid' => $price,
                        'status' => 'in_progress',
                    ]);
                    \App\Notifications\CourseEnrolled::send($user, $course);
                    $enrolled[] = $course->title . ' (via ' . $bundle->title . ')';
                    $subtotal += $price;
                }
            }
        }

        if (empty($enrolled)) {
            session()->forget('cart');
            session()->forget('coupon_code');
            return redirect('/dashboard')->with('info', 'You are already enrolled in all selected courses.');
        }

        $couponCode = session('coupon_code');
        if ($couponCode) {
            $coupon = Coupon::where('code', $couponCode)->first();
            if ($coupon) {
                $coupon->increment('used_count');
            }
            session()->forget('coupon_code');
        }

        session()->forget('cart');

        return redirect('/dashboard/my-enrolled-course')
            ->with('success', 'Successfully enrolled in ' . count($enrolled) . ' course(s)!');
    }
}
