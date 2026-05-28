@extends('layouts.dashboard')
@section('title', 'Course Tags')
@section('page-title', 'Course Tag')
@section('user-name', 'Admin')
@section('sidebar')@include('components.admin-sidebar')@stop
@section('content')
@if(session('success'))<div class="mb-6 p-4 bg-green-50 border border-green-200 text-green-700 rounded-lg text-sm font-semibold">{{ session('success') }}</div>@endif
<div class="bg-white rounded-xl shadow-sm mb-6">
    <div class="p-6 border-b border-gray-100"><h3 class="font-bold text-heading">Add New Tag</h3></div>
    <div class="p-6">
        <form method="POST" action="/admin/course/tag" class="flex flex-wrap gap-4">
            @csrf
            <input type="text" name="name" placeholder="Tag Name" required class="flex-1 px-4 py-2.5 border border-gray-200 rounded-lg text-sm focus:ring-2 focus:ring-primary/20 focus:border-primary outline-none">
            <button type="submit" class="px-6 py-2.5 bg-primary text-white font-semibold rounded-lg hover:opacity-90 text-sm">Add</button>
        </form>
    </div>
</div>
<div class="bg-white rounded-xl shadow-sm">
    <div class="p-6 border-b border-gray-100"><h3 class="font-bold text-heading">Course Tags</h3></div>
    <div class="overflow-x-auto">
        <table class="w-full text-sm">
            <thead><tr class="bg-gray-50 text-heading/60 text-xs uppercase tracking-wider">
                <th class="text-left py-4 px-6 font-semibold">#</th>
                <th class="text-left py-4 px-6 font-semibold">Name</th>
                <th class="text-left py-4 px-6 font-semibold">Slug</th>
                <th class="text-left py-4 px-6 font-semibold">Action</th>
            </tr></thead>
            <tbody class="divide-y divide-gray-100">
                @forelse($tags as $i=>$t)
                <tr class="hover:bg-gray-50">
                    <td class="py-4 px-6 text-heading/70">{{ $i+1 }}</td>
                    <td class="py-4 px-6 font-semibold text-heading">{{ $t->name }}</td>
                    <td class="py-4 px-6 text-heading/70">{{ $t->slug }}</td>
                    <td class="py-4 px-6">
                        <form method="POST" action="/admin/course/tag/{{ $t->id }}" class="inline-flex gap-2">
                            @csrf
                            <input type="text" name="name" value="{{ $t->name }}" class="w-28 px-2 py-1 border border-gray-200 rounded text-xs" required>
                            <button type="submit" class="text-xs text-primary hover:underline font-semibold">Update</button>
                        </form>
                                    <form method="POST" action="/admin/course/tag/{{ $t->id }}/delete" class="inline ml-2" onsubmit="return confirm('Delete this tag?')">
                                        @csrf
                                        <button type="submit" class="text-xs text-red-500 hover:underline font-semibold">Delete</button>
                                    </form>
                    </td>
                </tr>
                @empty
                <tr><td colspan="4" class="py-8 text-center text-heading/50 text-sm">No tags found.</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
@endsection
