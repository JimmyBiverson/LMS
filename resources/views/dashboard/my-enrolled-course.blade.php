@extends('layouts.dashboard')
@section('title', 'My Enrolled Course')
@section('page-title', 'My Enrolled Course')
@section('user-name', 'Student')
@section('sidebar')@include('components.student-sidebar')@stop
@section('content')
<div class="bg-white rounded-xl shadow-sm">
    <div class="p-6 border-b border-gray-100"><h3 class="font-bold text-heading">Enrolled Courses</h3></div>
    <div class="overflow-x-auto">
        <table class="w-full text-sm">
            <thead><tr class="bg-gray-50 text-heading/60 text-xs uppercase tracking-wider">
                <th class="text-left py-4 px-6 font-semibold">#</th>
                <th class="text-left py-4 px-6 font-semibold">Course Name</th>
                <th class="text-left py-4 px-6 font-semibold">Instructor</th>
                <th class="text-left py-4 px-6 font-semibold">Price</th>
                <th class="text-left py-4 px-6 font-semibold">Progress</th>
                <th class="text-left py-4 px-6 font-semibold">Status</th>
                <th class="text-right py-4 px-6 font-semibold">Action</th>
            </tr></thead>
            <tbody class="divide-y divide-gray-100">
                @forelse($enrollments as $i=>$e)
                <tr class="hover:bg-gray-50 transition-colors">
                    <td class="py-4 px-6 text-heading/70">{{ $i+1 }}</td>
                    <td class="py-4 px-6 font-semibold text-heading">{{ $e->course?->title ?? 'Deleted Course' }}</td>
                    <td class="py-4 px-6 text-heading/70">{{ $e->course?->instructor?->name ?? 'N/A' }}</td>
                    <td class="py-4 px-6 text-heading/70">{{ $e->amount_paid > 0 ? '$'.number_format($e->amount_paid,2) : 'Free' }}</td>
                    @php $p = $progress[$e->id] ?? ['total'=>0,'completed'=>0]; $pct = $p['total'] > 0 ? round(($p['completed']/$p['total'])*100) : 0; @endphp
                    <td class="py-4 px-6"><div class="flex items-center gap-2"><div class="flex-1 h-2 bg-gray-100 rounded-full"><div class="h-full bg-primary rounded-full" style="width:{{ $pct }}%"></div></div><span class="text-xs text-heading/60">{{ $pct }}%</span></div></td>
                    <td class="py-4 px-6"><span class="px-3 py-1 rounded-full text-xs font-bold {{ $e->status==='completed' ? 'bg-green-100 text-green-700' : 'bg-amber-100 text-amber-700' }}">{{ ucfirst(str_replace('_',' ',$e->status ?? 'in_progress')) }}</span></td>
                    <td class="py-4 px-6 text-right"><a href="/courses/{{ $e->course?->slug ?? $e->course_id }}" class="text-primary text-sm font-semibold hover:underline">View</a></td>
                </tr>
                @empty
                <tr><td colspan="7" class="py-8 text-center text-heading/50 text-sm">You haven't enrolled in any courses yet. <a href="/courses" class="text-primary font-semibold hover:underline">Browse courses</a></td></tr>
                @endforelse
            </tbody>
        </table>
    </div>
    @if(method_exists($enrollments, 'hasPages') && $enrollments->hasPages())
    <div class="p-6 flex items-center justify-center gap-2">{{ $enrollments->links() }}</div>
    @endif
</div>
@endsection
