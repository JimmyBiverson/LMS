@php
$status = $meeting->status;
@endphp
<div class="bg-white rounded-xl border border-gray-200 shadow-sm p-5 flex flex-col">
    <div class="flex items-start justify-between gap-2 mb-2">
        <span class="text-xs font-bold text-heading/50 uppercase tracking-wide">{{ $meeting->course->title }}</span>
        <x-zoom-status-badge :status="$status" />
    </div>
    <h3 class="font-bold text-heading leading-snug">{{ $meeting->topic }}</h3>
    @if($meeting->lesson)
        <p class="text-xs text-gray-500 mt-0.5">Lesson: {{ $meeting->lesson->title }}</p>
    @endif
    <p class="text-xs text-gray-500 mt-1.5">
        <i class="ri-calendar-line mr-1"></i>{{ $meeting->start_time->format('D, M d, Y') }}
        <span class="mx-1">·</span>
        {{ $meeting->start_time->setTimezone($meeting->timezone)->format('h:i A') }} - {{ $meeting->endTime()->setTimezone($meeting->timezone)->format('h:i A') }}
    </p>
    <div class="mt-3">
        <x-zoom-countdown :meeting="$meeting" />
    </div>
    <div class="mt-4 flex items-center gap-2 pt-3 border-t border-gray-50">
        @if($meeting->isJoinableNow())
            <form method="POST" action="{{ route('zoom.join', $meeting) }}">
                @csrf
                <button class="inline-flex items-center gap-2 px-4 py-2 rounded-lg bg-primary text-white text-sm font-bold hover:opacity-90">
                    <i class="ri-vidicon-line"></i> Join Class
                </button>
            </form>
        @endif
        <a href="{{ route('zoom.show', $meeting) }}" class="inline-flex items-center px-3 py-2 rounded-lg border border-gray-200 text-sm font-semibold text-heading/70 hover:bg-gray-50">Details</a>
    </div>
</div>
