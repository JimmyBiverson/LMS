@extends('layouts.dashboard')
@section('title', 'Edit Course')
@section('page-title', 'Edit Course')
@section('user-name', 'Organization')
@section('sidebar')@include('components.org-sidebar')@stop
@section('content')
<div class="max-w-4xl">
    <div class="bg-white rounded-xl shadow-sm p-6">
        <h3 class="font-bold text-heading mb-6">Edit Course</h3>
        @if (session('success'))
            <div class="mb-4 p-4 bg-green-50 border border-green-200 text-green-700 rounded-lg text-sm font-semibold">{{ session('success') }}</div>
        @endif
        @if ($errors->any())
            <div class="mb-4 p-4 bg-red-50 border border-red-200 text-red-700 rounded-lg text-sm">
                <ul class="list-disc pl-4 space-y-1">@foreach ($errors->all() as $error)<li>{{ $error }}</li>@endforeach</ul>
            </div>
        @endif
        <form method="POST" action="{{ route('org.dashboard.courses.edit', $course->id) }}" class="space-y-5">
            @csrf
            <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
                <div><label class="block text-sm font-semibold text-heading mb-1">Course Title *</label><input name="title" type="text" value="{{ old('title', $course->title) }}" class="w-full px-4 py-3 rounded-lg border border-heading/10 text-sm focus:outline-none focus:border-primary"></div>
                <div><label class="block text-sm font-semibold text-heading mb-1">Category *</label>
                    <select name="category" class="w-full px-4 py-3 rounded-lg border border-heading/10 text-sm focus:outline-none focus:border-primary">
                        @foreach(['Development','Design','Business','Marketing','Photography','Music','Health & Fitness'] as $cat)
                            <option value="{{ $cat }}" @selected(old('category', $course->category) === $cat)>{{ $cat }}</option>
                        @endforeach
                    </select>
                </div>
            </div>
            <div><label class="block text-sm font-semibold text-heading mb-1">Description</label><textarea name="description" rows="5" class="w-full px-4 py-3 rounded-lg border border-heading/10 text-sm focus:outline-none focus:border-primary">{{ old('description', $course->description) }}</textarea></div>
            <div x-data="{ type: '{{ old('payment_type', $course->payment_type ?? 'free') }}' }">
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
                <div x-show="type === 'paid'" x-transition.duration.200ms class="mb-4">
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
                        <div>
                            <label class="block text-sm font-semibold text-heading mb-1">Price *</label>
                            <input name="price" type="number" step="0.01" value="{{ old('price', $course->price) }}"
                                   :required="type === 'paid'"
                                   class="w-full px-4 py-3 rounded-lg border border-heading/10 text-sm focus:outline-none focus:border-primary" placeholder="0.00">
                        </div>
                        <div>
                            <label class="block text-sm font-semibold text-heading mb-1">Sale Price <span class="text-heading/40 font-normal">(optional)</span></label>
                            <input name="sale_price" type="number" step="0.01" value="{{ old('sale_price', $course->sale_price) }}"
                                   class="w-full px-4 py-3 rounded-lg border border-heading/10 text-sm focus:outline-none focus:border-primary" placeholder="Discounted price">
                        </div>
                    </div>
                </div>
            </div>
            <div class="grid grid-cols-1 gap-5">
                <div><label class="block text-sm font-semibold text-heading mb-1">Instructor</label>
                    <select name="instructor_id" class="w-full px-4 py-3 rounded-lg border border-heading/10 text-sm focus:outline-none focus:border-primary">
                        <option value="">Select Instructor</option>
                        @foreach($instructors as $instructor)
                            <option value="{{ $instructor->id }}" @selected(old('instructor_id', $course->instructor_id) == $instructor->id)>{{ $instructor->name }}</option>
                        @endforeach
                    </select>
                </div>
            </div>
            <div><label class="block text-sm font-semibold text-heading mb-1">Status</label>
                <select name="status" class="w-full px-4 py-3 rounded-lg border border-heading/10 text-sm focus:outline-none focus:border-primary">
                    @foreach(['Active','Draft'] as $st)
                        <option value="{{ $st }}" @selected(old('status', $course->status) === $st)>{{ $st }}</option>
                    @endforeach
                </select>
            </div>
            <button type="submit" class="px-8 py-3 bg-primary text-white font-bold rounded-full hover:opacity-90 transition-all duration-300">Update Course</button>
        </form>
    </div>
</div>
@endsection
