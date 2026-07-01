<?php

namespace App\Http\Controllers;

use App\Mail\DynamicMail;
use App\Models\NotificationLog;
use App\Models\NotificationTemplate;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use Illuminate\View\View;

class NotificationController extends Controller
{
    public function index(): View
    {
        $notifications = NotificationLog::where('user_id', auth()->id())
            ->latest()
            ->get();

        return view('dashboard.notifications', compact('notifications'));
    }

    public function markRead(Request $request, NotificationLog $notificationLog): RedirectResponse
    {
        if ($notificationLog->user_id !== auth()->id()) {
            abort(403);
        }

        $notificationLog->update(['is_read' => true]);

        return back();
    }

    public function markAllRead(): RedirectResponse
    {
        NotificationLog::where('user_id', auth()->id())
            ->where('is_read', false)
            ->update(['is_read' => true]);

        return back()->with('success', 'All notifications marked as read.');
    }

    public function sendForm(): View
    {
        $users = User::where('id', '!=', auth()->id())->where('status', 'active')->orderBy('name')->get();
        return view('dashboard.send-notification', compact('users'));
    }

    public function sendToUser(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'recipient_id' => ['required', 'exists:users,id'],
            'subject' => ['required', 'string', 'max:255'],
            'body' => ['required', 'string', 'max:2000'],
        ]);

        $recipient = User::findOrFail($validated['recipient_id']);

        NotificationLog::create([
            'user_id' => $recipient->id,
            'type' => 'in_app',
            'subject' => $validated['subject'],
            'body' => $validated['body'] . "\n\n— From: " . auth()->user()->name,
            'link' => url('dashboard/notifications'),
            'channel' => 'in_app',
            'sent_at' => now(),
        ]);

        return back()->with('success', 'Notification sent to ' . $recipient->name . '!');
    }

    public function sendTest(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'template_id' => ['required', 'exists:notification_templates,id'],
            'user_id' => ['nullable', 'exists:users,id'],
            'channel' => ['required', 'in:in_app,email,both'],
        ]);

        $template = NotificationTemplate::findOrFail($validated['template_id']);
        $userId = $validated['user_id'] ?? auth()->id();
        $user = User::findOrFail($userId);

        $this->dispatchNotification($user, $template, $validated['channel']);

        return back()->with('success', 'Notification sent successfully!');
    }

    private function dispatchNotification(User $user, NotificationTemplate $template, string $channel): void
    {
        $placeholders = [
            '{user_name}' => $user->name,
            '{user_email}' => $user->email,
            '{app_name}' => config('app.name'),
        ];

        $subject = str_replace(array_keys($placeholders), array_values($placeholders), $template->subject ?? $template->template_name);
        $body = str_replace(array_keys($placeholders), array_values($placeholders), $template->body ?? '');

        if (in_array($channel, ['in_app', 'both'])) {
            NotificationLog::create([
                'user_id' => $user->id,
                'notification_template_id' => $template->id,
                'type' => 'in_app',
                'subject' => $subject,
                'body' => $body,
                'channel' => 'in_app',
                'sent_at' => now(),
            ]);
        }

        if (in_array($channel, ['email', 'both'])) {
            try {
                Mail::to($user->email)->queue(new DynamicMail($subject, nl2br(e($body))));
            } catch (\Exception $e) {
                logger()->error('Failed to queue notification email: ' . $e->getMessage());
            }
        }
    }
}
