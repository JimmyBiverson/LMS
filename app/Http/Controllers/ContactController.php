<?php

namespace App\Http\Controllers;

use App\Models\ContactMessage;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;

class ContactController extends Controller
{
    public function send(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'email', 'max:255'],
            'phone' => ['required', 'string', 'max:20'],
            'subject' => ['required', 'string', 'max:255'],
            'message' => ['required', 'string'],
        ]);

        ContactMessage::create(array_merge($validated, ['type' => 'contact']));

        return redirect()->route('contact')
            ->with('success', 'Your message has been sent successfully! We will get back to you soon.');
    }

    public function instructorContact(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'email', 'max:255'],
            'phone' => ['required', 'string', 'max:20'],
            'subject' => ['required', 'string', 'max:255'],
            'message' => ['required', 'string'],
            'instructor_id' => ['required', 'exists:users,id'],
        ]);

        ContactMessage::create(array_merge($validated, ['type' => 'instructor_contact']));

        return back()->with('success', 'Your message has been sent to the instructor successfully!');
    }
}
