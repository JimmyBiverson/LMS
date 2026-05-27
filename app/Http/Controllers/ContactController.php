<?php

namespace App\Http\Controllers;

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

        $data = "Contact Inquiry\nName: {$validated['name']}\nEmail: {$validated['email']}\nPhone: {$validated['phone']}\nSubject: {$validated['subject']}\nMessage: {$validated['message']}";
        logger($data);

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
        ]);

        $data = "Instructor Contact\nName: {$validated['name']}\nEmail: {$validated['email']}\nPhone: {$validated['phone']}\nSubject: {$validated['subject']}\nMessage: {$validated['message']}";
        logger($data);

        return back()->with('success', 'Your message has been sent to the instructor successfully!');
    }
}
