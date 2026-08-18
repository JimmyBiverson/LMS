@php
$isAdmin = $isAdmin ?? false;
$scopeType = $meeting->scope_type ?? 'course';
$timezone = $meeting->timezone ?? ($timezone ?? config('app.timezone', 'UTC'));
$timezones = [
    'Pacific/Honolulu' => '(GMT-10:00) Hawaii',
    'America/Anchorage' => '(GMT-09:00) Alaska',
    'America/Los_Angeles' => '(GMT-08:00) Pacific Time',
    'America/Denver' => '(GMT-07:00) Mountain Time',
    'America/Chicago' => '(GMT-06:00) Central Time',
    'America/New_York' => '(GMT-05:00) Eastern Time',
    'America/Caracas' => '(GMT-04:00) Caracas',
    'America/Sao_Paulo' => '(GMT-03:00) Brasilia',
    'UTC' => '(GMT+00:00) UTC',
    'Europe/London' => '(GMT+00:00) London',
    'Europe/Paris' => '(GMT+01:00) Paris',
    'Europe/Berlin' => '(GMT+01:00) Berlin',
    'Africa/Cairo' => '(GMT+02:00) Cairo',
    'Europe/Moscow' => '(GMT+03:00) Moscow',
    'Asia/Dubai' => '(GMT+04:00) Dubai',
    'Asia/Karachi' => '(GMT+05:00) Karachi',
    'Asia/Kolkata' => '(GMT+05:30) New Delhi',
    'Asia/Dhaka' => '(GMT+06:00) Dhaka',
    'Asia/Bangkok' => '(GMT+07:00) Bangkok',
    'Asia/Shanghai' => '(GMT+08:00) Beijing / Singapore',
    'Asia/Tokyo' => '(GMT+09:00) Tokyo',
    'Australia/Sydney' => '(GMT+10:00) Sydney',
    'Pacific/Auckland' => '(GMT+12:00) Auckland',
];
$localStart = $meeting ? $meeting->localStart($timezone) : \Illuminate\Support\Carbon::now()->addHour()->setTimezone($timezone)->format('Y-m-d\TH:i');
@endphp
<form method="POST" action="{{ $submitUrl }}" class="space-y-6">
    @csrf
    @if(($method ?? 'POST') === 'PUT')
        @method('PUT')
    @endif

    <div class="grid md:grid-cols-2 gap-5">
        <div>
            <label for="topic" class="block text-sm font-semibold text-heading mb-1.5">Meeting Title <span class="text-red-500">*</span></label>
            <input id="topic" name="topic" type="text" required value="{{ old('topic', $meeting->topic ?? '') }}" class="w-full rounded-lg border-gray-200 focus:ring-primary focus:border-primary text-sm" placeholder="e.g. Chapter 5 - Algebra Review">
            @error('topic')<p class="text-xs text-red-500 mt-1">{{ $message }}</p>@enderror
        </div>
        <div>
            <label for="agenda" class="block text-sm font-semibold text-heading mb-1.5">Agenda / Description</label>
            <textarea id="agenda" name="agenda" rows="2" class="w-full rounded-lg border-gray-200 focus:ring-primary focus:border-primary text-sm" placeholder="What will this class cover?">{{ old('agenda', $meeting->agenda ?? '') }}</textarea>
        </div>
    </div>

    <div class="grid md:grid-cols-2 gap-5">
        <div>
            <label for="scope_type" class="block text-sm font-semibold text-heading mb-1.5">Audience</label>
            <select id="scope_type" name="scope_type" x-data="{ scope: '{{ old('scope_type', $scopeType) }}' }" x-model="scope" class="w-full rounded-lg border-gray-200 focus:ring-primary focus:border-primary text-sm">
                <option value="course">One Course</option>
                @if($isAdmin)
                    <option value="institution">Entire Institution</option>
                @endif
            </select>
            <p class="text-xs text-gray-400 mt-1">Course meetings are visible only to students enrolled in that course. Institution-wide meetings reach every student.</p>
        </div>
        <div>
            <label for="timezone" class="block text-sm font-semibold text-heading mb-1.5">Timezone</label>
            <select id="timezone" name="timezone" class="w-full rounded-lg border-gray-200 focus:ring-primary focus:border-primary text-sm">
                @foreach($timezones as $tz => $label)
                    <option value="{{ $tz }}" @selected(old('timezone', $timezone) === $tz)>{{ $label }}</option>
                @endforeach
            </select>
        </div>
    </div>

    <div class="grid md:grid-cols-2 gap-5" x-data="{ courseId: '{{ old('course_id', $meeting->course_id ?? '') }}' }">
        <div>
            <label for="course_id" class="block text-sm font-semibold text-heading mb-1.5">Course <span class="text-red-500">*</span></label>
            <select id="course_id" name="course_id" x-model="courseId" required class="w-full rounded-lg border-gray-200 focus:ring-primary focus:border-primary text-sm">
                <option value="">Select course</option>
                @foreach($courses as $course)
                    <option value="{{ $course->id }}" @selected(old('course_id', $meeting->course_id ?? '') == $course->id)>{{ $course->title }}</option>
                @endforeach
            </select>
            @error('course_id')<p class="text-xs text-red-500 mt-1">{{ $message }}</p>@enderror
        </div>
        <div>
            <label for="lesson_id" class="block text-sm font-semibold text-heading mb-1.5">Lesson (optional)</label>
            <select id="lesson_id" name="lesson_id" class="w-full rounded-lg border-gray-200 focus:ring-primary focus:border-primary text-sm">
                <option value="">Whole course</option>
                @foreach($lessonsByCourse as $courseId => $lessons)
                    <template x-if="courseId == '{{ $courseId }}'">
                        <optgroup label="{{ $courseTitle = ($courses->firstWhere('id', $courseId)->title ?? 'Course') }}">
                            @foreach($lessons as $lesson)
                                <option value="{{ $lesson->id }}" @selected(old('lesson_id', $meeting->lesson_id ?? '') == $lesson->id)>{{ $lesson->title }}</option>
                            @endforeach
                        </optgroup>
                    </template>
                @endforeach
            </select>
            <p class="text-xs text-gray-400 mt-1">Restrict this meeting to a single lesson if you like.</p>
        </div>
    </div>

    <div class="grid md:grid-cols-3 gap-5">
        <div>
            <label for="start_time" class="block text-sm font-semibold text-heading mb-1.5">Start Time <span class="text-red-500">*</span></label>
            <input id="start_time" name="start_time" type="datetime-local" required value="{{ old('start_time', $localStart) }}" class="w-full rounded-lg border-gray-200 focus:ring-primary focus:border-primary text-sm">
            @error('start_time')<p class="text-xs text-red-500 mt-1">{{ $message }}</p>@enderror
        </div>
        <div>
            <label for="duration_minutes" class="block text-sm font-semibold text-heading mb-1.5">Duration (minutes)</label>
            <input id="duration_minutes" name="duration_minutes" type="number" min="1" max="1440" value="{{ old('duration_minutes', $meeting->duration_minutes ?? 60) }}" class="w-full rounded-lg border-gray-200 focus:ring-primary focus:border-primary text-sm">
        </div>
        <div>
            <label for="password" class="block text-sm font-semibold text-heading mb-1.5">Meeting Passcode</label>
            <input id="password" name="password" type="text" value="{{ old('password', $meeting->password ?? '') }}" placeholder="Optional" class="w-full rounded-lg border-gray-200 focus:ring-primary focus:border-primary text-sm">
        </div>
    </div>

    <div class="grid md:grid-cols-3 gap-4">
        <label class="flex items-start gap-3 p-4 rounded-xl border border-gray-200 bg-gray-50/60 cursor-pointer">
            <input type="checkbox" name="is_recurring" value="1" @checked(old('is_recurring', $meeting->is_recurring ?? false)) class="mt-1 rounded border-gray-300 text-primary focus:ring-primary">
            <span>
                <span class="block text-sm font-semibold text-heading">Recurring series</span>
                <span class="block text-xs text-gray-500">Useful for a repeating weekly class.</span>
            </span>
        </label>
        <label class="flex items-start gap-3 p-4 rounded-xl border border-gray-200 bg-gray-50/60 cursor-pointer">
            <input type="checkbox" name="auto_recording" value="1" @checked(old('auto_recording', $meeting->auto_recording ?? false)) class="mt-1 rounded border-gray-300 text-primary focus:ring-primary">
            <span>
                <span class="block text-sm font-semibold text-heading">Auto recording</span>
                <span class="block text-xs text-gray-500">Record automatically in Zoom's cloud.</span>
            </span>
        </label>
        <label class="flex items-start gap-3 p-4 rounded-xl border border-gray-200 bg-gray-50/60 cursor-pointer">
            <input type="checkbox" name="waiting_room" value="1" @checked(old('waiting_room', $meeting->waiting_room ?? false)) class="mt-1 rounded border-gray-300 text-primary focus:ring-primary">
            <span>
                <span class="block text-sm font-semibold text-heading">Waiting room</span>
                <span class="block text-xs text-gray-500">Admit participants individually.</span>
            </span>
        </label>
    </div>

    <div class="flex items-center gap-3 pt-2">
        <button type="submit" class="inline-flex items-center gap-2 px-5 py-2.5 rounded-lg bg-primary text-white text-sm font-bold hover:opacity-90">
            <i class="ri-calendar-event-line"></i>{{ ($method ?? 'POST') === 'PUT' ? 'Update Meeting' : 'Schedule Meeting' }}
        </button>
        <a href="{{ $cancelUrl }}" class="inline-flex items-center gap-2 px-5 py-2.5 rounded-lg border border-gray-200 text-sm font-semibold text-heading/70 hover:bg-gray-50">Cancel</a>
    </div>
</form>
