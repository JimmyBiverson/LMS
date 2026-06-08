@extends('layouts.app')
@section('title', 'Discussions - ' . $course->title)
@section('content')
<section class="bg-[#F7F4FF] py-12">
    <div class="max-w-4xl mx-auto px-4">
        <h1 class="text-4xl font-extrabold text-heading mb-2">Discussions</h1>
        <div class="flex items-center gap-2 text-sm text-heading/60">
            <a href="/" class="hover:text-primary transition-colors">Home</a><i class="ri-arrow-right-s-line"></i>
            <a href="/courses/{{ $course->slug }}" class="hover:text-primary transition-colors">{{ $course->title }}</a><i class="ri-arrow-right-s-line"></i>
            <span class="text-primary font-semibold">Discussions</span>
        </div>
    </div>
</section>
<section class="py-12 lg:py-16">
    <div class="max-w-4xl mx-auto px-4">
        @auth
        <div class="bg-white rounded-xl shadow-sm p-6 mb-8">
            <h3 class="font-bold text-heading mb-4">Start a Discussion</h3>
            @if (session('success'))
            <div class="mb-4 p-4 bg-green-50 border border-green-200 text-green-700 rounded-lg text-sm font-semibold">{{ session('success') }}</div>
            @endif
            <form method="POST" action="/courses/{{ $course->id }}/discussions">
                @csrf
                <textarea name="body" rows="3" class="w-full px-4 py-3 rounded-lg border border-heading/10 text-sm focus:outline-none focus:border-primary" placeholder="Ask a question or start a discussion..." required></textarea>
                @error('body')<p class="text-red-500 text-sm mt-1">{{ $message }}</p>@enderror
                <button type="submit" class="mt-3 px-6 py-3 bg-primary text-white font-bold rounded-full hover:opacity-90 transition-all text-sm">Post Discussion</button>
            </form>
        </div>
        @endauth

        <div class="space-y-4">
            @forelse ($discussions as $discussion)
            <div class="bg-white rounded-xl shadow-sm p-6">
                <div class="flex items-start gap-4">
                    <div class="w-10 h-10 rounded-full bg-primary-50 flex items-center justify-center shrink-0"><i class="ri-user-smile-line text-primary"></i></div>
                    <div class="flex-1 min-w-0">
                        <div class="flex items-center gap-2 mb-1">
                            <span class="font-semibold text-heading text-sm">{{ $discussion->user->name ?? 'Deleted' }}</span>
                            <span class="text-xs text-heading/50">{{ $discussion->created_at->diffForHumans() }}</span>
                            @can('delete', $discussion)
                            <form method="POST" action="/courses/{{ $course->id }}/discussions/{{ $discussion->id }}/delete" class="ml-auto">@csrf<button type="submit" class="text-xs text-red-400 hover:text-red-600"><i class="ri-delete-bin-line"></i></button></form>
                            @endcan
                        </div>
                        <p class="text-heading/80 text-sm leading-relaxed">{{ $discussion->body }}</p>

                        @if ($discussion->replies->count() > 0)
                        <div class="mt-4 pl-4 border-l-2 border-primary-50 space-y-3">
                            @foreach ($discussion->replies as $reply)
                            <div class="flex items-start gap-3">
                                <div class="w-7 h-7 rounded-full bg-gray-100 flex items-center justify-center shrink-0"><i class="ri-user-smile-line text-xs text-heading/50"></i></div>
                                <div>
                                    <div class="flex items-center gap-2">
                                        <span class="font-semibold text-heading text-xs">{{ $reply->user->name ?? 'Deleted' }}</span>
                                        <span class="text-xs text-heading/50">{{ $reply->created_at->diffForHumans() }}</span>
                                    </div>
                                    <p class="text-heading/70 text-sm">{{ $reply->body }}</p>
                                </div>
                            </div>
                            @endforeach
                        </div>
                        @endif

                        @auth
                        <form method="POST" action="/courses/{{ $course->id }}/discussions/{{ $discussion->id }}/reply" class="mt-3 flex gap-2">
                            @csrf
                            <input name="body" type="text" placeholder="Write a reply..." class="flex-1 px-4 py-2 rounded-lg border border-heading/10 text-sm focus:outline-none focus:border-primary" required>
                            <button type="submit" class="px-4 py-2 bg-primary/10 text-primary text-sm font-semibold rounded-full hover:bg-primary hover:text-white transition-all">Reply</button>
                        </form>
                        @endauth
                    </div>
                </div>
            </div>
            @empty
            <div class="bg-white rounded-xl shadow-sm p-12 text-center">
                <i class="ri-question-answer-line text-5xl text-heading/20 mb-4"></i>
                <p class="text-heading/50">No discussions yet. Be the first to ask something!</p>
            </div>
            @endforelse
        </div>

        @if ($discussions->hasPages())
        <div class="mt-6">{{ $discussions->links() }}</div>
        @endif
    </div>
</section>
@endsection