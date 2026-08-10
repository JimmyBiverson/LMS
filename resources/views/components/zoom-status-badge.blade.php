@php
$map = [
    'scheduled' => ['bg-blue-100 text-blue-700', 'ri-calendar-check-line', 'Scheduled'],
    'starting_soon' => ['bg-amber-100 text-amber-700', 'ri-time-line', 'Starting Soon'],
    'live' => ['bg-red-100 text-red-700', 'ri-vidicon-line', 'Live'],
    'ended' => ['bg-gray-100 text-gray-600', 'ri-checkbox-circle-line', 'Ended'],
    'cancelled' => ['bg-rose-100 text-rose-700', 'ri-close-circle-line', 'Cancelled'],
];
$s = $map[$status] ?? $map['scheduled'];
@endphp
<span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-full text-xs font-bold {{ $s[0] }}">
    <i class="{{ $s[1] }}"></i>{{ $s[2] }}
</span>
