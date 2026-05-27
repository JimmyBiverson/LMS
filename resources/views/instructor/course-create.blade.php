@extends('layouts.dashboard')
@section('title', 'Create Course')
@section('page-title', 'Add Course')
@section('user-name', 'Instructor')
@section('sidebar')@include('components.instructor-sidebar')@stop
@section('content')
<div class="max-w-4xl">
    <div class="bg-white rounded-xl shadow-sm p-6">
        <h3 class="font-bold text-heading mb-6">Course Information</h3>
        @if (session('success'))
            <div class="mb-4 p-4 bg-green-50 border border-green-200 text-green-700 rounded-lg text-sm font-semibold">{{ session('success') }}</div>
        @endif
        @if ($errors->any())
            <div class="mb-4 p-4 bg-red-50 border border-red-200 text-red-700 rounded-lg text-sm">
                <ul class="list-disc pl-4 space-y-1">@foreach ($errors->all() as $error)<li>{{ $error }}</li>@endforeach</ul>
            </div>
        @endif
        <!-- FIXED: now POSTs to /instructor/courses -->
        <form method="POST" action="/instructor/courses" enctype="multipart/form-data" class="space-y-5">
            @csrf
            <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
                <div><label class="block text-sm font-semibold text-heading mb-1">Course Title *</label><input name="title" type="text" value="{{ old('title') }}" class="w-full px-4 py-3 rounded-lg border border-heading/10 text-sm focus:outline-none focus:border-primary" placeholder="Enter course title"></div>
                <div><label class="block text-sm font-semibold text-heading mb-1">Category *</label><select name="category" class="w-full px-4 py-3 rounded-lg border border-heading/10 text-sm focus:outline-none focus:border-primary"><option value="">Select category</option><option value="Development" @selected(old('category') === 'Development')>Development</option><option value="Design" @selected(old('category') === 'Design')>Design</option><option value="Business" @selected(old('category') === 'Business')>Business</option></select></div>
            </div>
            <div><label class="block text-sm font-semibold text-heading mb-1">Description *</label><textarea name="description" rows="5" class="w-full px-4 py-3 rounded-lg border border-heading/10 text-sm focus:outline-none focus:border-primary" placeholder="Describe your course">{{ old('description') }}</textarea></div>
            <div class="grid grid-cols-1 md:grid-cols-3 gap-5">
                <div><label class="block text-sm font-semibold text-heading mb-1">Price *</label><input name="price" type="number" step="0.01" value="{{ old('price') }}" class="w-full px-4 py-3 rounded-lg border border-heading/10 text-sm focus:outline-none focus:border-primary" placeholder="0.00"></div>
                <div><label class="block text-sm font-semibold text-heading mb-1">Duration</label><input name="duration" type="text" value="{{ old('duration') }}" class="w-full px-4 py-3 rounded-lg border border-heading/10 text-sm focus:outline-none focus:border-primary" placeholder="e.g. 10 hours"></div>
                <div><label class="block text-sm font-semibold text-heading mb-1">Status</label><select name="status" class="w-full px-4 py-3 rounded-lg border border-heading/10 text-sm focus:outline-none focus:border-primary"><option value="Draft" @selected(old('status', 'Draft') === 'Draft')>Draft</option><option value="Active" @selected(old('status') === 'Active')>Active</option><option value="Pending" @selected(old('status') === 'Pending')>Pending</option></select></div>
            </div>
            <div><label class="block text-sm font-semibold text-heading mb-1">Thumbnail</label><input name="thumbnail" type="file" accept="image/jpg,image/jpeg,image/png,image/webp" class="w-full px-4 py-3 rounded-lg border border-heading/10 text-sm focus:outline-none focus:border-primary"></div>
            <button type="submit" class="px-8 py-3 bg-primary text-white font-bold rounded-full hover:opacity-90 transition-all duration-300">Create Course</button>
        </form>
    </div>
</div>
@endsection