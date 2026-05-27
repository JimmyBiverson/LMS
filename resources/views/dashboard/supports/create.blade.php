@extends('layouts.dashboard')
@section('title', 'Create Support')
@section('page-title', 'New Ticket')
@section('user-name', 'Student')
@section('sidebar')@include('components.student-sidebar')@stop
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
        <h3 class="font-bold text-heading mb-6">Create New Support Ticket</h3>
        <form method="POST" action="/dashboard/supports" class="space-y-4">
            @csrf
            <div>
                <label class="block text-sm font-semibold text-heading mb-1">Subject *</label>
                <input name="subject" type="text" value="{{ old('subject') }}" class="w-full px-4 py-3 rounded-lg border border-heading/10 text-sm text-heading font-semibold focus:outline-none focus:border-primary" placeholder="Enter subject">
            </div>
            <div>
                <label class="block text-sm font-semibold text-heading mb-1">Category *</label>
                <select name="category" class="w-full px-4 py-3 rounded-lg border border-heading/10 text-sm text-heading font-semibold focus:outline-none focus:border-primary">
                    <option value="Technical Issue" @selected(old('category') === 'Technical Issue')>Technical Issue</option>
                    <option value="Billing" @selected(old('category') === 'Billing')>Billing</option>
                    <option value="Course Related" @selected(old('category') === 'Course Related')>Course Related</option>
                    <option value="Other" @selected(old('category') === 'Other')>Other</option>
                </select>
            </div>
            <div>
                <label class="block text-sm font-semibold text-heading mb-1">Priority</label>
                <select name="priority" class="w-full px-4 py-3 rounded-lg border border-heading/10 text-sm text-heading font-semibold focus:outline-none focus:border-primary">
                    <option value="Low" @selected(old('priority') === 'Low')>Low</option>
                    <option value="Medium" @selected(old('priority') === 'Medium') selected>Medium</option>
                    <option value="High" @selected(old('priority') === 'High')>High</option>
                </select>
            </div>
            <div>
                <label class="block text-sm font-semibold text-heading mb-1">Message *</label>
                <textarea name="message" rows="6" class="w-full px-4 py-3 rounded-lg border border-heading/10 text-sm text-heading font-semibold focus:outline-none focus:border-primary" placeholder="Describe your issue">{{ old('message') }}</textarea>
            </div>
            <button type="submit" class="px-8 py-3 bg-primary text-white font-bold rounded-full hover:opacity-90 transition-all duration-300">Submit Ticket</button>
        </form>
    </div>
</div>
@endsection