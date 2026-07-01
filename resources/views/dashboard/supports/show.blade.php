@extends('layouts.dashboard')
@section('title', 'Ticket #' . $supportTicket->id)
@section('page-title', 'Ticket Details')
@section('user-name', auth()->user()->role === 'admin' ? 'Admin' : (auth()->user()->role === 'instructor' ? 'Instructor' : 'Student'))
@section('sidebar')@include(auth()->user()->role === 'admin' ? 'components.admin-sidebar' : (auth()->user()->role === 'instructor' ? 'components.instructor-sidebar' : 'components.student-sidebar'))@stop
@section('content')
@if(session('success'))<div class="mb-6 p-4 bg-green-50 border border-green-200 text-green-700 rounded-lg text-sm font-semibold">{{ session('success') }}</div>@endif

<div class="mb-6">
    <a href="{{ auth()->user()->isAdmin() ? '/admin/support-ticket/ticket' : (auth()->user()->isInstructor() ? '/instructor/supports' : '/dashboard/supports') }}" class="text-sm text-primary hover:underline flex items-center gap-1">
        <i class="ri-arrow-left-line"></i> Back to tickets
    </a>
</div>

<div class="bg-white rounded-xl shadow-sm p-6 mb-6">
    <div class="flex items-start justify-between mb-4">
        <div>
            <h3 class="text-xl font-bold text-heading">{{ $supportTicket->subject }}</h3>
            <p class="text-sm text-heading/60 mt-1">
                {{ $supportTicket->category }} &middot; {{ $supportTicket->priority }} &middot;
                <span class="px-2 py-0.5 rounded-full text-xs font-bold
                    {{ $supportTicket->status === 'Open' ? 'bg-green-100 text-green-700' : '' }}
                    {{ $supportTicket->status === 'Pending' ? 'bg-yellow-100 text-yellow-700' : '' }}
                    {{ $supportTicket->status === 'Closed' ? 'bg-gray-100 text-gray-700' : '' }}">
                    {{ $supportTicket->status }}
                </span>
            </p>
        </div>
        <p class="text-xs text-heading/40">{{ $supportTicket->created_at->format('M d, Y h:i A') }}</p>
    </div>
    <div class="bg-gray-50 rounded-lg p-4 text-sm text-heading/80 leading-relaxed">
        {{ $supportTicket->message }}
    </div>
</div>

<div class="bg-white rounded-xl shadow-sm p-6 mb-6">
    <h4 class="font-bold text-heading mb-4">Replies ({{ $supportTicket->replies->count() }})</h4>
    <div class="space-y-4">
        @forelse($supportTicket->replies as $reply)
        <div class="flex gap-3 {{ $reply->user_id === $supportTicket->user_id ? '' : 'flex-row-reverse' }}">
            <div class="w-8 h-8 rounded-full bg-primary-50 flex items-center justify-center shrink-0">
                <i class="ri-user-line text-xs text-primary"></i>
            </div>
            <div class="flex-1 {{ $reply->user_id === $supportTicket->user_id ? '' : 'text-right' }}">
                <div class="inline-block bg-gray-50 rounded-lg px-4 py-3 text-sm text-heading/80 leading-relaxed max-w-lg {{ $reply->user_id !== $supportTicket->user_id ? 'bg-primary/5' : '' }}">
                    <p class="font-semibold text-xs text-heading mb-1">{{ $reply->user->name }}</p>
                    <p>{{ $reply->message }}</p>
                </div>
                <p class="text-xs text-heading/40 mt-1">{{ $reply->created_at->diffForHumans() }}</p>
            </div>
        </div>
        @empty
        <p class="text-center text-heading/40 text-sm py-8">No replies yet.</p>
        @endforelse
    </div>
</div>

@if($supportTicket->status !== 'Closed' || auth()->user()->isAdmin())
<div class="bg-white rounded-xl shadow-sm p-6">
    <h4 class="font-bold text-heading mb-4">Add Reply</h4>
    <form method="POST" action="/dashboard/supports/{{ $supportTicket->id }}/reply">
        @csrf
        <textarea name="message" rows="4" required class="w-full px-4 py-3 rounded-lg border border-heading/10 text-sm focus:outline-none focus:border-primary" placeholder="Type your reply..."></textarea>
        <button type="submit" class="mt-3 px-6 py-2.5 bg-primary text-white font-semibold rounded-lg hover:opacity-90 text-sm">Send Reply</button>
    </form>
</div>
@endif
@endsection
