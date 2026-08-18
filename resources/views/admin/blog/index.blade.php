@extends('layouts.dashboard')
@section('title', 'Blog Manage')
@section('page-title', 'All Blogs')
@section('user-name', auth()->user()->name ?? 'Admin')
@section('sidebar')@include('components.admin-sidebar')@stop
@section('content')
@if(session('success'))
<div class="mb-6 px-6 py-4 bg-green-50 border border-green-200 text-green-700 rounded-xl text-sm font-semibold flex items-center gap-2">
    <i class="ri-checkbox-circle-fill"></i> {{ session('success') }}
</div>
@endif
<div class="bg-white rounded-xl shadow-sm mb-6">
    <div class="p-6 border-b border-gray-100">
        <h3 class="font-bold text-heading">Add New Blog Post</h3>
    </div>
    <div class="p-6">
        <form method="POST" action="/admin/blog" enctype="multipart/form-data" class="flex flex-wrap gap-4">
            @csrf
            <input type="text" name="title" placeholder="Blog Title" required class="flex-1 min-w-[200px] px-4 py-2.5 border border-gray-200 rounded-lg text-sm focus:ring-2 focus:ring-primary/20 focus:border-primary outline-none">
            <select name="blog_category_id" class="px-4 py-2.5 border border-gray-200 rounded-lg text-sm">
                <option value="">No Category</option>
                @foreach($categories as $cat)
                <option value="{{ $cat->id }}">{{ $cat->name }}</option>
                @endforeach
            </select>
            <textarea name="excerpt" placeholder="Excerpt (short summary)" class="flex-[2] px-4 py-2.5 border border-gray-200 rounded-lg text-sm" rows="2"></textarea>
            <input type="file" name="image" accept="image/jpeg,image/png,image/webp" class="px-4 py-2.5 border border-gray-200 rounded-lg text-sm">
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
                        <div class="flex items-center gap-2">
                            <button x-data @click="$dispatch('open-blog-edit-modal', @js($b))" class="text-xs text-primary hover:underline font-semibold">Edit</button>
                            <form method="POST" action="/admin/blog/{{ $b->id }}/delete" class="inline" x-data @submit.prevent="if(confirm('Delete this blog post?')) $el.submit()">
                                @csrf
                                <button type="submit" class="text-xs text-red-500 hover:underline font-semibold">Delete</button>
                            </form>
                        </div>
                    </td>
                </tr>
                @empty
                <tr><td colspan="7" class="py-8 text-center text-heading/50 text-sm">No blog posts found.</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>

{{-- Edit Blog Modal --}}
<div x-data="{ open: false, blog: {} }"
     @open-blog-edit-modal.window="blog = $event.detail; open = true"
     x-show="open"
     x-cloak
     class="fixed inset-0 z-50 flex items-center justify-center bg-black/50"
     @click.self="open = false">
    <div class="bg-white rounded-xl shadow-xl w-full max-w-lg mx-4 max-h-[90vh] overflow-y-auto">
        <div class="p-6 border-b border-gray-100 flex items-center justify-between">
            <h3 class="font-bold text-heading">Edit Blog Post</h3>
            <button @click="open = false" class="text-heading/40 hover:text-heading"><i class="ri-close-line text-xl"></i></button>
        </div>
        <form method="POST" :action="'/admin/blog/' + blog.id" enctype="multipart/form-data" class="p-6 grid grid-cols-1 gap-4">
            @csrf
            <input type="text" name="title" placeholder="Blog Title" required x-model="blog.title" class="px-4 py-2.5 border border-gray-200 rounded-lg text-sm">
            <select name="blog_category_id" x-model="blog.blog_category_id" class="px-4 py-2.5 border border-gray-200 rounded-lg text-sm">
                <option value="">No Category</option>
                @foreach($categories as $cat)
                <option value="{{ $cat->id }}">{{ $cat->name }}</option>
                @endforeach
            </select>
            <textarea name="excerpt" placeholder="Excerpt" x-model="blog.excerpt" rows="2" class="px-4 py-2.5 border border-gray-200 rounded-lg text-sm"></textarea>
            <textarea name="content" placeholder="Blog content (HTML supported)" required x-model="blog.content" rows="4" class="px-4 py-2.5 border border-gray-200 rounded-lg text-sm"></textarea>
            <select name="status" x-model="blog.status" class="px-4 py-2.5 border border-gray-200 rounded-lg text-sm">
                <option value="draft">Draft</option>
                <option value="published">Published</option>
            </select>
            <div>
                <label class="block text-xs text-heading/60 mb-1">Featured Image (leave empty to keep current)</label>
                <input type="file" name="image" accept="image/jpeg,image/png,image/webp" class="w-full px-4 py-2.5 border border-gray-200 rounded-lg text-sm">
            </div>
            <div class="flex items-center gap-2 pt-2">
                <button type="submit" class="px-6 py-2.5 bg-primary text-white font-semibold rounded-lg hover:opacity-90 text-sm">Update</button>
                <button type="button" @click="open = false" class="px-6 py-2.5 border border-gray-200 text-heading/70 font-semibold rounded-lg hover:bg-gray-50 text-sm">Cancel</button>
            </div>
        </form>
    </div>
</div>
@endsection
