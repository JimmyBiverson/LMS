@extends('layouts.dashboard')
@section('title', 'Blog Manage')
@section('page-title', 'All Blog')
@section('user-name', 'Admin')
@section('sidebar')@include('components.admin-sidebar')@stop
@section('content')
<div class="bg-white rounded-xl shadow-sm mb-6">
    <div class="p-6 border-b border-gray-100">
        <h3 class="font-bold text-heading">Add New Blog Post</h3>
    </div>
    <div class="p-6">
        <form method="POST" action="/admin/blog" class="flex flex-wrap gap-4">
            @csrf
            <input type="text" name="title" placeholder="Blog Title" required class="flex-1 min-w-[200px] px-4 py-2.5 border border-gray-200 rounded-lg text-sm focus:ring-2 focus:ring-primary/20 focus:border-primary outline-none">
            <select name="blog_category_id" class="px-4 py-2.5 border border-gray-200 rounded-lg text-sm">
                <option value="">No Category</option>
                @foreach($categories as $cat)
                <option value="{{ $cat->id }}">{{ $cat->name }}</option>
                @endforeach
            </select>
            <textarea name="excerpt" placeholder="Excerpt (short summary)" class="flex-[2] px-4 py-2.5 border border-gray-200 rounded-lg text-sm" rows="2"></textarea>
            <textarea name="content" placeholder="Blog content (HTML supported)" required class="w-full px-4 py-2.5 border border-gray-200 rounded-lg text-sm" rows="4"></textarea>
            <select name="status" class="px-4 py-2.5 border border-gray-200 rounded-lg text-sm">
                <option value="draft">Draft</option>
                <option value="published">Published</option>
            </select>
            <button type="submit" class="px-6 py-2.5 bg-primary text-white font-semibold rounded-lg hover:opacity-90 text-sm">Add</button>
        </form>
    </div>
</div>
<div class="bg-white rounded-xl shadow-sm">
    <div class="p-6 border-b border-gray-100"><h3 class="font-bold text-heading">Blog Posts</h3></div>
    <div class="overflow-x-auto">
        <table class="w-full text-sm">
            <thead><tr class="bg-gray-50 text-heading/60 text-xs uppercase tracking-wider">
                <th class="text-left py-4 px-6 font-semibold">#</th>
                <th class="text-left py-4 px-6 font-semibold">Title</th>
                <th class="text-left py-4 px-6 font-semibold">Category</th>
                <th class="text-left py-4 px-6 font-semibold">Author</th>
                <th class="text-left py-4 px-6 font-semibold">Date</th>
                <th class="text-left py-4 px-6 font-semibold">Status</th>
                <th class="text-left py-4 px-6 font-semibold">Action</th>
            </tr></thead>
            <tbody class="divide-y divide-gray-100">
                @forelse($blogs as $i=>$b)
                <tr class="hover:bg-gray-50">
                    <td class="py-4 px-6 text-heading/70">{{ $i+1 }}</td>
                    <td class="py-4 px-6 font-semibold text-heading max-w-xs truncate">{{ $b->title }}</td>
                    <td class="py-4 px-6 text-heading/70">{{ $b->category?->name ?? '--' }}</td>
                    <td class="py-4 px-6 text-heading/70">{{ $b->author?->name ?? '--' }}</td>
                    <td class="py-4 px-6 text-heading/70">{{ $b->created_at->format('Y-m-d') }}</td>
                    <td class="py-4 px-6"><span class="px-3 py-1 rounded-full text-xs font-bold {{ $b->status=='published' ? 'bg-green-100 text-green-700' : 'bg-yellow-100 text-yellow-700' }}">{{ ucfirst($b->status) }}</span></td>
                    <td class="py-4 px-6">
                        <form method="POST" action="/admin/blog/{{ $b->id }}/delete" class="inline" onsubmit="return confirm('Delete this blog post?')">
                            @csrf
                            <button type="submit" class="text-xs text-red-500 hover:underline font-semibold">Delete</button>
                        </form>
                    </td>
                </tr>
                @empty
                <tr><td colspan="7" class="py-8 text-center text-heading/50 text-sm">No blog posts found.</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
@endsection
