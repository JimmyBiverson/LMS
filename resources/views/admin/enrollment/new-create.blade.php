@extends('layouts.dashboard')
@section('title', 'New Enrollment')
@section('page-title', 'New Enrollment')
@section('user-name', auth()->user()->name ?? 'Admin')
@section('sidebar')@include('components.admin-sidebar')@stop
@section('content')
<div class="max-w-2xl">
    <div class="bg-white rounded-xl shadow-sm p-6">
        @if (session('success'))
            <div class="mb-4 p-4 bg-green-50 border border-green-200 text-green-700 rounded-lg text-sm font-semibold">{{ session('success') }}</div>
        @endif
        @if ($errors->any())
            <div class="mb-4 p-4 bg-red-50 border border-red-200 text-red-700 rounded-lg text-sm">
                <ul class="list-disc pl-4 space-y-1">@foreach ($errors->all() as $error)<li>{{ $error }}</li>@endforeach</ul>
            </div>
        @endif
        <h3 class="font-bold text-heading mb-6">Create New Enrollment</h3>
        <form method="POST" action="/admin/enrollment" class="space-y-4">
            @csrf
            <div><label class="block text-sm font-semibold text-heading mb-1">Student *</label>
                <select name="user_id" class="w-full px-4 py-3 rounded-lg border border-heading/10 text-sm focus:outline-none focus:border-primary">
                    <option value="">Select Student</option>
                    @foreach($students as $student)
                        <option value="{{ $student->id }}" @selected(old('user_id') == $student->id)>{{ $student->name }} ({{ $student->email }})</option>
                    @endforeach
                </select>
            </div>
            <div><label class="block text-sm font-semibold text-heading mb-1">Course *</label>
                <select name="course_id" class="w-full px-4 py-3 rounded-lg border border-heading/10 text-sm focus:outline-none focus:border-primary">
                    <option value="">Select Course</option>
                    @foreach($courses as $course)
                        <option value="{{ $course->id }}" @selected(old('course_id') == $course->id)>{{ $course->title }}</option>
                    @endforeach
                </select>
            </div>
            <button type="submit" class="px-8 py-3 bg-primary text-white font-bold rounded-full hover:opacity-90 transition-all">Enroll Student</button>
        </form>
    </div>
</div>
@endsection
