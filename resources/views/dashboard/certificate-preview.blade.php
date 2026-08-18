@extends('layouts.dashboard')
@section('title', 'Certificate Preview')
@section('page-title', 'Certificate Preview')
@section('user-name', auth()->user()?->name ?? 'Student')
@section('sidebar')@include('components.student-sidebar')@stop
@section('content')
<div class="space-y-6">
    <div class="rounded-3xl border border-indigo-100 bg-gradient-to-br from-indigo-600 via-violet-600 to-fuchsia-600 p-6 text-white shadow-xl">
        <div class="flex flex-col gap-4 md:flex-row md:items-center md:justify-between">
            <div>
                <p class="text-xs uppercase tracking-[0.24em] text-indigo-100">Achievement unlocked</p>
                <h1 class="mt-2 text-3xl font-black">Certificate of Completion</h1>
            </div>
            <div class="flex items-center gap-3 rounded-full bg-white/15 px-4 py-2 backdrop-blur-sm">
                <i class="ri-award-fill text-2xl"></i>
                <span class="font-semibold">Verified Earned Credential</span>
            </div>
        </div>
    </div>

    <div class="overflow-hidden rounded-3xl border border-slate-200 bg-white shadow-sm">
        <div class="border-b border-slate-200 bg-slate-50 px-6 py-4">
            <div class="flex flex-col gap-2 md:flex-row md:items-center md:justify-between">
                <div>
                    <p class="text-xs font-bold uppercase tracking-[0.2em] text-slate-500">Course Completion</p>
                    <h2 class="mt-1 text-xl font-extrabold text-slate-800">{{ $course->title ?? 'Course Completion' }}</h2>
                </div>
                <div class="flex items-center gap-2">
                    <a href="{{ route('certificate.download', $certificate) }}" class="inline-flex items-center gap-2 rounded-full bg-emerald-600 px-4 py-2 text-sm font-bold text-white hover:bg-emerald-700">
                        <i class="ri-download-line"></i> Download PDF
                    </a>
                    <a href="{{ url('/dashboard/certificate') }}" class="inline-flex items-center gap-2 rounded-full border border-slate-200 bg-white px-4 py-2 text-sm font-bold text-slate-700 hover:border-slate-300">
                        <i class="ri-arrow-left-line"></i> Back to Certificates
                    </a>
                </div>
            </div>
        </div>

        <div class="relative overflow-hidden p-4 md:p-8">
            <div class="pointer-events-none absolute inset-0 bg-[radial-gradient(circle_at_top_left,_rgba(99,102,241,0.12),_transparent_35%),radial-gradient(circle_at_bottom_right,_rgba(16,185,129,0.12),_transparent_25%)]"></div>
            <div class="relative mx-auto max-w-5xl rounded-[2rem] border-[10px] border-indigo-500 bg-white p-6 shadow-[0_30px_80px_-30px_rgba(79,70,229,0.55)] md:p-10">
                <div class="rounded-[1.5rem] border-2 border-indigo-100 bg-gradient-to-br from-white via-violet-50 to-sky-50 px-6 py-8 md:px-10 md:py-12">
                    <div class="mb-8 flex items-center justify-between text-xs font-bold uppercase tracking-[0.35em] text-slate-400">
                        <span>Edulab</span>
                        <span>{{ $certificate->created_at->format('F d, Y') }}</span>
                    </div>

                    <div class="text-center">
                        <div class="mx-auto mb-5 flex h-16 w-16 items-center justify-center rounded-full bg-indigo-100 text-3xl text-indigo-600">
                            <i class="ri-award-fill"></i>
                        </div>
                        <p class="text-xs font-bold uppercase tracking-[0.42em] text-slate-500">Certificate</p>
                        <h3 class="mt-4 text-3xl font-black uppercase tracking-[0.18em] text-indigo-600 md:text-5xl">of Completion</h3>
                    </div>

                    <div class="mt-8 text-center text-slate-600">
                        <p class="text-base md:text-lg">This is to certify that</p>
                        <div class="mt-4 inline-block border-b-[3px] border-indigo-500 px-10 pb-2 text-3xl font-black text-slate-800 md:text-5xl">
                            {{ $student?->name ?? 'Student Name' }}
                        </div>
                    </div>

                    <div class="mt-8 text-center text-slate-600">
                        <p class="text-base md:text-lg">has successfully completed the course</p>
                        <p class="mt-3 text-2xl font-extrabold text-indigo-600 md:text-3xl">{{ $course->title ?? 'Course Title' }}</p>
                    </div>

                    @if(!empty($certificate->description))
                        <p class="mx-auto mt-8 max-w-2xl text-center text-sm text-slate-500 md:text-base">{{ $certificate->description }}</p>
                    @endif

                    <div class="mt-10 grid gap-5 border-t border-slate-200 pt-6 md:grid-cols-3">
                        <div class="text-center">
                            <p class="text-[10px] font-bold uppercase tracking-[0.3em] text-slate-400">Issued On</p>
                            <p class="mt-2 text-base font-bold text-slate-700">{{ $certificate->created_at->format('d M Y') }}</p>
                        </div>
                        <div class="text-center">
                            <p class="text-[10px] font-bold uppercase tracking-[0.3em] text-slate-400">Instructor</p>
                            <p class="mt-2 text-base font-bold text-slate-700">{{ $course->instructor?->name ?? 'Course Instructor' }}</p>
                        </div>
                        <div class="text-center">
                            <p class="text-[10px] font-bold uppercase tracking-[0.3em] text-slate-400">Status</p>
                            <p class="mt-2 text-base font-bold text-emerald-600">Completed</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
