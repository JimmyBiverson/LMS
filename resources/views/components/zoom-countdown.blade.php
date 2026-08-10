@php
$startIso = $meeting->start_time->toIso8601String();
$endIso = $meeting->endTime()->toIso8601String();
@endphp
<span
    x-data="zoomCountdown({ start: '{{ $startIso }}', end: '{{ $endIso }}' })"
    x-init="init()"
    class="inline-flex items-center gap-1.5 font-bold text-sm tabular-nums"
    :class="labelClass"
>
    <i class="ri-time-line" x-show="phase !== 'live'"></i>
    <i class="ri-vidicon-line animate-pulse" x-show="phase === 'live'" x-cloak></i>
    <span x-text="label"></span>
</span>
@once
@push('scripts')
<script>
document.addEventListener('alpine:init', () => {
    Alpine.data('zoomCountdown', (cfg) => ({
        start: new Date(cfg.start).getTime(),
        end: new Date(cfg.end).getTime(),
        now: Date.now(),
        label: '',
        labelClass: 'text-heading',
        phase: 'upcoming',
        init() {
            this.tick();
            setInterval(() => this.tick(), 1000);
        },
        tick() {
            this.now = Date.now();
            if (this.now < this.start) {
                this.phase = 'upcoming';
                const s = Math.max(0, Math.floor((this.start - this.now) / 1000));
                const d = Math.floor(s / 86400);
                const h = Math.floor((s % 86400) / 3600);
                const m = Math.floor((s % 3600) / 60);
                const sec = s % 60;
                this.label = (d > 0 ? d + 'd ' : '') + this.pad(h) + ':' + this.pad(m) + ':' + this.pad(sec);
                this.labelClass = 'text-blue-600';
            } else if (this.now < this.end) {
                this.phase = 'live';
                const s = Math.max(0, Math.floor((this.end - this.now) / 1000));
                const m = Math.floor(s / 60);
                const sec = s % 60;
                this.label = 'Live - ' + m + ':' + this.pad(sec);
                this.labelClass = 'text-red-600';
            } else {
                this.phase = 'ended';
                this.label = 'Ended';
                this.labelClass = 'text-gray-400';
            }
        },
        pad(n) { return n < 10 ? '0' + n : '' + n; }
    }));
});
</script>
@endpush
@endonce
