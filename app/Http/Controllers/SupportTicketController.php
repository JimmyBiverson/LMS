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
        $isInstructorOfCourse = false;
        if ($supportTicket->course_id) {
            $isInstructorOfCourse = \App\Models\Course::where('id', $supportTicket->course_id)
                ->where('user_id', auth()->id())
                ->exists();
        }

        if ($supportTicket->user_id !== auth()->id() && !auth()->user()?->isAdmin() && !$isInstructorOfCourse) {
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
            'course_id' => ['nullable', 'exists:courses,id'],
        ]);

        $validated['user_id'] = auth()->id();

        $ticket = SupportTicket::create($validated);
        
        // Send notification to instructor and admins
        \App\Notifications\SupportTicketCreated::send($ticket);

        return redirect()->route('dashboard.supports')->with('success', 'Support ticket created successfully!');
    }

    public function reply(Request $request, SupportTicket $supportTicket): RedirectResponse
    {
        $isInstructorOfCourse = false;
        if ($supportTicket->course_id) {
            $isInstructorOfCourse = \App\Models\Course::where('id', $supportTicket->course_id)
                ->where('user_id', auth()->id())
                ->exists();
        }

        if ($supportTicket->user_id !== auth()->id() && !auth()->user()?->isAdmin() && !$isInstructorOfCourse) {
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

        // Send notification about this reply
        \App\Notifications\SupportTicketReply::send($supportTicket, auth()->user());

        return back()->with('success', 'Reply added successfully.');
    }
}
