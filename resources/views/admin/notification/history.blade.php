@extends('layouts.dashboard')
@section('title', 'Notification History')
@section('page-title', 'Notification History')
@section('user-name', auth()->user()->name ?? 'Admin')
@section('sidebar')@include('components.admin-sidebar')@stop
@section('content')
<div class="bg-white rounded-xl shadow-sm">
    <div class="p-6 border-b border-gray-100"><h3 class="font-bold text-heading">Sent Notifications</h3></div>
    <div class="overflow-x-auto">
        <table class="w-full text-sm">
            <thead><tr class="bg-gray-50 text-heading/60 text-xs uppercase tracking-wider">
                <th class="text-left py-4 px-6 font-semibold">#</th>
                <th class="text-left py-4 px-6 font-semibold">Recipient</th>
                <th class="text-left py-4 px-6 font-semibold">Subject</th>
                <th class="text-left py-4 px-6 font-semibold">Message</th>
                <th class="text-left py-4 px-6 font-semibold">Channel</th>
                <th class="text-left py-4 px-6 font-semibold">Sent Date</th>
                <th class="text-left py-4 px-6 font-semibold">Status</th>
                <th class="text-left py-4 px-6 font-semibold">Action</th>
            </tr></thead>
            <tbody class="divide-y divide-gray-100">
                @forelse ($logs as $log)
                <tr class="hover:bg-gray-50 {{ !$log->is_read ? 'bg-blue-50/30' : '' }}">
                    <td class="py-4 px-6 text-heading/70">{{ $logs->firstItem() + $loop->index }}</td>
                    <td class="py-4 px-6 font-semibold text-heading">{{ $log->user?->email ?? 'Deleted User' }}</td>
                    <td class="py-4 px-6 text-heading/70">{{ $log->template?->template_name ?? $log->subject }}</td>
                    <td class="py-4 px-6 text-heading/70 max-w-xs truncate" title="{{ $log->body ?? '' }}">{{ $log->body ?? '-' }}</td>
                    <td class="py-4 px-6 text-heading/70">{{ ucfirst($log->channel) }}</td>
                    <td class="py-4 px-6 text-heading/70">{{ $log->sent_at?->format('Y-m-d H:i') ?? $log->created_at->format('Y-m-d H:i') }}</td>
                    <td class="py-4 px-6">
                        @if ($log->is_read)
                            <span class="px-3 py-1 rounded-full text-xs font-bold bg-green-100 text-green-700">Read</span>
                        @else
                            <span class="px-3 py-1 rounded-full text-xs font-bold bg-yellow-100 text-yellow-700">Unread</span>
                        @endif
                    </td>
                    <td class="py-4 px-6">
                        @if ($log->link)
                            <a href="{{ $log->link }}" class="inline-flex items-center gap-1 px-3 py-1 rounded-lg text-xs font-bold bg-primary-50 text-primary hover:bg-primary hover:text-white transition-all duration-300">
                                <i class="ri-external-link-line"></i> View
                            </a>
                        @else
                            <span class="text-heading/40 text-xs">-</span>
                        @endif
                    </td>
                </tr>
                @empty
                <tr><td colspan="8" class="py-12 text-center text-heading/50">No notifications sent yet.</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>
    @if ($logs->hasPages())
    <div class="p-4 border-t border-gray-100">{{ $logs->links() }}</div>
    @endif
</div>
@endsection