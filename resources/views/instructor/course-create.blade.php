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
            <div><label class="block text-sm font-semibold text-heading mb-1">Learning Outcomes <span class="text-heading/40 font-normal">(one per line)</span></label><textarea name="outcomes" rows="4" class="w-full px-4 py-3 rounded-lg border border-heading/10 text-sm focus:outline-none focus:border-primary" placeholder="What students will learn...">{{ old('outcomes') }}</textarea></div>
            <div><label class="block text-sm font-semibold text-heading mb-1">Requirements <span class="text-heading/40 font-normal">(one per line)</span></label><textarea name="requirements" rows="3" class="w-full px-4 py-3 rounded-lg border border-heading/10 text-sm focus:outline-none focus:border-primary" placeholder="Prerequisites or required skills...">{{ old('requirements') }}</textarea></div>
            <div x-data="{ type: '{{ old('payment_type', 'free') }}' }">
                <label class="block text-sm font-semibold text-heading mb-2">Payment Type</label>
                <div class="flex gap-6 mb-4">
                    <label class="flex items-center gap-2 cursor-pointer select-none">
                        <input type="radio" name="payment_type" value="free" x-model="type" class="w-4 h-4 text-primary focus:ring-primary">
                        <span class="text-sm font-semibold text-heading">Free</span>
                    </label>
                    <label class="flex items-center gap-2 cursor-pointer select-none">
                        <input type="radio" name="payment_type" value="paid" x-model="type" class="w-4 h-4 text-primary focus:ring-primary">
                        <span class="text-sm font-semibold text-heading">Paid</span>
                    </label>
                </div>
                <div x-show="type === 'paid'" x-transition.duration.200ms class="grid grid-cols-1 md:grid-cols-2 gap-5 mb-4">
                    <div>
                        <label class="block text-sm font-semibold text-heading mb-1">Price *</label>
                        <input name="price" type="number" step="0.01" value="{{ old('price') }}"
                               :required="type === 'paid'"
                               class="w-full px-4 py-3 rounded-lg border border-heading/10 text-sm focus:outline-none focus:border-primary" placeholder="0.00">
                    </div>
                    <div>
                        <label class="block text-sm font-semibold text-heading mb-1">Sale Price <span class="text-heading/40 font-normal">(optional)</span></label>
                        <input name="sale_price" type="number" step="0.01" value="{{ old('sale_price') }}"
                               class="w-full px-4 py-3 rounded-lg border border-heading/10 text-sm focus:outline-none focus:border-primary" placeholder="Discounted price">
                    </div>
                </div>
            </div>
            <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
                <div><label class="block text-sm font-semibold text-heading mb-1">Duration</label><input name="duration" type="text" value="{{ old('duration') }}" class="w-full px-4 py-3 rounded-lg border border-heading/10 text-sm focus:outline-none focus:border-primary" placeholder="e.g. 10 hours"></div>
                <div><label class="block text-sm font-semibold text-heading mb-1">Status</label><select name="status" class="w-full px-4 py-3 rounded-lg border border-heading/10 text-sm focus:outline-none focus:border-primary"><option value="Draft" @selected(old('status', 'Draft') === 'Draft')>Draft</option><option value="Active" @selected(old('status') === 'Active')>Active</option><option value="Pending" @selected(old('status') === 'Pending')>Pending</option></select></div>
            </div>
            <div><label class="block text-sm font-semibold text-heading mb-1">Thumbnail</label><input name="thumbnail" type="file" accept="image/jpg,image/jpeg,image/png,image/webp" class="w-full px-4 py-3 rounded-lg border border-heading/10 text-sm focus:outline-none focus:border-primary"></div>
            <button type="submit" class="px-8 py-3 bg-primary text-white font-bold rounded-full hover:opacity-90 transition-all duration-300">Create Course</button>
        </form>
    </div>
</div>
@endsection