<?php

namespace App\Http\Controllers;

use App\Models\SupportTicket;
use App\Models\TicketReply;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class SupportTicketController extends Controller
{
    public function index(): View
    {
        $tickets = SupportTicket::with('user')
            ->where('user_id', auth()->id())
            ->latest()
            ->get();

        return view('dashboard.supports.index', compact('tickets'));
    }

    public function show(SupportTicket $supportTicket): View
    {
        if ($supportTicket->user_id !== auth()->id() && !auth()->user()?->isAdmin()) {
            abort(403);
        }

        $supportTicket->load('replies.user');

        return view('dashboard.supports.show', compact('supportTicket'));
    }

    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'subject' => ['required', 'string', 'max:255'],
            'category' => ['required', 'string', 'max:255'],
            'priority' => ['required', 'string', 'in:Low,Medium,High'],
            'message' => ['required', 'string'],
        ]);

        $validated['user_id'] = auth()->id();

        SupportTicket::create($validated);

        return redirect()->route('dashboard.supports')->with('success', 'Support ticket created successfully!');
    }

    public function reply(Request $request, SupportTicket $supportTicket): RedirectResponse
    {
        if ($supportTicket->user_id !== auth()->id() && !auth()->user()?->isAdmin()) {
            abort(403);
        }

        $validated = $request->validate([
            'message' => ['required', 'string'],
        ]);

        TicketReply::create([
            'support_ticket_id' => $supportTicket->id,
            'user_id' => auth()->id(),
            'message' => $validated['message'],
        ]);

        if ($supportTicket->status === 'Closed') {
            $supportTicket->update(['status' => 'Open']);
        }

        return back()->with('success', 'Reply added successfully.');
    }
}
