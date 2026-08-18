<?php

namespace App\Http\Controllers;

use App\Models\Payout;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class PayoutController extends Controller
{
    public function index(): View
    {
        $payouts = Payout::with('user')
            ->where('user_id', auth()->id())
            ->latest()
            ->paginate(20);
        $totalEarnings = \App\Models\Enrollment::whereIn('course_id', function ($q) {
            $q->select('id')->from('courses')->where('user_id', auth()->id());
        })->sum('amount_paid');
        $totalPaid = Payout::where('user_id', auth()->id())
            ->where('status', 'paid')->sum('amount');
        $pendingBalance = $totalEarnings - $totalPaid;

        return view('instructor.payouts', compact('payouts', 'totalEarnings', 'totalPaid', 'pendingBalance'));
    }

    public function request(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'amount' => 'required|numeric|min:1',
            'method' => 'required|string|in:bank,paypal,stripe',
            'account_details' => 'required|string|max:500',
            'notes' => 'nullable|string|max:1000',
        ]);

        $totalEarnings = \App\Models\Enrollment::whereIn('course_id', function ($q) {
            $q->select('id')->from('courses')->where('user_id', auth()->id());
        })->sum('amount_paid');
        $totalPaid = Payout::where('user_id', auth()->id())
            ->where('status', 'paid')->sum('amount');
        $pendingBalance = $totalEarnings - $totalPaid;

        if ($validated['amount'] > $pendingBalance) {
            return back()->withErrors(['amount' => 'Requested amount exceeds your available balance.']);
        }

        $validated['user_id'] = auth()->id();
        $validated['status'] = 'pending';
        Payout::create($validated);

        return back()->with('success', 'Payout request submitted!');
    }

    public function adminIndex(): View
    {
        $payouts = Payout::with('user')->latest()->paginate(20);
        return view('admin.payouts', compact('payouts'));
    }

    public function approve(Payout $payout): RedirectResponse
    {
        $payout->update(['status' => 'paid', 'paid_at' => now()]);
        return back()->with('success', 'Payout approved!');
    }

    public function reject(Request $request, Payout $payout): RedirectResponse
    {
        $request->validate(['notes' => 'nullable|string|max:1000']);
        $payout->update(['status' => 'rejected', 'notes' => $request->notes ?? $payout->notes]);
        return back()->with('success', 'Payout rejected.');
    }
}
