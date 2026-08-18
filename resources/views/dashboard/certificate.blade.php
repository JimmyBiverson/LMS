@extends('layouts.dashboard')
@section('title', 'My Certificates')
@section('page-title', 'Certificates')
@section('user-name', 'Student')
@section('sidebar')@include('components.student-sidebar')@stop
@section('content')
<div class="space-y-6">
    <div class="bg-gradient-to-r from-emerald-500 to-cyan-500 rounded-2xl p-6 text-white shadow-sm">
        <div class="flex items-center justify-between gap-4 flex-wrap">
            <div>
                <p class="text-xs uppercase tracking-[0.2em] text-emerald-100">Achievements</p>
                <h3 class="mt-2 text-2xl font-extrabold">Your course certificates</h3>
            </div>
            <span class="px-3 py-1.5 rounded-full bg-white/15 text-sm font-semibold">{{ $certificates->count() }} earned</span>
        </div>
    </div>

    @forelse($certificates as $certificate)
        <div class="bg-white rounded-2xl shadow-sm overflow-hidden border border-gray-100">
            <div class="grid lg:grid-cols-[1.4fr_0.6fr]">
                <div class="p-6 border-b lg:border-b-0 lg:border-r border-gray-100">
                    <div class="flex items-start gap-4">
                        <div class="w-16 h-16 rounded-2xl bg-emerald-100 flex items-center justify-center shrink-0">
                            <i class="ri-award-fill text-3xl text-emerald-600"></i>
                        </div>
                        <div class="min-w-0">
                            <p class="text-xs uppercase tracking-[0.25em] text-emerald-600 font-bold">Certificate</p>
                            <h4 class="mt-2 text-xl font-extrabold text-heading">{{ $certificate->title ?? 'Certificate of Completion' }}</h4>
                            <p class="mt-2 text-sm text-heading/70">Awarded to {{ auth()->user()->full_name }} for successfully completing {{ $certificate->course?->title ?? 'this course' }}.</p>
                            <div class="mt-4 flex flex-wrap gap-3 text-xs text-heading/60">
                                <span class="inline-flex items-center gap-1 bg-emerald-50 text-emerald-700 px-2 py-1 rounded-full"><i class="ri-calendar-line"></i> {{ $certificate->created_at->format('M d, Y') }}</span>
                                <span class="inline-flex items-center gap-1 bg-sky-50 text-sky-700 px-2 py-1 rounded-full"><i class="ri-book-open-line"></i> {{ $certificate->course?->title ?? 'Course Completed' }}</span>
                            </div>
                        </div>
                    </div>
                    <!-- Certificate Mini Preview -->
                    <div class="mt-4 pt-4 border-t border-gray-100">
                        <p class="text-xs uppercase tracking-[0.25em] text-heading/60 font-bold mb-2">Preview</p>
                        <div class="relative bg-gradient-to-br from-purple-50 to-blue-50 rounded-lg p-3 border border-gray-200 h-32 flex items-center justify-center overflow-hidden">
                            <div class="text-center text-xs">
                                <div class="font-bold text-heading text-sm">{{ auth()->user()->full_name }}</div>
                                <div class="text-heading/60 mt-1">{{ $certificate->course?->title ?? 'Course' }}</div>
                                <div class="text-heading/40 mt-1">{{ $certificate->created_at->format('M d, Y') }}</div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="p-6 flex flex-col justify-center gap-3">
                    <button onclick="openCertificateModal({{ $certificate->id }})" class="inline-flex items-center justify-center gap-2 px-5 py-3 bg-emerald-600 text-white font-semibold rounded-xl hover:bg-emerald-700 transition-colors">
                        <i class="ri-eye-line"></i> View Full
                    </button>
                    <a href="/dashboard/certificate/{{ $certificate->id }}/download" class="inline-flex items-center justify-center gap-2 px-5 py-3 border border-emerald-200 text-emerald-700 bg-emerald-50 font-semibold rounded-xl hover:bg-emerald-100 transition-colors">
                        <i class="ri-download-2-line"></i> Download PDF
                    </a>
                </div>
            </div>
        </div>
    @empty
        <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-12 text-center">
            <div class="w-20 h-20 mx-auto rounded-full bg-gray-100 flex items-center justify-center mb-4">
                <i class="ri-award-line text-4xl text-gray-400"></i>
            </div>
            <h4 class="text-xl font-bold text-heading">No certificates yet</h4>
            <p class="mt-2 text-sm text-heading/60">Complete a course and pass the required assessments to unlock your certificate.</p>
        </div>
    @endforelse
</div>

<!-- Certificate Modal -->
<div id="certificateModal" class="fixed inset-0 bg-black/50 hidden items-center justify-center z-50 p-4" onclick="closeCertificateModal(event)">
    <div class="bg-white rounded-2xl shadow-2xl w-full max-w-6xl max-h-[90vh] overflow-hidden flex flex-col" onclick="event.stopPropagation()">
        <div class="flex items-center justify-between p-6 border-b border-gray-200">
            <h3 class="text-xl font-bold text-heading">Certificate Preview</h3>
            <button onclick="closeCertificateModal()" class="text-heading/60 hover:text-heading transition-colors">
                <i class="ri-close-line text-2xl"></i>
            </button>
        </div>
        <div class="flex-1 overflow-auto p-6 bg-gray-50 flex items-center justify-center">
            <iframe id="certificateFrame" src="" class="w-full h-full border-0" style="min-height: 600px;"></iframe>
        </div>
        <div class="flex items-center justify-between p-6 border-t border-gray-200 bg-gray-50">
            <p class="text-sm text-heading/60">Landscape orientation certificate preview</p>
            <a id="downloadBtn" href="#" class="inline-flex items-center gap-2 px-5 py-2.5 bg-emerald-600 text-white font-semibold rounded-lg hover:bg-emerald-700 transition-colors">
                <i class="ri-download-2-line"></i> Download PDF
            </a>
        </div>
    </div>
</div>

<script>
function openCertificateModal(certificateId) {
    const modal = document.getElementById('certificateModal');
    const frame = document.getElementById('certificateFrame');
    const downloadBtn = document.getElementById('downloadBtn');
    
    frame.src = `/dashboard/certificate/${certificateId}/download`;
    downloadBtn.href = `/dashboard/certificate/${certificateId}/download`;
    modal.style.display = 'flex';
}

function closeCertificateModal(event) {
    if (event && event.target.id !== 'certificateModal') return;
    document.getElementById('certificateModal').style.display = 'none';
    document.getElementById('certificateFrame').src = '';
}

document.addEventListener('keydown', (e) => {
    if (e.key === 'Escape') closeCertificateModal();
});
</script>
@endsection
