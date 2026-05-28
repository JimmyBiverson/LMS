@extends('layouts.dashboard')
@section('title', 'Bundle Courses')
@section('page-title', 'Bundle Course')
@section('user-name', 'Admin')
@section('sidebar')@include('components.admin-sidebar')@stop
@section('content')
<div class="max-w-5xl">
    @if (session('success'))
        <div class="mb-4 p-4 bg-green-50 border border-green-200 text-green-700 rounded-lg text-sm font-semibold">{{ session('success') }}</div>
    @endif
    @if ($errors->any())
        <div class="mb-4 p-4 bg-red-50 border border-red-200 text-red-700 rounded-lg text-sm">
            <ul class="list-disc pl-4 space-y-1">@foreach ($errors->all() as $error)<li>{{ $error }}</li>@endforeach</ul>
        </div>
    @endif

    <div class="bg-white rounded-xl shadow-sm mb-6">
        <div class="p-6 border-b border-gray-100 flex items-center justify-between">
            <h3 class="font-bold text-heading">Bundle Courses</h3>
            <button onclick="document.getElementById('createForm').classList.toggle('hidden')" class="px-4 py-2 bg-primary text-white text-sm font-bold rounded-full hover:opacity-90">+ New Bundle</button>
        </div>
        <form id="createForm" method="POST" action="/admin/course/bundle" class="hidden p-6 border-b border-gray-100 space-y-4">
            @csrf
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div><label class="block text-sm font-semibold text-heading mb-1">Title *</label><input name="title" type="text" value="{{ old('title') }}" class="w-full px-4 py-3 rounded-lg border border-heading/10 text-sm focus:outline-none focus:border-primary"></div>
                <div><label class="block text-sm font-semibold text-heading mb-1">Price *</label><input name="price" type="number" step="0.01" value="{{ old('price') }}" class="w-full px-4 py-3 rounded-lg border border-heading/10 text-sm focus:outline-none focus:border-primary"></div>
                <div><label class="block text-sm font-semibold text-heading mb-1">Sale Price</label><input name="sale_price" type="number" step="0.01" value="{{ old('sale_price') }}" class="w-full px-4 py-3 rounded-lg border border-heading/10 text-sm focus:outline-none focus:border-primary"></div>
                <div><label class="block text-sm font-semibold text-heading mb-1">Level</label><input name="level" type="text" value="{{ old('level') }}" class="w-full px-4 py-3 rounded-lg border border-heading/10 text-sm focus:outline-none focus:border-primary"></div>
                <div><label class="block text-sm font-semibold text-heading mb-1">Status</label><select name="status" class="w-full px-4 py-3 rounded-lg border border-heading/10 text-sm focus:outline-none focus:border-primary"><option value="active">Active</option><option value="inactive">Inactive</option></select></div>
            </div>
            <div><label class="block text-sm font-semibold text-heading mb-1">Description</label><textarea name="description" rows="3" class="w-full px-4 py-3 rounded-lg border border-heading/10 text-sm focus:outline-none focus:border-primary">{{ old('description') }}</textarea></div>
            <div><label class="block text-sm font-semibold text-heading mb-1">Courses</label>
                <select name="course_ids[]" multiple class="w-full px-4 py-3 rounded-lg border border-heading/10 text-sm focus:outline-none focus:border-primary">
                    @foreach($courses as $course)
                        <option value="{{ $course->id }}">{{ $course->title }}</option>
                    @endforeach
                </select>
            </div>
            <button type="submit" class="px-6 py-2 bg-primary text-white font-bold rounded-full hover:opacity-90">Create</button>
        </form>
        <div class="overflow-x-auto">
            <table class="w-full text-sm">
                <thead><tr class="bg-gray-50 text-heading/60 text-xs uppercase tracking-wider">
                    <th class="text-left py-4 px-6 font-semibold">#</th>
                    <th class="text-left py-4 px-6 font-semibold">Title</th>
                    <th class="text-left py-4 px-6 font-semibold">Courses</th>
                    <th class="text-left py-4 px-6 font-semibold">Price</th>
                    <th class="text-left py-4 px-6 font-semibold">Status</th>
                    <th class="text-left py-4 px-6 font-semibold">Actions</th>
                </tr></thead>
                <tbody class="divide-y divide-gray-100">
                    @forelse($bundles as $bundle)
                    <tr class="hover:bg-gray-50">
                        <td class="py-4 px-6 text-heading/70">{{ $bundle->id }}</td>
                        <td class="py-4 px-6 font-semibold text-heading">{{ $bundle->title }}</td>
                        <td class="py-4 px-6 text-heading/70">{{ $bundle->courses_count ?? 0 }}</td>
                        <td class="py-4 px-6 text-heading/70">{{ $bundle->displayPrice() }}</td>
                        <td class="py-4 px-6"><span class="px-3 py-1 rounded-full text-xs font-bold {{ $bundle->status === 'active' ? 'bg-green-100 text-green-700' : 'bg-red-100 text-red-700' }}">{{ ucfirst($bundle->status) }}</span></td>
                        <td class="py-4 px-6">
                            <form method="POST" action="/admin/course/bundle/{{ $bundle->id }}" class="inline-flex gap-1 items-center">
                                @csrf
                                <input type="text" name="title" value="{{ $bundle->title }}" class="w-24 px-2 py-1 border border-gray-200 rounded text-xs" required>
                                <input type="number" step="0.01" name="price" value="{{ $bundle->price }}" class="w-16 px-2 py-1 border border-gray-200 rounded text-xs" required>
                                <select name="status" class="px-2 py-1 border border-gray-200 rounded text-xs">
                                    <option value="active" @selected($bundle->status === 'active')>Active</option>
                                    <option value="inactive" @selected($bundle->status !== 'active')>Inactive</option>
                                </select>
                                <button type="submit" class="text-primary hover:underline text-xs font-semibold">Update</button>
                            </form>
                            <form method="POST" action="/admin/course/bundle/{{ $bundle->id }}/delete" class="inline ml-1" onsubmit="return confirm('Delete this bundle?')">
                                @csrf
                                <button type="submit" class="text-red-500 hover:underline text-xs font-semibold">Delete</button>
                            </form>
                        </td>
                    </tr>
                    @empty
                    <tr><td colspan="6" class="py-8 text-center text-heading/40">No bundles yet.</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection
