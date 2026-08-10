@php
$view = $calendar['view'];
$current = $calendar['current'];
$days = $calendar['days'];
$lastDay = $days->last()['date'];
$title = $view === 'week'
    ? $days[0]['date']->format('M j') . ' - ' . $lastDay->format('M j, Y')
    : ($view === 'day' ? $current->format('l, F j, Y') : $current->format('F Y'));

function zoomFmt($iso, $tz) {
    return \Carbon\Carbon::parse($iso)->setTimezone($tz)->format('g:i A');
}
$eventTz = $calendar['timezone'] ?? config('app.timezone', 'UTC');
@endphp
<div class="bg-white rounded-xl shadow-sm overflow-hidden">
    <div class="p-5 border-b border-gray-100 flex flex-wrap items-center justify-between gap-3">
        <div class="flex items-center gap-2">
            <a href="{{ route($routePrefix.'.calendar', ['view' => $view, 'date' => $calendar['prev']]) }}" class="w-9 h-9 inline-flex items-center justify-center rounded-lg border border-gray-200 text-heading/60 hover:bg-gray-50"><i class="ri-arrow-left-s-line"></i></a>
            <a href="{{ route($routePrefix.'.calendar', ['view' => $view]) }}" class="px-3 h-9 inline-flex items-center rounded-lg border border-gray-200 text-xs font-semibold text-heading/70 hover:bg-gray-50">Today</a>
            <a href="{{ route($routePrefix.'.calendar', ['view' => $view, 'date' => $calendar['next']]) }}" class="w-9 h-9 inline-flex items-center justify-center rounded-lg border border-gray-200 text-heading/60 hover:bg-gray-50"><i class="ri-arrow-right-s-line"></i></a>
            <h3 class="font-bold text-heading ml-2">{{ $title }}</h3>
        </div>
        <div class="flex items-center gap-2 flex-wrap">
            <div class="flex rounded-lg border border-gray-200 overflow-hidden text-xs font-semibold">
                @foreach(['month' => 'Month', 'week' => 'Week', 'day' => 'Day'] as $k => $lbl)
                    <a href="{{ route($routePrefix.'.calendar', ['view' => $k, 'date' => $current->toDateString()]) }}" class="px-3 py-2 {{ $view === $k ? 'bg-primary text-white' : 'text-heading/60 hover:bg-gray-50' }}">{{ $lbl }}</a>
                @endforeach
            </div>
            <a href="{{ route($routePrefix.'.calendar.ics', ['start' => $days[0]['date']->toDateString(), 'end' => $lastDay->toDateString()]) }}" class="inline-flex items-center gap-1.5 px-3 h-9 rounded-lg border border-gray-200 text-xs font-semibold text-heading/70 hover:bg-gray-50"><i class="ri-file-download-line"></i> iCal</a>
        </div>
    </div>

    @if($view === 'month')
        <div class="grid grid-cols-7 border-b border-gray-100">
            @foreach(['Sun', 'Mon', 'Tue', 'Wed', 'Thu', 'Fri', 'Sat'] as $d)
                <div class="px-2 py-2 text-[11px] font-bold uppercase tracking-wide text-heading/50 text-center">{{ $d }}</div>
            @endforeach
        </div>
        <div class="grid grid-cols-7">
            @foreach($days as $day)
                <div class="min-h-28 border-b border-r border-gray-100 p-2 {{ !$day['in_month'] ? 'bg-gray-50/70' : '' }}">
                    <div class="text-xs font-bold mb-1.5 {{ $day['date']->isToday() ? 'text-primary' : 'text-heading/70' }}">{{ $day['date']->format('j') }}</div>
                    <div class="space-y-1">
                        @forelse($day['events'] as $event)
                            <a href="{{ $event['url'] }}" class="block px-1.5 py-1 rounded-md text-[10px] leading-tight font-semibold truncate
                                @if($event['status'] === 'live') bg-red-50 text-red-700 border border-red-200
                                @elseif($event['status'] === 'starting_soon') bg-amber-50 text-amber-700 border border-amber-200
                                @elseif($event['status'] === 'cancelled') bg-rose-50 text-rose-500 border border-rose-100 line-through
                                @else bg-blue-50 text-blue-700 border border-blue-100 @endif" title="{{ $event['title'] }}">
                                {{ zoomFmt($event['start'], $event['timezone']) }} {{ $event['title'] }}
                            </a>
                        @empty
                            <div class="hidden md:block text-[10px] text-gray-300">-</div>
                        @endforelse
                    </div>
                </div>
            @endforeach
        </div>
    @elseif($view === 'week')
        <div class="grid grid-cols-7 border-b border-gray-100">
            @foreach($days as $day)
                <div class="px-2 py-2 text-center {{ $day['date']->isToday() ? 'text-primary' : 'text-heading/50' }}">
                    <div class="text-[11px] font-bold uppercase">{{ $day['date']->format('D') }}</div>
                    <div class="text-sm font-extrabold">{{ $day['date']->format('j') }}</div>
                </div>
            @endforeach
        </div>
        <div class="grid grid-cols-7">
            @foreach($days as $day)
                <div class="min-h-40 border-b border-r border-gray-100 p-1.5 space-y-1">
                    @forelse($day['events'] as $event)
                        <a href="{{ $event['url'] }}" class="block px-1.5 py-1 rounded-md text-[10px] leading-tight font-semibold truncate
                            @if($event['status'] === 'live') bg-red-50 text-red-700 border border-red-200
                            @elseif($event['status'] === 'starting_soon') bg-amber-50 text-amber-700 border border-amber-200
                            @elseif($event['status'] === 'cancelled') bg-rose-50 text-rose-500 border border-rose-100 line-through
                            @else bg-blue-50 text-blue-700 border border-blue-100 @endif" title="{{ $event['title'] }}">
                            {{ zoomFmt($event['start'], $event['timezone']) }}
                        </a>
                    @empty
                        <div class="hidden md:block text-[10px] text-gray-300">-</div>
                    @endforelse
                </div>
            @endforeach
        </div>
    @else
        <div class="divide-y divide-gray-100">
            @forelse($days[0]['events'] as $event)
                <div class="flex items-center gap-4 px-5 py-3.5 hover:bg-gray-50/70">
                    <div class="w-24 shrink-0 text-sm font-bold text-heading/80">{{ zoomFmt($event['start'], $event['timezone']) }}</div>
                    <div class="w-3 h-3 rounded-full shrink-0
                        @if($event['status'] === 'live') bg-red-500
                        @elseif($event['status'] === 'starting_soon') bg-amber-500
                        @elseif($event['status'] === 'cancelled') bg-rose-300
                        @else bg-blue-500 @endif"></div>
                    <a href="{{ $event['url'] }}" class="flex-1 min-w-0">
                        <p class="text-sm font-semibold text-heading truncate">{{ $event['title'] }}</p>
                        <p class="text-xs text-gray-500 truncate">{{ $event['course'] ?? '' }}</p>
                    </a>
                    <span class="text-xs font-semibold text-heading/50 capitalize">{{ $event['status'] }}</span>
                </div>
            @empty
                <div class="px-5 py-12 text-center">
                    <i class="ri-calendar-line text-3xl text-gray-300"></i>
                    <p class="mt-2 text-sm text-gray-400">No meetings scheduled for this day.</p>
                </div>
            @endforelse
        </div>
    @endif
</div>
