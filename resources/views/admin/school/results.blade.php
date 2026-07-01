@extends('layouts.dashboard')
@section('title', 'Result Management')
@section('page-title', 'Results')
@section('user-name', auth()->user()->name ?? 'Admin')
@section('sidebar')@include('components.admin-sidebar')@stop
@section('content')
@if(session('success'))
<div class="mb-6 px-6 py-4 bg-green-50 border border-green-200 text-green-700 rounded-xl text-sm font-semibold">{{ session('success') }}</div>
@endif
<div class="bg-white rounded-xl shadow-sm mb-6">
    <div class="p-6 border-b border-gray-100"><h3 class="font-bold text-heading">Add Result</h3></div>
    <div class="p-6">
        <form method="POST" action="/admin/school/results" class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4">
            @csrf
            <select name="exam_id" required class="px-4 py-2.5 border border-gray-200 rounded-lg text-sm">
                <option value="">Select Exam</option> @foreach($exams as $e) <option value="{{ $e->id }}">{{ $e->title }} ({{ $e->course?->title ?? '' }})</option> @endforeach
            </select>
            <select name="student_id" required class="px-4 py-2.5 border border-gray-200 rounded-lg text-sm">
                <option value="">Select Student</option>
            </select>
            <select name="course_id" required class="px-4 py-2.5 border border-gray-200 rounded-lg text-sm">
                <option value="">Select Course</option>
            </select>
            <input type="number" name="marks" placeholder="Marks Obtained" required step="0.01" class="px-4 py-2.5 border border-gray-200 rounded-lg text-sm">
            <textarea name="remarks" placeholder="Remarks" rows="2" class="px-4 py-2.5 border border-gray-200 rounded-lg text-sm md:col-span-2"></textarea>
            <button type="submit" class="px-6 py-2.5 bg-primary text-white font-semibold rounded-lg hover:opacity-90 text-sm">Save Result</button>
        </form>
    </div>
</div>
<div class="bg-white rounded-xl shadow-sm">
    <div class="p-6 border-b border-gray-100"><h3 class="font-bold text-heading">Results</h3></div>
    <div class="overflow-x-auto">
        <table class="w-full text-sm">
            <thead><tr class="bg-gray-50 text-heading/60 text-xs uppercase tracking-wider">
                <th class="text-left py-4 px-6 font-semibold">#</th><th class="text-left py-4 px-6 font-semibold">Student</th>
                <th class="text-left py-4 px-6 font-semibold">Exam</th><th class="text-left py-4 px-6 font-semibold">Course</th>
                <th class="text-left py-4 px-6 font-semibold">Marks</th><th class="text-left py-4 px-6 font-semibold">Grade</th><th class="text-left py-4 px-6 font-semibold">Action</th>
            </tr></thead>
            <tbody class="divide-y divide-gray-100">
                @forelse($results as $i=>$r)
                <tr class="hover:bg-gray-50">
                    <td class="py-4 px-6 text-heading/70">{{ $results->firstItem()+$i }}</td>
                    <td class="py-4 px-6 font-semibold text-heading">{{ $r->student?->name ?? '--' }}</td>
                    <td class="py-4 px-6 text-heading/70">{{ $r->exam?->title ?? '--' }}</td>
                    <td class="py-4 px-6 text-heading/70">{{ $r->course?->title ?? '--' }}</td>
                    <td class="py-4 px-6 text-heading/70">{{ $r->marks }}/{{ $r->total_marks }}</td>
                    <td class="py-4 px-6"><span class="px-3 py-1 rounded-full text-xs font-bold {{ $r->grade == 'F' ? 'bg-red-100 text-red-700' : 'bg-green-100 text-green-700' }}">{{ $r->grade ?? '--' }}</span></td>
                    <td class="py-4 px-6">
                        <form method="POST" action="/admin/school/results/{{ $r->id }}/delete" x-data @submit.prevent="if(confirm('Delete this result?')) $el.submit()">
                            @csrf
                            <button type="submit" class="px-3 py-1.5 text-xs font-semibold bg-red-50 text-red-600 rounded-lg hover:bg-red-600 hover:text-white transition-all">Delete</button>
                        </form>
                    </td>
                </tr>
                @empty <tr><td colspan="7" class="py-8 text-center text-heading/50">No results found.</td></tr>
                @endforelse
            </tbody>
        </table>
        @if($results->hasPages())<div class="p-4">{{ $results->links() }}</div>@endif
    </div>
</div>
@endsection
