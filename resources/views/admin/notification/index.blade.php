@extends('layouts.dashboard')
@section('title', 'Notification Templates')
@section('page-title', 'Notification')
@section('user-name', auth()->user()->name ?? 'Admin')
@section('sidebar')@include('components.admin-sidebar')@stop
@section('content')
@if (session('success'))
    <div class="mb-4 p-4 bg-green-50 border border-green-200 text-green-700 rounded-lg text-sm font-semibold">{{ session('success') }}</div>
@endif
@if ($errors->any())
    <div class="mb-4 p-4 bg-red-50 border border-red-200 text-red-700 rounded-lg text-sm">
        <ul class="list-disc pl-4 space-y-1">@foreach ($errors->all() as $error)<li>{{ $error }}</li>@endforeach</ul>
    </div>
@endif
<div class="bg-white rounded-xl shadow-sm mb-6">
    <div class="p-6 border-b border-gray-100"><h3 class="font-bold text-heading">Add New Template</h3></div>
    <div class="p-6">
        <form method="POST" action="/admin/notification" class="flex flex-wrap gap-4">
            @csrf
            <input type="text" name="template_name" placeholder="Template Name" required class="px-4 py-2.5 rounded-lg border border-heading/10 text-sm focus:outline-none focus:border-primary">
            <input type="text" name="subject" placeholder="Subject" class="px-4 py-2.5 rounded-lg border border-heading/10 text-sm focus:outline-none focus:border-primary">
            <select name="type" class="px-4 py-2.5 rounded-lg border border-heading/10 text-sm focus:outline-none focus:border-primary">
                <option value="email">Email</option><option value="in_app">In-App</option>
            </select>
            <select name="status" class="px-4 py-2.5 rounded-lg border border-heading/10 text-sm focus:outline-none focus:border-primary">
                <option value="active">Active</option><option value="inactive">Inactive</option>
            </select>
            <button type="submit" class="px-6 py-2.5 bg-primary text-white font-semibold rounded-lg hover:opacity-90 text-sm">Add</button>
        </form>
    </div>
</div>
<div class="bg-white rounded-xl shadow-sm mb-6">
    <div class="p-6 border-b border-gray-100"><h3 class="font-bold text-heading">Send Notification</h3></div>
    <div class="p-6">
        <form method="POST" action="/admin/notification/send-test" class="flex flex-wrap gap-4 items-end">
            @csrf
            <select name="template_id" required class="px-4 py-2.5 rounded-lg border border-heading/10 text-sm focus:outline-none focus:border-primary">
                <option value="">Select template</option>
                @foreach($templates as $t)
                <option value="{{ $t->id }}">{{ $t->template_name }}</option>
                @endforeach
            </select>
            <select name="channel" required class="px-4 py-2.5 rounded-lg border border-heading/10 text-sm focus:outline-none focus:border-primary">
                <option value="in_app">In-App</option>
                <option value="email">Email</option>
                <option value="both">Both</option>
            </select>
            <button type="submit" class="px-6 py-2.5 bg-primary text-white font-semibold rounded-lg hover:opacity-90 text-sm">Send to Self</button>
        </form>
    </div>
</div>
<div class="bg-white rounded-xl shadow-sm">
    <div class="p-6 border-b border-gray-100"><h3 class="font-bold text-heading">Notification Templates</h3></div>
    <div class="overflow-x-auto">
        <table class="w-full text-sm">
            <thead><tr class="bg-gray-50 text-heading/60 text-xs uppercase tracking-wider">
                <th class="text-left py-4 px-6 font-semibold">#</th>
                <th class="text-left py-4 px-6 font-semibold">Template</th>
                <th class="text-left py-4 px-6 font-semibold">Subject</th>
                <th class="text-left py-4 px-6 font-semibold">Type</th>
                <th class="text-left py-4 px-6 font-semibold">Status</th>
                <th class="text-left py-4 px-6 font-semibold">Action</th>
            </tr></thead>
            <tbody class="divide-y divide-gray-100">
                @forelse($templates as $i=>$t)
                <tr class="hover:bg-gray-50">
                    <td class="py-4 px-6 text-heading/70">{{ $i+1 }}</td>
                    <td class="py-4 px-6 font-semibold text-heading">{{ $t->template_name }}</td>
                    <td class="py-4 px-6 text-heading/70">{{ $t->subject ?? '--' }}</td>
                    <td class="py-4 px-6 text-heading/70">{{ $t->type === 'email' ? 'Email' : 'In-App' }}</td>
                    <td class="py-4 px-6"><span class="px-3 py-1 rounded-full text-xs font-bold {{ $t->status=='active' ? 'bg-green-100 text-green-700' : 'bg-red-100 text-red-700' }}">{{ ucfirst($t->status) }}</span></td>
                    <td class="py-4 px-6">
                        <form method="POST" action="/admin/notification/{{ $t->id }}" class="inline">
                            @csrf
                            <input type="hidden" name="template_name" value="{{ $t->template_name }}">
                            <input type="hidden" name="subject" value="{{ $t->subject }}">
                            <input type="hidden" name="type" value="{{ $t->type }}">
                            <input type="hidden" name="status" value="{{ $t->status === 'active' ? 'inactive' : 'active' }}">
                            <button type="submit" class="text-xs text-primary hover:underline font-semibold">{{ $t->status === 'active' ? 'Deactivate' : 'Activate' }}</button>
                        </form>
                        <form method="POST" action="/admin/notification/{{ $t->id }}/delete" class="inline ml-2" onsubmit="return confirm('Delete this template?')">
                            @csrf
                            <button type="submit" class="text-xs text-red-500 hover:underline font-semibold">Delete</button>
                        </form>
                    </td>
                </tr>
                @empty
                <tr><td colspan="6" class="py-8 text-center text-heading/50 text-sm">No templates found.</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
@endsection
