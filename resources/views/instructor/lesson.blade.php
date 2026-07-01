@extends('layouts.dashboard')
@section('title', 'Lessons')
@section('page-title', 'Lessons for: ' . $course->title)
@section('user-name', 'Instructor')
@section('sidebar')@include('components.instructor-sidebar')@stop
@section('content')
<div class="mb-6">
    <a href="{{ route('instructor.dashboard.courses.edit', $course->id) }}" class="inline-flex items-center gap-2 text-sm text-primary hover:underline font-semibold">
        <i class="ri-arrow-left-s-line"></i> Back to Course
    </a>
</div>

@if (session('success'))
    <div class="mb-4 p-4 bg-green-50 border border-green-200 text-green-700 rounded-lg text-sm font-semibold flex items-center gap-2">
        <i class="ri-checkbox-circle-fill text-green-500 text-lg"></i> {{ session('success') }}
    </div>
@endif
@if ($errors->any())
    <div class="mb-4 p-4 bg-red-50 border border-red-200 text-red-700 rounded-lg text-sm">
        <ul class="list-disc pl-4 space-y-1">@foreach ($errors->all() as $error)<li>{{ $error }}</li>@endforeach</ul>
    </div>
@endif

<div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
    {{-- Lesson List --}}
    <div class="lg:col-span-2">
        <div class="bg-white rounded-xl shadow-sm">
            <div class="p-6 border-b border-gray-100 flex items-center justify-between flex-wrap gap-3">
                <h3 class="font-bold text-heading flex items-center gap-2">
                    <i class="ri-play-list-fill text-primary"></i>
                    Lessons <span class="bg-primary-50 text-primary text-xs font-bold px-2.5 py-1 rounded-full">{{ $course->lessons->count() }}</span>
                </h3>
            </div>
            <div class="overflow-x-auto">
                <table class="w-full text-sm">
                    <thead><tr class="bg-gray-50 text-heading/60 text-xs uppercase tracking-wider">
                        <th class="text-left py-4 px-6 font-semibold">#</th>
                        <th class="text-left py-4 px-6 font-semibold">Lesson</th>
                        <th class="text-left py-4 px-6 font-semibold">Duration</th>
                        <th class="text-left py-4 px-6 font-semibold">Media</th>
                        <th class="text-left py-4 px-6 font-semibold">Preview</th>
                        <th class="text-right py-4 px-6 font-semibold">Actions</th>
                    </tr></thead>
                    <tbody class="divide-y divide-gray-100">
                        @forelse($course->lessons->sortBy('order') as $lesson)
                        <tr class="hover:bg-gray-50 transition-colors">
                            <td class="py-4 px-6 text-heading/70">{{ $lesson->order }}</td>
                            <td class="py-4 px-6">
                                <div class="font-semibold text-heading">{{ $lesson->title }}</div>
                            </td>
                            <td class="py-4 px-6 text-heading/70">{{ $lesson->duration ?? '--' }}</td>
                            <td class="py-4 px-6">
                                <div class="flex items-center gap-1.5">
                                    @if($lesson->video_url || $lesson->video_file)
                                        <span class="inline-flex items-center gap-1 text-xs bg-blue-100 text-blue-700 px-2 py-0.5 rounded-full font-semibold">
                                            <i class="ri-video-line"></i> Video
                                        </span>
                                    @endif
                                    @if($lesson->document_file)
                                        <span class="inline-flex items-center gap-1 text-xs bg-amber-100 text-amber-700 px-2 py-0.5 rounded-full font-semibold">
                                            <i class="ri-file-pdf-line"></i> Doc
                                        </span>
                                    @endif
                                </div>
                            </td>
                            <td class="py-4 px-6">
                                @if($lesson->is_free_preview)
                                    <span class="text-xs bg-green-100 text-green-700 px-2 py-0.5 rounded-full font-semibold">Free</span>
                                @else
                                    <span class="text-xs text-heading/40">Locked</span>
                                @endif
                            </td>
                            <td class="py-4 px-6 text-right">
                                <form method="POST" action="{{ route('instructor.dashboard.courses.lessons.delete', [$course->id, $lesson->id]) }}" class="inline" onsubmit="return confirm('Delete this lesson?')">
                                    @csrf
                                    <button class="px-3 py-1 text-xs font-semibold rounded-full border border-red-200 text-red-500 hover:bg-red-50 transition-all">Delete</button>
                                </form>
                            </td>
                        </tr>
                        @empty
                        <tr><td colspan="6" class="py-12 text-center text-heading/40 text-sm">
                            <i class="ri-inbox-2-line text-3xl block mb-2"></i>
                            No lessons yet. Add your first lesson using the form!
                        </td></tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    {{-- Add Lesson Form --}}
    <div>
        <div class="bg-white rounded-xl shadow-sm p-6">
            <h3 class="font-bold text-heading mb-1 flex items-center gap-2">
                <i class="ri-add-circle-line text-primary text-xl"></i> Add Lesson
            </h3>
            <p class="text-xs text-heading/50 mb-5">Upload at least one media: a video or a document.</p>

            <form method="POST" action="{{ route('instructor.dashboard.courses.lessons.store', $course->id) }}" class="space-y-4" enctype="multipart/form-data">
                @csrf
                <div>
                    <label class="block text-sm font-semibold text-heading mb-1">Lesson Title <span class="text-red-500">*</span></label>
                    <input name="title" type="text" value="{{ old('title') }}"
                        class="w-full px-4 py-2.5 rounded-lg border border-heading/10 text-sm focus:outline-none focus:border-primary focus:ring-2 focus:ring-primary/10"
                        placeholder="e.g. Introduction to the Course">
                </div>

                <div>
                    <label class="block text-sm font-semibold text-heading mb-1">Lesson Description</label>
                    <textarea name="content" rows="3"
                        class="w-full px-4 py-2.5 rounded-lg border border-heading/10 text-sm focus:outline-none focus:border-primary focus:ring-2 focus:ring-primary/10"
                        placeholder="Brief overview of what this lesson covers...">{{ old('content') }}</textarea>
                </div>

                {{-- Media Section --}}
                <div class="border border-dashed border-primary/30 rounded-xl p-4 bg-primary-50/30 space-y-4">
                    <p class="text-xs font-bold text-primary uppercase tracking-wider flex items-center gap-1">
                        <i class="ri-attachment-line"></i> Media (at least one required)
                    </p>

                    {{-- Video URL --}}
                    <div>
                        <label class="block text-sm font-semibold text-heading mb-1 flex items-center gap-1">
                            <i class="ri-links-line text-blue-500"></i> Video URL (YouTube / Vimeo / Direct Link)
                        </label>
                        <input name="video_url" type="url" value="{{ old('video_url') }}"
                            class="w-full px-4 py-2.5 rounded-lg border border-heading/10 text-sm focus:outline-none focus:border-primary focus:ring-2 focus:ring-primary/10"
                            placeholder="https://www.youtube.com/watch?v=...">
                    </div>

                    <div class="flex items-center gap-2 text-xs text-heading/40">
                        <div class="flex-1 h-px bg-heading/10"></div><span>OR</span><div class="flex-1 h-px bg-heading/10"></div>
                    </div>

                    {{-- Video File Upload --}}
                    <div>
                        <label class="block text-sm font-semibold text-heading mb-1 flex items-center gap-1">
                            <i class="ri-video-upload-line text-blue-500"></i> Upload Video File
                            <span class="text-xs text-heading/40 font-normal">(MP4, MOV, AVI, WebM — max 500MB)</span>
                        </label>
                        <div class="relative border-2 border-dashed border-blue-200 rounded-lg bg-blue-50/40 hover:border-blue-400 transition-colors cursor-pointer" id="videoDropZone">
                            <input type="file" name="video_file" accept="video/mp4,video/mov,video/avi,video/webm,video/ogg"
                                class="absolute inset-0 w-full h-full opacity-0 cursor-pointer z-10" id="videoFileInput"
                                onchange="previewFile(this, 'videoPreviewArea', 'video')">
                            <div class="p-4 text-center" id="videoPreviewArea">
                                <i class="ri-video-upload-line text-2xl text-blue-400 block mb-1"></i>
                                <p class="text-xs text-blue-600 font-semibold">Click or drag video here</p>
                                <p class="text-xs text-blue-400 mt-0.5" id="videoFileName">No file chosen</p>
                            </div>
                        </div>
                    </div>

                    <div class="flex items-center gap-2 text-xs text-heading/40">
                        <div class="flex-1 h-px bg-heading/10"></div><span>AND/OR</span><div class="flex-1 h-px bg-heading/10"></div>
                    </div>

                    {{-- Document File Upload --}}
                    <div>
                        <label class="block text-sm font-semibold text-heading mb-1 flex items-center gap-1">
                            <i class="ri-file-upload-line text-amber-500"></i> Upload Document
                            <span class="text-xs text-heading/40 font-normal">(PDF, DOC, DOCX, PPT, XLSX — max 50MB)</span>
                        </label>
                        <div class="relative border-2 border-dashed border-amber-200 rounded-lg bg-amber-50/40 hover:border-amber-400 transition-colors cursor-pointer">
                            <input type="file" name="document_file" accept=".pdf,.doc,.docx,.ppt,.pptx,.xls,.xlsx"
                                class="absolute inset-0 w-full h-full opacity-0 cursor-pointer z-10" id="docFileInput"
                                onchange="previewFile(this, 'docPreviewArea', 'doc')">
                            <div class="p-4 text-center" id="docPreviewArea">
                                <i class="ri-file-pdf-line text-2xl text-amber-400 block mb-1"></i>
                                <p class="text-xs text-amber-600 font-semibold">Click or drag document here</p>
                                <p class="text-xs text-amber-400 mt-0.5" id="docFileName">No file chosen</p>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="grid grid-cols-2 gap-3">
                    <div>
                        <label class="block text-sm font-semibold text-heading mb-1">Duration</label>
                        <input name="duration" type="text" value="{{ old('duration') }}"
                            class="w-full px-4 py-2.5 rounded-lg border border-heading/10 text-sm focus:outline-none focus:border-primary"
                            placeholder="e.g. 15 min">
                    </div>
                    <div>
                        <label class="block text-sm font-semibold text-heading mb-1">Status</label>
                        <select name="status" class="w-full px-4 py-2.5 rounded-lg border border-heading/10 text-sm focus:outline-none focus:border-primary">
                            <option value="published" @selected(old('status', 'published') === 'published')>Published</option>
                            <option value="draft" @selected(old('status') === 'draft')>Draft</option>
                        </select>
                    </div>
                </div>

                <div>
                    <label class="flex items-center gap-2 cursor-pointer select-none">
                        <input name="is_free_preview" type="checkbox" value="1" class="w-4 h-4 rounded border-heading/20 text-primary accent-primary" @checked(old('is_free_preview'))>
                        <span class="text-sm text-heading font-semibold">Allow free preview (visible without enrollment)</span>
                    </label>
                </div>

                <button type="submit" class="w-full px-6 py-3 bg-primary text-white font-bold rounded-full hover:opacity-90 transition-all duration-300 flex items-center justify-center gap-2">
                    <i class="ri-add-circle-line"></i> Add Lesson
                </button>
            </form>
        </div>
    </div>
</div>

{{-- Course Tools --}}
<div class="mt-6 bg-white rounded-xl shadow-sm p-6">
    <h3 class="font-bold text-heading mb-3 flex items-center gap-2"><i class="ri-tools-line text-primary"></i> Course Tools</h3>
    <div class="flex flex-wrap gap-3">
        <a href="/instructor/courses/{{ $course->id }}/quizzes" class="inline-flex items-center gap-2 px-4 py-2 bg-primary-50 text-primary font-semibold rounded-lg hover:bg-primary hover:text-white transition-all text-sm">
            <i class="ri-questionnaire-line"></i> Manage Quizzes
        </a>
        <a href="/instructor/courses/{{ $course->id }}/assignments" class="inline-flex items-center gap-2 px-4 py-2 bg-secondary/20 text-secondary font-semibold rounded-lg hover:bg-secondary hover:text-white transition-all text-sm">
            <i class="ri-task-line"></i> Manage Assignments
        </a>
        <a href="/courses/{{ $course->slug }}" target="_blank" class="inline-flex items-center gap-2 px-4 py-2 bg-green-50 text-green-600 font-semibold rounded-lg hover:bg-green-500 hover:text-white transition-all text-sm">
            <i class="ri-eye-line"></i> Preview Course
        </a>
    </div>
</div>

@push('scripts')
<script>
function previewFile(input, areaId, type) {
    const area = document.getElementById(areaId);
    const file = input.files[0];
    if (!file) return;

    if (type === 'video') {
        const url = URL.createObjectURL(file);
        area.innerHTML = `
            <video src="${url}" class="w-full rounded-lg mt-1 max-h-28 object-cover" muted autoplay loop playsinline></video>
            <p class="text-xs text-blue-600 font-semibold mt-1 truncate px-2">${file.name}</p>
            <p class="text-xs text-blue-400">${(file.size / 1024 / 1024).toFixed(1)} MB</p>`;
    } else {
        const icon = file.name.endsWith('.pdf') ? 'ri-file-pdf-line text-red-500' :
                     file.name.match(/\.(doc|docx)$/) ? 'ri-file-word-line text-blue-500' :
                     file.name.match(/\.(ppt|pptx)$/) ? 'ri-file-ppt-line text-orange-500' :
                     'ri-file-excel-line text-green-500';
        area.innerHTML = `
            <i class="${icon} text-3xl block mb-1"></i>
            <p class="text-xs text-amber-600 font-semibold truncate px-2">${file.name}</p>
            <p class="text-xs text-amber-400">${(file.size / 1024 / 1024).toFixed(2)} MB</p>`;
    }
}
</script>
@endpush
@endsection
