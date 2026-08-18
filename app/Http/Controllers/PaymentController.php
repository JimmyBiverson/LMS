<?php

namespace App\Http\Controllers;

use App\Models\Bundle;
use App\Models\Course;
use App\Models\Coupon;
use App\Models\Enrollment;
use App\Models\PaymentMethod;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Http;
use Illuminate\View\View;

class PaymentController extends Controller
{
    protected string $secretKey;
    protected string $publicKey;

    public function __construct()
    {
        $this->secretKey = config('services.paystack.secret_key');
        $this->publicKey = config('services.paystack.public_key');
    }

    public function showCheckout()
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

        $hasPaystack = !empty($this->publicKey);
        $paymentMethods = PaymentMethod::where('status', 'active')->get();

        return view('checkout', compact('items', 'subtotal', 'discount', 'coupon', 'tax', 'total', 'hasPaystack', 'paymentMethods'));
    }

    public function initiatePaystack(Request $request): RedirectResponse
    {
        $cart = session()->get('cart', []);
        if (empty($cart['courses'] ?? []) && empty($cart['bundles'] ?? [])) {
            return redirect('/cart')->with('error', 'Your cart is empty.');
        }

        $subtotal = 0;
        $items = [];

        foreach ($cart as $type => $ids) {
            if ($type === 'courses') {
                $courses = Course::whereIn('id', $ids)->get();
                foreach ($courses as $course) {
                    $price = $course->payment_type === 'free' ? 0 : ($course->sale_price ?? $course->price);
                    $items[] = ['type' => 'course', 'id' => $course->id];
                    $subtotal += $price;
                }
            }
            if ($type === 'bundles') {
                $bundles = Bundle::with('courses')->whereIn('id', $ids)->get();
                foreach ($bundles as $bundle) {
                    $price = $bundle->sale_price ?? $bundle->price;
                    $items[] = ['type' => 'bundle', 'id' => $bundle->id];
                    $subtotal += $price;
                }
            }
        }

        $couponCode = session('coupon_code');
        $discount = 0;

        if ($couponCode) {
            $coupon = Coupon::where('code', $couponCode)->first();
            if ($coupon && $coupon->isValid() && $subtotal >= ($coupon->min_amount ?? 0)) {
                $discount = $coupon->discount_type === 'percentage'
                    ? round($subtotal * $coupon->discount / 100, 2)
                    : min($coupon->discount, $subtotal);
            }
        }

        $taxRate = config('lms.tax_rate', 0);
        $tax = round($subtotal * $taxRate, 2);
        $total = max(0, $subtotal - $discount + $tax);

        if ($total <= 0) {
            return redirect('/checkout/place-order')->with('error', 'Free items do not require payment.');
        }

        $reference = 'PAY-' . strtoupper(uniqid());

        session()->put('paystack_reference', $reference);
        session()->put('paystack_items', $items);
        session()->put('paystack_amount', $total);
        session()->put('paystack_discount', $discount);
        session()->put('paystack_tax', $tax);
        if ($couponCode) {
            session()->put('paystack_coupon', $couponCode);
        }

        $response = Http::withToken($this->secretKey)->post('https://api.paystack.co/transaction/initialize', [
            'email' => auth()->user()->email,
            'amount' => (int) round($total * 100),
            'reference' => $reference,
            'callback_url' => route('paystack.callback'),
            'metadata' => [
                'user_id' => auth()->id(),
                'items' => $items,
                'coupon_code' => $couponCode,
            ],
        ]);

        $result = $response->json();

        if (!$result['status']) {
            return redirect('/checkout')->with('error', 'Payment initialization failed: ' . ($result['message'] ?? 'Unknown error'));
        }

        return redirect($result['data']['authorization_url']);
    }

    public function handlePaystackCallback(Request $request): RedirectResponse
    {
        $reference = $request->query('reference');

        if (!$reference) {
            return redirect('/cart')->with('error', 'No payment reference found.');
        }

        $response = Http::withToken($this->secretKey)->get("https://api.paystack.co/transaction/verify/{$reference}");

        $result = $response->json();

        if (!$result['status'] || $result['data']['status'] !== 'success') {
            return redirect('/cart')->with('error', 'Payment verification failed.');
        }

        $metadata = $result['data']['metadata'];
        $user = auth()->user() ?? \App\Models\User::find($metadata['user_id']);

        if (!$user) {
            return redirect('/login')->with('error', 'Please login to complete enrollment.');
        }

        if (auth()->id() !== (int) $metadata['user_id']) {
            Auth::loginUsingId($metadata['user_id']);
            $user = auth()->user();
        }

        $items = $metadata['items'];
        $enrolled = [];

        foreach ($items as $item) {
            if ($item['type'] === 'course') {
                $course = Course::find($item['id']);
                if (!$course) continue;
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
            } elseif ($item['type'] === 'bundle') {
                $bundle = Bundle::with('courses')->find($item['id']);
                if (!$bundle) continue;
                foreach ($bundle->courses as $course) {
                    $exists = Enrollment::where('user_id', $user->id)->where('course_id', $course->id)->exists();
                    if ($exists) continue;
                    Enrollment::create([
                        'user_id' => $user->id,
                        'course_id' => $course->id,
                        'amount_paid' => $price ?? 0,
                        'status' => 'in_progress',
                    ]);
                    \App\Notifications\CourseEnrolled::send($user, $course);
                    $enrolled[] = $course->title . ' (via ' . $bundle->title . ')';
                }
            }
        }

        $couponCode = session('paystack_coupon') ?? session('coupon_code');
        if ($couponCode) {
            $coupon = Coupon::where('code', $couponCode)->first();
            if ($coupon) $coupon->increment('used_count');
        }

        session()->forget('cart');
        session()->forget('coupon_code');
        session()->forget('paystack_reference');
        session()->forget('paystack_items');
        session()->forget('paystack_amount');
        session()->forget('paystack_discount');
        session()->forget('paystack_tax');
        session()->forget('paystack_coupon');

        if (empty($enrolled)) {
            return redirect('/dashboard')->with('info', 'You are already enrolled in all selected courses.');
        }

        return redirect('/dashboard/my-enrolled-course')
            ->with('success', 'Payment successful! Enrolled in ' . count($enrolled) . ' course(s).');
    }

    public function paystackWebhook(Request $request)
    {
        $input = $request->all();

        // Verify webhook signature
        $signature = $request->header('x-paystack-signature');
        $payload = $request->getContent();
        $expectedSignature = hash_hmac('sha512', $payload, $this->secretKey);

        if (!$signature || $signature !== $expectedSignature) {
            logger()->warning('Paystack webhook: Invalid signature');
            return response('Invalid signature', 401);
        }

        if (($input['event'] ?? '') !== 'charge.success') {
            return response('OK', 200);
        }

        $data = $input['data'] ?? [];
        $metadata = $data['metadata'] ?? [];
        $userId = $metadata['user_id'] ?? null;
        $items = $metadata['items'] ?? [];

        if (!$userId || empty($items)) {
            logger()->warning('Paystack webhook: Missing user_id or items in metadata', $metadata);
            return response('OK', 200);
        }

        $user = \App\Models\User::find($userId);
        if (!$user) {
            logger()->warning('Paystack webhook: User not found', ['user_id' => $userId]);
            return response('User not found', 404);
        }

        $enrolled = [];

        foreach ($items as $item) {
            if ($item['type'] === 'course') {
                $course = Course::find($item['id']);
                if (!$course) continue;
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
            } elseif ($item['type'] === 'bundle') {
                $bundle = Bundle::with('courses')->find($item['id']);
                if (!$bundle) continue;
                foreach ($bundle->courses as $course) {
                    $exists = Enrollment::where('user_id', $user->id)->where('course_id', $course->id)->exists();
                    if ($exists) continue;
                    Enrollment::create([
                        'user_id' => $user->id,
                        'course_id' => $course->id,
                        'amount_paid' => $course->payment_type === 'free' ? 0 : ($course->sale_price ?? $course->price),
                        'status' => 'in_progress',
                    ]);
                    \App\Notifications\CourseEnrolled::send($user, $course);
                    $enrolled[] = $course->title . ' (via ' . $bundle->title . ')';
                }
            }
        }

        $couponCode = $metadata['coupon_code'] ?? null;
        if ($couponCode) {
            $coupon = Coupon::where('code', $couponCode)->first();
            if ($coupon) $coupon->increment('used_count');
        }

        logger()->info('Paystack webhook: Enrollment processed', [
            'user_id' => $userId,
            'enrolled_count' => count($enrolled),
        ]);

        return response('OK', 200);
    }
}
