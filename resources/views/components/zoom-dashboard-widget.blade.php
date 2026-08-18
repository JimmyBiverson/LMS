@php
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Schema;
use App\Models\ZoomMeeting;

$zoomWidget = ['ok' => false, 'live' => collect(), 'upcoming' => collect(), 'count' => 0];

if (auth()->check()) {
    $zoomWidget['ok'] = Schema::hasTable('zoom_meetings');

    if ($zoomWidget['ok']) {
        try {
            $user = auth()->user();
            $meetings = Cache::remember('zoom.user.'.$user->id.'.widget', 60, function () use ($user) {
                return ZoomMeeting::with(['course', 'lesson'])
                    ->visibleTo($user)
                    ->whereIn('status', [ZoomMeeting::STATUS_SCHEDULED, ZoomMeeting::STATUS_STARTING_SOON, ZoomMeeting::STATUS_LIVE])
                    ->orderBy('start_time')
                    ->take(6)
                    ->get();
            });
            $zoomWidget['live'] = $meetings->filter(fn ($m) => $m->computeStatus() === ZoomMeeting::STATUS_LIVE && $m->isJoinableNow())->values();
            $zoomWidget['upcoming'] = $meetings->filter(fn ($m) => $m->computeStatus() !== ZoomMeeting::STATUS_LIVE)->values();
            $zoomWidget['count'] = $meetings->count();
        } catch (\Throwable $e) {
            $zoomWidget['ok'] = false;
        }
    }

    $zoomRole = auth()->user()->isAdmin() || auth()->user()->isStaff() ? 'admin' : (auth()->user()->isInstructor() ? 'instructor' : 'student');
    $zoomHub = $zoomRole === 'admin'
        ? route('zoom.admin.index')
        : ($zoomRole === 'instructor' ? route('zoom.instructor.index') : route('zoom.index'));
}

$accent = $zoomRole === 'admin' ? 'sky' : ($zoomRole === 'instructor' ? 'indigo' : 'emerald');
@endphp

@if($zoomWidget['ok'] ?? false)
<div class="bg-white rounded-xl shadow-sm mb-8">
    <div class="p-5 border-b border-gray-100 flex items-center justify-between">
        <h3 class="font-bold text-heading flex items-center gap-2">
            <span class="w-1.5 h-5 bg-primary rounded-full"></span>
            <i class="ri-video-on-line text-primary"></i> Zoom Classes
        </h3>
        <a href="{{ $zoomHub }}" class="inline-flex items-center gap-1 text-xs font-bold text-primary hover:underline">
            Open Classroom <i class="ri-arrow-right-s-line"></i>
        </a>
    </div>

    @if($zoomWidget['live']->isNotEmpty())
        <div class="p-5 border-b border-gray-100 bg-red-50/50">
            @foreach($zoomWidget['live'] as $m)
                <div class="flex flex-wrap items-center justify-between gap-3">
                    <div class="flex items-center gap-3 min-w-0">
                        <span class="relative flex h-2.5 w-2.5 shrink-0">
                            <span class="animate-ping absolute inline-flex h-full w-full rounded-full bg-red-500 opacity-75"></span>
                            <span class="relative inline-flex rounded-full h-2.5 w-2.5 bg-red-600"></span>
                        </span>
                        <div class="min-w-0">
                            <p class="text-sm font-bold text-red-700 truncate">{{ $m->topic }}</p>
                            <p class="text-xs text-red-500/70 truncate">{{ $m->course?->title ?? 'Institution-wide' }}</p>
                        </div>
                    </div>
                    <form method="POST" action="{{ route($zoomRole === 'admin' ? 'zoom.admin.start' : ($zoomRole === 'instructor' ? 'zoom.instructor.start' : 'zoom.join'), $m) }}">
                        @csrf
                        <button class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-lg bg-red-600 text-white text-xs font-bold hover:bg-red-700">
                            <i class="ri-vidicon-line"></i>{{ $zoomRole === 'admin' || $zoomRole === 'instructor' ? 'Start' : 'Join' }}
                        </button>
                    </form>
                </div>
            @endforeach
        </div>
    @endif

    @if($zoomWidget['upcoming']->isNotEmpty())
        <div class="p-5 space-y-3">
            @foreach($zoomWidget['upcoming']->take(3) as $m)
                <div class="flex items-center justify-between gap-3">
                    <div class="min-w-0">
                        <p class="text-sm font-semibold text-heading truncate">{{ $m->topic }}</p>
                        <p class="text-xs text-gray-400">{{ $m->start_time->setTimezone($m->timezone)->format('D, M j · g:i A') }}</p>
                    </div>
                    <x-zoom-countdown :meeting="$m" />
                </div>
            @endforeach
        </div>
    @endif

    @if($zoomWidget['live']->isEmpty() && $zoomWidget['upcoming']->isEmpty())
        <div class="p-5 flex items-center justify-between">
            <div class="flex items-center gap-3">
                <span class="w-10 h-10 rounded-lg bg-gray-100 text-gray-400 inline-flex items-center justify-center"><i class="ri-video-on-line"></i></span>
                <div>
                    <p class="text-sm font-semibold text-heading">No live classes right now</p>
                    <p class="text-xs text-gray-400">
                        {{ $zoomRole === 'admin' ? 'Monitor and schedule classes from the Zoom Classroom.' : ($zoomRole === 'instructor' ? 'Schedule your next live class.' : 'When your instructors schedule classes they appear here.') }}
                    </p>
                </div>
            </div>
            <a href="{{ $zoomHub }}" class="shrink-0 inline-flex items-center gap-1.5 px-3 py-2 rounded-lg border border-gray-200 text-xs font-bold text-heading/70 hover:bg-gray-50">
                {{ $zoomRole === 'admin' ? 'Manage' : ($zoomRole === 'instructor' ? 'Schedule' : 'View') }}
            </a>
        </div>
    @endif
</div>
@endif
