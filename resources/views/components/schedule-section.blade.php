@props([
    'model',
    'action',
    'label' => 'item',
    'fields' => [],
    'showResults' => false,
])

@php
    $badgeClass = match($model->availabilityBadge()) {
        'draft' => 'bg-gray-100 text-gray-600',
        'scheduled' => 'bg-amber-100 text-amber-700',
        'available' => 'bg-green-100 text-green-700',
    };
    $badgeLabel = match($model->availabilityBadge()) {
        'draft' => 'Draft (not visible)',
        'scheduled' => $model->available_from ? 'Scheduled for ' . $model->available_from->format('M j, Y \a\t g:i A') : 'Scheduled',
        'available' => 'Available Now',
    };
@endphp

<div class="bg-white rounded-xl shadow-sm" x-data="{ showSchedule: {{ $model->available_from ? 'true' : 'false' }} }">
    <div class="p-6 border-b border-gray-100">
        <h3 class="font-bold text-heading flex items-center gap-2">
            <i class="ri-calendar-line text-primary text-xl"></i>
            Schedule &amp; Release
        </h3>
        <p class="text-sm text-heading/60 mt-1">Control when students see this {{ $label }}</p>
    </div>
    <div class="p-6 space-y-4">

        {{-- Availability Status --}}
        <div class="flex items-center gap-3 text-sm">
            <span class="font-semibold text-heading">Availability:</span>
            <span class="px-3 py-1 rounded-full text-xs font-bold {{ $badgeClass }}">{{ $badgeLabel }}</span>
        </div>

        {{-- Schedule Toggle --}}
        <div class="flex items-center justify-between p-3 bg-gray-50 rounded-lg">
            <div>
                <label class="text-sm font-semibold text-heading cursor-pointer" @click="showSchedule = !showSchedule">Schedule availability</label>
                <p class="text-xs text-heading/50">Set a specific date and time for students to access this {{ $label }}</p>
            </div>
            <label class="relative inline-flex items-center cursor-pointer">
                <input type="checkbox" @click="showSchedule = !showSchedule" :checked="showSchedule" class="sr-only peer">
                <div class="w-9 h-5 bg-gray-200 peer-focus:outline-none rounded-full peer peer-checked:after:translate-x-full after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:rounded-full after:h-4 after:w-4 after:transition-all peer-checked:bg-primary"></div>
            </label>
        </div>

        {{-- Schedule Form --}}
        <form method="POST" action="{{ $action }}" x-show="showSchedule" x-transition:enter="transition ease-out duration-200" x-transition:enter-start="opacity-0 scale-95" x-transition:enter-end="opacity-100 scale-100">
            @csrf
            @foreach($fields as $fieldName => $fieldValue)
                @if($fieldValue !== null && $fieldValue !== false)
                    <input type="hidden" name="{{ $fieldName }}" value="{{ $fieldValue }}">
                @endif
            @endforeach
            <div class="flex items-center gap-3">
                <input type="datetime-local" name="available_from" value="{{ $model->available_from?->format('Y-m-d\TH:i') }}" class="flex-1 px-4 py-2.5 border border-gray-200 rounded-lg text-sm focus:ring-2 focus:ring-primary/20 focus:border-primary outline-none">
                <button type="submit" class="px-6 py-2.5 bg-primary text-white font-semibold rounded-lg hover:opacity-90 text-sm whitespace-nowrap">
                    <i class="ri-calendar-check-line mr-1"></i>
                    @if($model->available_from) Update Schedule @else Set Schedule @endif
                </button>
                @if($model->available_from)
                <a href="#" onclick="event.preventDefault(); if(confirm('Clear the scheduled date?')) { this.closest('form').querySelector('[name=available_from]').value = ''; this.closest('form').submit(); }" class="px-4 py-2.5 bg-gray-100 text-gray-600 font-semibold rounded-lg hover:bg-gray-200 text-sm">Clear</a>
                @endif
            </div>
        </form>

        {{-- Results Release (quiz/exam only) --}}
        @if($showResults)
        <div class="border-t border-gray-100 pt-4">
            <div class="flex items-center justify-between mb-3">
                <div>
                    <h4 class="text-sm font-semibold text-heading">Results Release</h4>
                    <p class="text-xs text-heading/50">Students will not see their scores until you release them</p>
                </div>
                @if(method_exists($model, 'areResultsReleased') && $model->areResultsReleased())
                    <span class="px-3 py-1 rounded-full text-xs font-bold bg-green-100 text-green-700">
                        Released {{ $model->results_released_at->format('M j, Y') }}
                    </span>
                @else
                    <span class="px-3 py-1 rounded-full text-xs font-bold bg-amber-100 text-amber-700">Pending</span>
                @endif
            </div>
            @if($model->status === 'published' && (!method_exists($model, 'areResultsReleased') || !$model->areResultsReleased()))
            <form method="POST" action="{{ $action }}/release-results" onsubmit="return confirm('Release results to all students? This cannot be undone.');">
                @csrf
                <button type="submit" class="px-6 py-2.5 bg-green-600 text-white font-semibold rounded-lg hover:bg-green-700 text-sm flex items-center gap-2">
                    <i class="ri-share-line"></i> Release Results
                </button>
            </form>
            @endif
        </div>
        @endif

    </div>
</div>
