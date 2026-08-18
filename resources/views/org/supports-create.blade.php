@extends('layouts.dashboard')
@section('title', 'New Ticket')
@section('page-title', 'New Ticket')
@section('user-name', 'Organization')
@section('sidebar')@include('components.org-sidebar')@stop
@section('content')
<div class="max-w-2xl mx-auto">
    <div class="bg-white rounded-xl shadow-sm p-6">
        <h3 class="font-bold text-heading mb-6">Create Support Ticket</h3>
        <form method="POST" action="/org/supports" class="space-y-5">
            @csrf
            <div>
                <label class="block text-sm font-semibold text-heading mb-2">Subject</label>
                <input type="text" name="subject" class="form-input rounded-full w-full peer" placeholder="Enter subject" required>
            </div>
            <div>
                <label class="block text-sm font-semibold text-heading mb-2">Course</label>
                <select name="course_id" class="form-input rounded-full w-full peer">
                    <option value="">General Inquiry</option>
                    @foreach(\App\Models\Course::where('user_id', auth()->id())->get() as $course)
                        <option value="{{ $course->id }}">{{ $course->title }}</option>
                    @endforeach
                </select>
            </div>
            <div>
                <label class="block text-sm font-semibold text-heading mb-2">Message</label>
                <textarea name="message" rows="6" class="form-input rounded-2xl w-full peer" placeholder="Describe your issue..." required></textarea>
            </div>
            <div>
                <button type="submit" class="px-8 py-3 bg-primary text-white font-bold rounded-full hover:opacity-90 transition-all duration-300">Submit Ticket</button>
            </div>
        </form>
    </div>
</div>
@endsection
