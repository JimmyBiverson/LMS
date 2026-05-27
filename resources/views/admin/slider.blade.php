@extends('layouts.dashboard')
@section('title', 'Slider')
@section('page-title', 'Slider')
@section('user-name', 'Admin')
@section('sidebar')@include('components.admin-sidebar')@stop
@section('content')
<div class="bg-white rounded-xl shadow-sm mb-6">
    <div class="p-6 border-b border-gray-100">
        <h3 class="font-bold text-heading">Add New Slider</h3>
    </div>
    <div class="p-6">
        <form method="POST" action="/admin/slider" class="flex flex-wrap gap-4">
            @csrf
            <input type="text" name="title" placeholder="Title" required class="flex-1 min-w-[150px] px-4 py-2.5 border border-gray-200 rounded-lg text-sm focus:ring-2 focus:ring-primary/20 focus:border-primary outline-none">
            <input type="text" name="subtitle" placeholder="Subtitle" class="flex-1 min-w-[150px] px-4 py-2.5 border border-gray-200 rounded-lg text-sm">
            <input type="number" name="order" placeholder="Order" value="0" class="w-20 px-4 py-2.5 border border-gray-200 rounded-lg text-sm">
            <select name="status" class="px-4 py-2.5 border border-gray-200 rounded-lg text-sm">
                <option value="active">Active</option>
                <option value="inactive">Inactive</option>
            </select>
            <button type="submit" class="px-6 py-2.5 bg-primary text-white font-semibold rounded-lg hover:opacity-90 text-sm">Add</button>
        </form>
    </div>
</div>
<div class="bg-white rounded-xl shadow-sm">
    <div class="p-6 border-b border-gray-100"><h3 class="font-bold text-heading">Homepage Sliders</h3></div>
    <div class="overflow-x-auto">
        <table class="w-full text-sm">
            <thead><tr class="bg-gray-50 text-heading/60 text-xs uppercase tracking-wider">
                <th class="text-left py-4 px-6 font-semibold">#</th>
                <th class="text-left py-4 px-6 font-semibold">Title</th>
                <th class="text-left py-4 px-6 font-semibold">Subtitle</th>
                <th class="text-left py-4 px-6 font-semibold">Order</th>
                <th class="text-left py-4 px-6 font-semibold">Status</th>
                <th class="text-left py-4 px-6 font-semibold">Action</th>
            </tr></thead>
            <tbody class="divide-y divide-gray-100">
                @forelse($sliders as $i=>$s)
                <tr class="hover:bg-gray-50">
                    <td class="py-4 px-6 text-heading/70">{{ $i+1 }}</td>
                    <td class="py-4 px-6 font-semibold text-heading">{{ $s->title }}</td>
                    <td class="py-4 px-6 text-heading/70">{{ $s->subtitle ?? '--' }}</td>
                    <td class="py-4 px-6 text-heading/70">{{ $s->order }}</td>
                    <td class="py-4 px-6"><span class="px-3 py-1 rounded-full text-xs font-bold {{ $s->status=='active' ? 'bg-green-100 text-green-700' : 'bg-red-100 text-red-700' }}">{{ ucfirst($s->status) }}</span></td>
                    <td class="py-4 px-6">
                        <form method="POST" action="/admin/slider/{{ $s->id }}/delete" class="inline" onsubmit="return confirm('Delete this slider?')">
                            @csrf
                            <button type="submit" class="text-xs text-red-500 hover:underline font-semibold">Delete</button>
                        </form>
                    </td>
                </tr>
                @empty
                <tr><td colspan="6" class="py-8 text-center text-heading/50 text-sm">No sliders found.</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
@endsection
