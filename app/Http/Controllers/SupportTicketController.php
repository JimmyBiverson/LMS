<?php

namespace App\Http\Controllers;

use App\Models\SupportTicket;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;

class SupportTicketController extends Controller
{
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

        return back()->with('success', 'Support ticket created successfully!');
    }
}
