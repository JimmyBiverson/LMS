@extends('layouts.dashboard')
@section('title', 'Edit Course')
@section('page-title', 'Edit Course')
@section('user-name', 'Instructor')
@section('sidebar')@include('components.instructor-sidebar')@stop
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
        <form method="POST" action="{{ route('instructor.dashboard.courses.edit', $course->id) }}" enctype="multipart/form-data" class="space-y-5">
            @csrf
            <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
                <div><label class="block text-sm font-semibold text-heading mb-1">Course Title *</label><input name="title" type="text" value="{{ old('title', $course->title) }}" class="w-full px-4 py-3 rounded-lg border border-heading/10 text-sm focus:outline-none focus:border-primary"></div>
                <div><label class="block text-sm font-semibold text-heading mb-1">Category *</label>
                    <select name="category_id" class="w-full px-4 py-3 rounded-lg border border-heading/10 text-sm focus:outline-none focus:border-primary">
                        <option value="">Select category</option>
                        @foreach($categories ?? [] as $cat)
                            <option value="{{ $cat->id }}" @selected(old('category_id', $course->category_id) == $cat->id)>{{ $cat->name }}</option>
                        @endforeach
                    </select>
                </div>
                <div><label class="block text-sm font-semibold text-heading mb-1">Level</label>
                    <select name="level_id" class="w-full px-4 py-3 rounded-lg border border-heading/10 text-sm focus:outline-none focus:border-primary">
                        <option value="">Select level</option>
                        @foreach($levels ?? [] as $level)
                            <option value="{{ $level->id }}" @selected(old('level_id', $course->level_id) == $level->id)>{{ $level->name }}</option>
                        @endforeach
                    </select>
                </div>
            </div>
            <div><label class="block text-sm font-semibold text-heading mb-1">Tags</label>
                <div class="flex flex-wrap gap-3 mt-1">@php $courseTagIds = $course->tags->pluck('id')->toArray(); @endphp@foreach($tags ?? [] as $tag)<label class="flex items-center gap-2 cursor-pointer select-none"><input type="checkbox" name="tags[]" value="{{ $tag->id }}" {{ in_array($tag->id, old('tags', $courseTagIds)) ? 'checked' : '' }} class="w-4 h-4 text-primary focus:ring-primary rounded"><span class="text-sm text-heading/80">{{ $tag->name }}</span></label>@endforeach</div>
            </div>
            <div><label class="block text-sm font-semibold text-heading mb-1">Description *</label><textarea name="description" rows="5" class="w-full px-4 py-3 rounded-lg border border-heading/10 text-sm focus:outline-none focus:border-primary">{{ old('description', $course->description) }}</textarea></div>
            <div><label class="block text-sm font-semibold text-heading mb-1">Learning Outcomes <span class="text-heading/40 font-normal">(one per line)</span></label><textarea name="outcomes" rows="4" class="w-full px-4 py-3 rounded-lg border border-heading/10 text-sm focus:outline-none focus:border-primary" placeholder="What students will learn...">{{ old('outcomes', $course->outcomes) }}</textarea></div>
            <div><label class="block text-sm font-semibold text-heading mb-1">Requirements <span class="text-heading/40 font-normal">(one per line)</span></label><textarea name="requirements" rows="3" class="w-full px-4 py-3 rounded-lg border border-heading/10 text-sm focus:outline-none focus:border-primary" placeholder="Prerequisites or required skills...">{{ old('requirements', $course->requirements) }}</textarea></div>
            <div>
                <label class="block text-sm font-semibold text-heading mb-2">Payment Type</label>
                <div class="flex gap-6 mb-4">
                    <label class="flex items-center gap-2 cursor-pointer select-none">
                        <input type="radio" name="payment_type" value="free" onclick="togglePaymentTypeEdit('free')" {{ old('payment_type', $course->payment_type ?? 'free') === 'free' ? 'checked' : '' }} class="w-4 h-4 text-primary focus:ring-primary">
                        <span class="text-sm font-semibold text-heading">Free</span>
                    </label>
                    <label class="flex items-center gap-2 cursor-pointer select-none">
                        <input type="radio" name="payment_type" value="paid" onclick="togglePaymentTypeEdit('paid')" {{ old('payment_type', $course->payment_type ?? 'free') === 'paid' ? 'checked' : '' }} class="w-4 h-4 text-primary focus:ring-primary">
                        <span class="text-sm font-semibold text-heading">Paid</span>
                    </label>
                </div>
                <div id="priceFieldsEdit" class="grid grid-cols-1 md:grid-cols-2 gap-5 mb-4" style="{{ old('payment_type', $course->payment_type ?? 'free') === 'paid' ? '' : 'display:none' }}">
                    <div>
                        <label class="block text-sm font-semibold text-heading mb-1">Price *</label>
                        <input name="price" type="number" step="0.01" value="{{ old('price', $course->price) }}"
                               id="priceInputEdit"
                               class="w-full px-4 py-3 rounded-lg border border-heading/10 text-sm focus:outline-none focus:border-primary" placeholder="0.00">
                    </div>
                    <div>
                        <label class="block text-sm font-semibold text-heading mb-1">Sale Price <span class="text-heading/40 font-normal">(optional)</span></label>
                        <input name="sale_price" type="number" step="0.01" value="{{ old('sale_price', $course->sale_price) }}"
                               class="w-full px-4 py-3 rounded-lg border border-heading/10 text-sm focus:outline-none focus:border-primary" placeholder="Discounted price">
                    </div>
                </div>
            </div>
            <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
                <div><label class="block text-sm font-semibold text-heading mb-1">Duration</label><input name="duration" type="text" value="{{ old('duration', $course->duration) }}" class="w-full px-4 py-3 rounded-lg border border-heading/10 text-sm focus:outline-none focus:border-primary" placeholder="e.g. 12 hours"></div>
                <div><label class="block text-sm font-semibold text-heading mb-1">Status</label>
                    <select name="status" class="w-full px-4 py-3 rounded-lg border border-heading/10 text-sm focus:outline-none focus:border-primary">
                        @foreach(['Active','Draft','Pending'] as $st)
                            <option value="{{ $st }}" @selected(old('status', $course->status) === $st)>{{ $st }}</option>
                        @endforeach
                    </select>
                </div>
            </div>
            <div><label class="block text-sm font-semibold text-heading mb-1">Thumbnail</label>
            <div class="relative">
                <div id="thumbnailDropZone" class="border-2 border-dashed border-heading/20 rounded-lg p-6 text-center cursor-pointer hover:border-primary hover:bg-primary/5 transition-all" style="min-height: 150px; display: flex; align-items: center; justify-content: center;">
                    <div class="text-center">
                        <i class="ri-image-add-line text-3xl text-primary mb-2 block"></i>
                        <p class="text-sm font-semibold text-heading mb-1">Drag image here or click to upload</p>
                        <p class="text-xs text-heading/50">JPG, PNG or WebP • Max 5MB</p>
                        <p class="text-xs text-heading/50 mt-2">Recommended: 300x200 pixels or larger</p>
                    </div>
                    <input id="thumbnailInput" name="thumbnail" type="file" accept="image/jpeg,image/png,image/webp" class="absolute inset-0 opacity-0 cursor-pointer" />
                </div>
                <div id="thumbnailPreview" class="mt-3 {{ $course->thumbnail && $course->thumbnail !== 'N/A' ? '' : 'hidden' }}">
                    @if($course->thumbnail && $course->thumbnail !== 'N/A')
                        <img id="previewImage" src="{{ asset('storage/' . $course->thumbnail) }}" alt="Current Thumbnail" class="max-w-xs max-h-40 rounded-lg mx-auto" />
                    @else
                        <img id="previewImage" src="" alt="Preview" class="max-w-xs max-h-40 rounded-lg mx-auto" />
                    @endif
                    <button type="button" id="removeThumbnail" class="mt-2 px-3 py-1 bg-red-100 text-red-700 text-xs font-semibold rounded hover:bg-red-200 transition-all">Remove</button>
                </div>
                @error('thumbnail')
                    <div class="mt-2 p-3 bg-red-50 border border-red-200 text-red-700 text-sm rounded">{{ $message }}</div>
                @enderror
            </div>
            <button type="submit" class="px-8 py-3 bg-primary text-white font-bold rounded-full hover:opacity-90 transition-all duration-300">Update Course</button>
        </form>
    </div>
</div>
@push('scripts')
<script>
function togglePaymentTypeEdit(type) {
    var fields = document.getElementById('priceFieldsEdit');
    var priceInput = document.getElementById('priceInputEdit');
    if (type === 'paid') {
        fields.style.display = '';
        priceInput.required = true;
    } else {
        fields.style.display = 'none';
        priceInput.required = false;
    }
}
document.addEventListener('DOMContentLoaded', function () {
    var checked = document.querySelector('input[name="payment_type"]:checked');
    if (checked) togglePaymentTypeEdit(checked.value);

    // Thumbnail upload handling
    const dropZone = document.getElementById('thumbnailDropZone');
    const fileInput = document.getElementById('thumbnailInput');
    const preview = document.getElementById('thumbnailPreview');
    const previewImage = document.getElementById('previewImage');
    const removeBtn = document.getElementById('removeThumbnail');

    // Prevent default drag behaviors
    ['dragenter', 'dragover', 'dragleave', 'drop'].forEach(eventName => {
        dropZone.addEventListener(eventName, preventDefaults, false);
    });

    function preventDefaults(e) {
        e.preventDefault();
        e.stopPropagation();
    }

    // Highlight drop zone when item is dragged over it
    ['dragenter', 'dragover'].forEach(eventName => {
        dropZone.addEventListener(eventName, highlight, false);
    });

    ['dragleave', 'drop'].forEach(eventName => {
        dropZone.addEventListener(eventName, unhighlight, false);
    });

    function highlight(e) {
        dropZone.classList.add('border-primary', 'bg-primary/10');
    }

    function unhighlight(e) {
        dropZone.classList.remove('border-primary', 'bg-primary/10');
    }

    // Handle drop
    dropZone.addEventListener('drop', handleDrop, false);

    function handleDrop(e) {
        var dt = e.dataTransfer;
        var files = dt.files;
        fileInput.files = files;
        handleFileSelect({ target: { files: files } });
    }

    // Handle click to select file
    dropZone.addEventListener('click', () => fileInput.click());

    fileInput.addEventListener('change', handleFileSelect);

    function handleFileSelect(e) {
        const files = e.target.files;
        if (files.length === 0) return;

        const file = files[0];

        // Validate file type
        if (!['image/jpeg', 'image/png', 'image/webp'].includes(file.type)) {
            alert('Please upload a valid image file (JPG, PNG, or WebP)');
            fileInput.value = '';
            return;
        }

        // Validate file size (5MB)
        if (file.size > 5242880) {
            alert('File size must be less than 5MB');
            fileInput.value = '';
            return;
        }

        // Show preview
        const reader = new FileReader();
        reader.onload = function(e) {
            previewImage.src = e.target.result;
            preview.classList.remove('hidden');
            dropZone.style.display = 'none';
        };
        reader.readAsDataURL(file);
    }

    removeBtn.addEventListener('click', function() {
        fileInput.value = '';
        preview.classList.add('hidden');
        dropZone.style.display = 'flex';
    });
});
</script>
@endpush
@endsection
