@extends('layouts.dashboard')
@section('title', 'Categories')
@section('page-title', 'Category')
@section('user-name', 'Admin')
@section('sidebar')@include('components.admin-sidebar')@stop
@section('content')
<div class="bg-white rounded-xl shadow-sm mb-6">
    <div class="p-6 border-b border-gray-100">
        <h3 class="font-bold text-heading">Add New Category</h3>
    </div>
    <div class="p-6">
        <form method="POST" action="/admin/category" class="flex flex-wrap gap-4">
            @csrf
            <input type="text" name="name" placeholder="Category Name" required class="flex-1 px-4 py-2.5 border border-gray-200 rounded-lg text-sm focus:ring-2 focus:ring-primary/20 focus:border-primary outline-none">
            <input type="text" name="description" placeholder="Description (optional)" class="flex-1 px-4 py-2.5 border border-gray-200 rounded-lg text-sm focus:ring-2 focus:ring-primary/20 focus:border-primary outline-none">
            <select name="status" class="px-4 py-2.5 border border-gray-200 rounded-lg text-sm focus:ring-2 focus:ring-primary/20 focus:border-primary outline-none">
                <option value="active">Active</option>
                <option value="inactive">Inactive</option>
            </select>
            <button type="submit" class="px-6 py-2.5 bg-primary text-white font-semibold rounded-lg hover:opacity-90 text-sm">Add</button>
        </form>
    </div>
</div>
<div class="bg-white rounded-xl shadow-sm">
    <div class="p-6 border-b border-gray-100"><h3 class="font-bold text-heading">Categories</h3></div>
    <div class="overflow-x-auto">
        <table class="w-full text-sm">
            <thead><tr class="bg-gray-50 text-heading/60 text-xs uppercase tracking-wider">
                <th class="text-left py-4 px-6 font-semibold">#</th>
                <th class="text-left py-4 px-6 font-semibold">Name</th>
                <th class="text-left py-4 px-6 font-semibold">Slug</th>
                <th class="text-left py-4 px-6 font-semibold">Subjects</th>
                <th class="text-left py-4 px-6 font-semibold">Status</th>
                <th class="text-left py-4 px-6 font-semibold">Action</th>
            </tr></thead>
            <tbody class="divide-y divide-gray-100">
                @forelse($categories as $i=>$c)
                <tr class="hover:bg-gray-50">
                    <td class="py-4 px-6 text-heading/70">{{ $i+1 }}</td>
                    <td class="py-4 px-6 font-semibold text-heading">{{ $c->name }}</td>
                    <td class="py-4 px-6 text-heading/70">{{ $c->slug }}</td>
                    <td class="py-4 px-6 text-heading/70">{{ $c->subjects_count }}</td>
                    <td class="py-4 px-6"><span class="px-3 py-1 rounded-full text-xs font-bold {{ $c->status=='active' ? 'bg-green-100 text-green-700' : 'bg-red-100 text-red-700' }}">{{ ucfirst($c->status) }}</span></td>
                    <td class="py-4 px-6">
                        <form method="POST" action="/admin/category/{{ $c->id }}" class="inline">
                            @csrf
                            <input type="hidden" name="name" value="{{ $c->name }}">
                            <input type="hidden" name="description" value="{{ $c->description }}">
                            <input type="hidden" name="status" value="{{ $c->status=='active' ? 'inactive' : 'active' }}">
                            <button type="submit" class="text-xs text-primary hover:underline font-semibold">{{ $c->status=='active' ? 'Deactivate' : 'Activate' }}</button>
                        </form>
                        <form method="POST" action="/admin/category/{{ $c->id }}/delete" class="inline ml-2" onsubmit="return confirm('Delete this category?')">
                            @csrf
                            <button type="submit" class="text-xs text-red-500 hover:underline font-semibold">Delete</button>
                        </form>
                    </td>
                </tr>
                @empty
                <tr><td colspan="6" class="py-8 text-center text-heading/50 text-sm">No categories found.</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
@endsection
