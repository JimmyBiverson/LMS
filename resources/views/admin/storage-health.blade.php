@extends('layouts.dashboard')
@section('title', 'LMS Storage Health Check')
@section('page-title', 'Storage & Media Health Check')
@section('user-name', auth()->user()->name ?? 'Admin')
@section('sidebar')@include('components.admin-sidebar')@stop

@section('content')
@php
$roleBg = 'bg-slate-50';
$roleSidebarBorder = 'border-slate-200';
$roleLogoBg = 'bg-slate-800';
$roleAccent = 'slate-700';
$roleHover = 'slate-100';
$roleHeaderBg = 'bg-white';
$roleAvatarBg = 'bg-slate-100';
$roleAvatarText = 'text-slate-700';
$notifUrl = url('admin/notification');
@endphp

<div class="space-y-6">
    {{-- Summary Panel --}}
    <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6">
        <div class="flex flex-col md:flex-row md:items-center justify-between gap-4">
            <div>
                <h3 class="text-lg font-bold text-heading">Storage Connection Status</h3>
                <p class="text-sm text-heading/60">Verify if the public storage symlink and directories are configured correctly for media display.</p>
            </div>
            <div>
                <form action="/admin/storage-health/fix" method="POST">
                    @csrf
                    <button type="submit" class="px-5 py-2.5 bg-primary text-white font-semibold rounded-lg hover:opacity-90 transition-all duration-300 text-sm flex items-center gap-2 shadow-sm">
                        <i class="ri-tools-fill"></i> Fix Symlink & Folders
                    </button>
                </form>
            </div>
        </div>
    </div>

    {{-- Connection Health Grid --}}
    <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
        {{-- Symlink Status --}}
        <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6 flex flex-col justify-between">
            <div>
                <div class="flex items-center justify-between mb-4">
                    <span class="text-xs font-bold text-heading/50 uppercase tracking-wider">Public Symlink</span>
                    @if($symlinkExists && $isSymlink)
                        <span class="px-2.5 py-1 bg-green-50 text-green-700 border border-green-200 rounded-full text-xs font-semibold">Active</span>
                    @else
                        <span class="px-2.5 py-1 bg-red-50 text-red-700 border border-red-200 rounded-full text-xs font-semibold">Broken/Missing</span>
                    @endif
                </div>
                <div class="space-y-2">
                    <p class="text-3xl font-extrabold text-heading">
                        @if($symlinkExists && $isSymlink)
                            Connected
                        @else
                            Not Linked
                        @endif
                    </p>
                    <p class="text-xs text-heading/40 break-all">Path: <code>{{ $storagePath }}</code></p>
                </div>
            </div>
            <div class="mt-4 pt-4 border-t border-gray-50 text-xs text-heading/60">
                @if($symlinkExists && $isSymlink)
                    ✓ <code>public/storage</code> correctly maps to your storage folder.
                @else
                    ✗ Symbolic link is missing. Uploaded images will return 404. Click the "Fix" button to create it.
                @endif
            </div>
        </div>

        {{-- Writable Folder Status --}}
        <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6 flex flex-col justify-between">
            <div>
                <div class="flex items-center justify-between mb-4">
                    <span class="text-xs font-bold text-heading/50 uppercase tracking-wider">Storage Directory</span>
                    @if($isWritable)
                        <span class="px-2.5 py-1 bg-green-50 text-green-700 border border-green-200 rounded-full text-xs font-semibold">Writable</span>
                    @else
                        <span class="px-2.5 py-1 bg-red-50 text-red-700 border border-red-200 rounded-full text-xs font-semibold">Protected</span>
                    @endif
                </div>
                <div class="space-y-2">
                    <p class="text-3xl font-extrabold text-heading">
                        @if($isWritable)
                            Writable
                        @else
                            Read-Only
                        @endif
                    </p>
                    <p class="text-xs text-heading/40 break-all">Target: <code>{{ $targetPath }}</code></p>
                </div>
            </div>
            <div class="mt-4 pt-4 border-t border-gray-50 text-xs text-heading/60">
                @if($isWritable)
                    ✓ PHP has full permissions to write files to storage.
                @else
                    ✗ Storage folder is not writable. Change target directory permissions (e.g. chmod 775).
                @endif
            </div>
        </div>

        {{-- Read/Write HTTP Test --}}
        <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6 flex flex-col justify-between">
            <div>
                <div class="flex items-center justify-between mb-4">
                    <span class="text-xs font-bold text-heading/50 uppercase tracking-wider">R/W Test Execution</span>
                    @if($testFileWritten && $testFileReadable)
                        <span class="px-2.5 py-1 bg-green-50 text-green-700 border border-green-200 rounded-full text-xs font-semibold">Verified</span>
                    @else
                        <span class="px-2.5 py-1 bg-yellow-50 text-yellow-700 border border-yellow-200 rounded-full text-xs font-semibold">Pending</span>
                    @endif
                </div>
                <div class="space-y-2">
                    <p class="text-3xl font-extrabold text-heading">
                        @if($testFileWritten && $testFileReadable)
                            Passed
                        @else
                            Running Check
                        @endif
                    </p>
                    <p class="text-xs text-heading/40">Test Endpoint: <a href="{{ $testFileUrl ?? '#' }}" target="_blank" class="text-primary hover:underline font-semibold break-all">{{ $testFileUrl ? basename($testFileUrl) : 'N/A' }}</a></p>
                </div>
            </div>
            <div class="mt-4 pt-4 border-t border-gray-50 text-xs text-heading/60">
                @if($testFileWritten && $testFileReadable)
                    ✓ Wrote test file and verified it is loadable over HTTP assets.
                @else
                    ✗ Did not pass HTTP loopback. Make sure webserver symlink is active and accessible.
                @endif
            </div>
        </div>
    </div>

    {{-- Subdirectories Detail --}}
    <div class="bg-white rounded-xl shadow-sm border border-gray-100">
        <div class="p-6 border-b border-gray-100">
            <h3 class="font-bold text-heading">Required Directories Health Status</h3>
            <p class="text-xs text-heading/60">Each folder registers uploads for courses, sections, and site parameters.</p>
        </div>
        <div class="overflow-x-auto">
            <table class="w-full text-sm">
                <thead>
                    <tr class="bg-gray-50 text-heading/60 text-xs uppercase tracking-wider border-b border-gray-100">
                        <th class="text-left py-4 px-6 font-semibold">Storage Folder</th>
                        <th class="text-left py-4 px-6 font-semibold font-mono">Relative Path</th>
                        <th class="text-left py-4 px-6 font-semibold">Existence</th>
                        <th class="text-left py-4 px-6 font-semibold">Permissions</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100">
                    @foreach($dirsStatus as $name => $status)
                    <tr class="hover:bg-slate-50/50">
                        <td class="py-4 px-6 font-semibold text-heading">{{ ucwords(str_replace(['/', '_'], ' ', $name)) }}</td>
                        <td class="py-4 px-6 font-mono text-xs text-heading/70">{{ 'storage/app/public/' . $name }}</td>
                        <td class="py-4 px-6">
                            @if($status['exists'])
                                <span class="text-green-600 font-semibold flex items-center gap-1"><i class="ri-checkbox-circle-fill"></i> Exists</span>
                            @else
                                <span class="text-red-500 font-semibold flex items-center gap-1"><i class="ri-error-warning-fill"></i> Missing</span>
                            @endif
                        </td>
                        <td class="py-4 px-6">
                            @if($status['writable'])
                                <span class="text-green-600 font-semibold flex items-center gap-1"><i class="ri-checkbox-circle-fill"></i> Writable</span>
                            @else
                                <span class="text-red-500 font-semibold flex items-center gap-1"><i class="ri-error-warning-fill"></i> Unwritable</span>
                            @endif
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection
