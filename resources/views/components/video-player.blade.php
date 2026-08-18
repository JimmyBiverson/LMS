{{-- Modern Video Player Component with Auto-play Preview --}}
@props(['lesson', 'autoplay' => false, 'previewSeconds' => 8, 'enrolled' => false, 'lastPosition' => 0, 'isCompleted' => false])

@php
    $videoSource = null;
    $hasVideo = false;
    
    if ($lesson->video_file) {
        $videoSource = asset('storage/' . $lesson->video_file);
        $hasVideo = true;
    } elseif ($lesson->video_url) {
        $videoSource = $lesson->video_url;
        $hasVideo = true;
    }
    
    $isYoutube = $videoSource && str_contains($videoSource, 'youtube.com') || str_contains($videoSource ?? '', 'youtu.be');
    $isVimeo = $videoSource && str_contains($videoSource, 'vimeo.com');
@endphp

<div class="relative bg-black rounded-xl overflow-hidden shadow-2xl" style="aspect-ratio: 16 / 9;">
    @if($isYoutube)
        {{-- YouTube Embed --}}
        @php
            $videoId = '';
            if (str_contains($videoSource, 'youtube.com')) {
                preg_match('/v=([^&]+)/', $videoSource, $matches);
                $videoId = $matches[1] ?? '';
            } elseif (str_contains($videoSource, 'youtu.be')) {
                preg_match('/youtu\.be\/([^?]+)/', $videoSource, $matches);
                $videoId = $matches[1] ?? '';
            }
        @endphp
        @if($videoId)
        <iframe 
            class="w-full h-full"
            src="https://www.youtube.com/embed/{{ $videoId }}?autoplay={{ $autoplay ? 1 : 0 }}&modestbranding=1&rel=0"
            title="Lesson Video"
            frameborder="0"
            allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture"
            allowfullscreen>
        </iframe>
        @endif
    @elseif($isVimeo)
        {{-- Vimeo Embed --}}
        @php
            preg_match('/vimeo\.com\/(\d+)/', $videoSource, $matches);
            $videoId = $matches[1] ?? '';
        @endphp
        @if($videoId)
        <iframe 
            class="w-full h-full"
            src="https://player.vimeo.com/video/{{ $videoId }}?autoplay={{ $autoplay ? 1 : 0 }}"
            title="Lesson Video"
            frameborder="0"
            allow="autoplay; fullscreen; picture-in-picture"
            allowfullscreen>
        </iframe>
        @endif
    @elseif($hasVideo)
        {{-- HTML5 Video Player --}}
        <video 
            class="w-full h-full object-cover"
            controls
            @if($autoplay) autoplay muted @endif
            id="lessonVideo"
            data-preview-seconds="{{ $previewSeconds }}">
            <source src="{{ $videoSource }}" type="video/mp4">
            Your browser does not support the video tag.
        </video>
        
        {{-- Progress Tracking & Auto-play Preview Logic --}}
        <script>
            document.addEventListener('DOMContentLoaded', function() {
                const video = document.getElementById('lessonVideo');
                if (!video) return;

                @auth
                    @if($enrolled)
                        const lastPos = {{ $lastPosition }};
                        const isCompleted = {{ $isCompleted ? 'true' : 'false' }};
                        
                        if (lastPos > 0) {
                            video.currentTime = lastPos;
                        }

                        // Save watch progress periodically
                        let lastUpdated = 0;
                        video.addEventListener('timeupdate', function() {
                            const now = Math.floor(video.currentTime);
                            if (now - lastUpdated >= 5) {
                                lastUpdated = now;
                                fetch('/lessons/{{ $lesson->id }}/progress', {
                                    method: 'POST',
                                    headers: {
                                        'Content-Type': 'application/json',
                                        'X-CSRF-TOKEN': '{{ csrf_token() }}'
                                    },
                                    body: JSON.stringify({ position: now })
                                });
                            }
                        });

                        // Automatically mark complete when video ends
                        video.addEventListener('ended', function() {
                            if (!isCompleted) {
                                fetch('/lessons/{{ $lesson->id }}/toggle-completion', {
                                    method: 'POST',
                                    headers: {
                                        'Content-Type': 'application/json',
                                        'X-CSRF-TOKEN': '{{ csrf_token() }}'
                                    }
                                }).then(() => {
                                    window.location.reload();
                                });
                            }
                        });
                    @endif
                @endauth

                {{-- Preview Limit for Guests / Unenrolled --}}
                @if($autoplay && !$lesson->is_free_preview)
                    const previewSeconds = parseInt(video.dataset.previewSeconds || 8);
                    let previewShown = false;
                    
                    video.addEventListener('timeupdate', function() {
                        if (!previewShown && video.currentTime >= previewSeconds) {
                            video.pause();
                            showPreviewLimitModal();
                            previewShown = true;
                        }
                    });
                @endif
            });
            
            function showPreviewLimitModal() {
                const overlay = document.createElement('div');
                overlay.className = 'fixed inset-0 z-50 flex items-center justify-center bg-black/60 p-4';
                overlay.innerHTML = `
                    <div class="bg-white rounded-2xl p-8 max-w-md w-full text-center shadow-2xl">
                        <div class="w-16 h-16 rounded-full bg-amber-100 flex items-center justify-center mx-auto mb-4">
                            <i class="ri-lock-line text-3xl text-amber-600"></i>
                        </div>
                        <h3 class="text-xl font-bold text-heading mb-2">Preview Limit Reached</h3>
                        <p class="text-heading/60 mb-6">You've watched the preview. Enroll in this course to access the full lesson and all course materials.</p>
                        <div class="flex flex-col gap-3">
                            <a href="/courses/${window.courseSlug || ''}/checkout" class="w-full px-6 py-3 bg-primary text-white font-bold rounded-xl hover:opacity-90 text-center">Enroll Now</a>
                            <button onclick="this.closest('.fixed').remove()" class="text-sm text-heading/60 font-semibold hover:text-heading">Continue browsing</button>
                        </div>
                    </div>
                `;
                overlay.addEventListener('click', (e) => { if (e.target === overlay) overlay.remove(); });
                document.body.appendChild(overlay);
            }
        </script>
    @else
        {{-- No Video Placeholder --}}
        <div class="w-full h-full flex flex-col items-center justify-center bg-gradient-to-br from-gray-800 to-gray-900">
            <div class="text-center">
                <i class="ri-video-off-line text-6xl text-gray-600 mb-4 block"></i>
                <p class="text-gray-400 font-semibold">No video available</p>
                <p class="text-gray-500 text-sm mt-1">This lesson may have document resources only</p>
            </div>
        </div>
    @endif
</div>

{{-- Video Info --}}
<div class="mt-4 flex items-center justify-between">
    <div>
        <h2 class="text-2xl font-bold text-heading">{{ $lesson->title }}</h2>
        @if($lesson->duration)
        <p class="text-sm text-heading/60 mt-1">
            <i class="ri-time-line"></i> Duration: {{ $lesson->duration }}
        </p>
        @endif
    </div>
    @if($lesson->is_free_preview)
    <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full bg-green-100 text-green-700 text-sm font-bold">
        <i class="ri-unlock-line"></i> Free Preview
    </span>
    @endif
</div>

{{-- Description --}}
@if($lesson->content)
<div class="mt-6 p-6 bg-gray-50 rounded-xl">
    <h3 class="font-bold text-heading mb-2">About This Lesson</h3>
    <p class="text-heading/70 leading-relaxed">{{ $lesson->content }}</p>
</div>
@endif

{{-- Resources --}}
<div class="mt-6 grid grid-cols-1 md:grid-cols-2 gap-4">
    @if($lesson->document_file)
        @auth
            @if($enrolled)
                <a href="{{ route('lessons.download.document', $lesson) }}" target="_blank" class="flex items-center gap-3 p-4 rounded-xl border border-blue-200 bg-blue-50 hover:bg-blue-100 transition-colors group cursor-pointer">
                    <div class="w-12 h-12 rounded-lg bg-blue-500 flex items-center justify-center text-white group-hover:scale-110 transition-transform">
                        <i class="ri-file-pdf-line text-xl"></i>
                    </div>
                    <div class="flex-1">
                        <p class="font-semibold text-heading text-sm">Course Material</p>
                        <p class="text-xs text-heading/60">PDF Document</p>
                    </div>
                    <i class="ri-download-line text-heading/40 group-hover:text-primary transition-colors"></i>
                </a>
            @else
                <div class="flex items-center gap-3 p-4 rounded-xl border border-gray-200 bg-gray-50 opacity-70">
                    <div class="w-12 h-12 rounded-lg bg-gray-400 flex items-center justify-center text-white">
                        <i class="ri-lock-line text-xl"></i>
                    </div>
                    <div class="flex-1">
                        <p class="font-semibold text-heading text-sm">Course Material</p>
                        <p class="text-xs text-amber-600 font-medium">Enroll to Download</p>
                    </div>
                </div>
            @endif
        @else
            <a href="{{ route('login') }}" class="flex items-center gap-3 p-4 rounded-xl border border-gray-200 bg-gray-50 transition-colors group cursor-pointer opacity-70">
                <div class="w-12 h-12 rounded-lg bg-gray-400 flex items-center justify-center text-white">
                    <i class="ri-lock-line text-xl"></i>
                </div>
                <div class="flex-1">
                    <p class="font-semibold text-heading text-sm">Course Material</p>
                    <p class="text-xs text-heading/60">Login to Access</p>
                </div>
                <i class="ri-arrow-right-s-line text-heading/40"></i>
            </a>
        @endauth
    @endif
    
    @if($lesson->video_file)
        @php $fileSize = $lesson->video_file && Storage::disk('public')->exists($lesson->video_file) ? Storage::disk('public')->size($lesson->video_file) : 0; @endphp
        @auth
            @if($enrolled)
                <a href="{{ route('lessons.download.video', $lesson) }}" class="flex items-center gap-3 p-4 rounded-xl border border-purple-200 bg-purple-50 hover:bg-purple-100 transition-colors group cursor-pointer">
                    <div class="w-12 h-12 rounded-lg bg-purple-500 flex items-center justify-center text-white group-hover:scale-110 transition-transform">
                        <i class="ri-video-download-line text-xl"></i>
                    </div>
                    <div class="flex-1">
                        <p class="font-semibold text-heading text-sm">Download Video</p>
                        <p class="text-xs text-heading/60">{{ $fileSize > 0 ? round($fileSize / 1048576, 1) . ' MB' : 'Available for download' }}</p>
                    </div>
                    <i class="ri-download-line text-heading/40 group-hover:text-primary transition-colors"></i>
                </a>
            @else
                <div class="flex items-center gap-3 p-4 rounded-xl border border-gray-200 bg-gray-50 opacity-70">
                    <div class="w-12 h-12 rounded-lg bg-gray-400 flex items-center justify-center text-white">
                        <i class="ri-lock-line text-xl"></i>
                    </div>
                    <div class="flex-1">
                        <p class="font-semibold text-heading text-sm">Download Video</p>
                        <p class="text-xs text-amber-600 font-medium">Enroll to Download</p>
                    </div>
                </div>
            @endif
        @else
            <a href="{{ route('login') }}" class="flex items-center gap-3 p-4 rounded-xl border border-gray-200 bg-gray-50 transition-colors group cursor-pointer opacity-70">
                <div class="w-12 h-12 rounded-lg bg-gray-400 flex items-center justify-center text-white">
                    <i class="ri-lock-line text-xl"></i>
                </div>
                <div class="flex-1">
                    <p class="font-semibold text-heading text-sm">Download Video</p>
                    <p class="text-xs text-heading/60">Login to Access</p>
                </div>
                <i class="ri-arrow-right-s-line text-heading/40"></i>
            </a>
        @endauth
    @endif
</div>
