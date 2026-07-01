@extends('layouts.dashboard')
@section('title', 'Page Manage')
@section('page-title', 'Page Manage')
@section('user-name', auth()->user()->name ?? 'Admin')
@section('sidebar')@include('components.admin-sidebar')@stop
@section('content')
<div class="bg-white rounded-xl shadow-sm mb-6">
    <div class="p-6 border-b border-gray-100">
        <h3 class="font-bold text-heading">Add New Page</h3>
    </div>
    <div class="p-6">
        <form method="POST" action="/admin/page" class="flex flex-wrap gap-4">
            @csrf
            <input type="text" name="title" placeholder="Page Title" required class="flex-1 min-w-[200px] px-4 py-2.5 border border-gray-200 rounded-lg text-sm focus:ring-2 focus:ring-primary/20 focus:border-primary outline-none">
            <textarea name="content" placeholder="Page content (HTML supported)" required class="w-full px-4 py-2.5 border border-gray-200 rounded-lg text-sm" rows="4"></textarea>
            <select name="status" class="px-4 py-2.5 border border-gray-200 rounded-lg text-sm">
                <option value="draft">Draft</option>
                <option value="published">Published</option>
            </select>
            <button type="submit" class="px-6 py-2.5 bg-primary text-white font-semibold rounded-lg hover:opacity-90 text-sm">Add</button>
        </form>
    </div>
</div>
<div class="bg-white rounded-xl shadow-sm">
    <div class="p-6 border-b border-gray-100"><h3 class="font-bold text-heading">Pages</h3></div>
    <div class="overflow-x-auto">
        <table class="w-full text-sm">
            <thead><tr class="bg-gray-50 text-heading/60 text-xs uppercase tracking-wider">
                <th class="text-left py-4 px-6 font-semibold">#</th>
                <th class="text-left py-4 px-6 font-semibold">Title</th>
                <th class="text-left py-4 px-6 font-semibold">Slug</th>
                <th class="text-left py-4 px-6 font-semibold">Status</th>
                <th class="text-left py-4 px-6 font-semibold">Action</th>
            </tr></thead>
            <tbody class="divide-y divide-gray-100">
                @forelse($pages as $i=>$p)
                <tr class="hover:bg-gray-50">
                    <td class="py-4 px-6 text-heading/70">{{ $i+1 }}</td>
                    <td class="py-4 px-6 font-semibold text-heading">{{ $p->title }}</td>
                    <td class="py-4 px-6 text-heading/70">{{ $p->slug }}</td>
                    <td class="py-4 px-6"><span class="px-3 py-1 rounded-full text-xs font-bold {{ $p->status=='published' ? 'bg-green-100 text-green-700' : 'bg-yellow-100 text-yellow-700' }}">{{ ucfirst($p->status) }}</span></td>
                    <td class="py-4 px-6">
                        <form method="POST" action="/admin/page/{{ $p->id }}/delete" class="inline" onsubmit="return confirm('Delete this page?')">
                            @csrf
                            <button type="submit" class="text-xs text-red-500 hover:underline font-semibold">Delete</button>
                        </form>
                    </td>
                </tr>
                @empty
                <tr><td colspan="5" class="py-8 text-center text-heading/50 text-sm">No pages found.</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
@endsection
