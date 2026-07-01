@extends('layouts.dashboard')
@section('title', 'Slider')
@section('page-title', 'Slider')
@section('user-name', auth()->user()->name ?? 'Admin')
@section('sidebar')@include('components.admin-sidebar')@stop
@section('content')
<div class="bg-white rounded-xl shadow-sm mb-6">
    <div class="p-6 border-b border-gray-100">
        <h3 class="font-bold text-heading">Add New Slider</h3>
    </div>
    <div class="p-6">
        <form method="POST" action="/admin/slider" enctype="multipart/form-data" class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
            @csrf
            <input type="text" name="title" placeholder="Title" required class="px-4 py-2.5 border border-gray-200 rounded-lg text-sm focus:ring-2 focus:ring-primary/20 focus:border-primary outline-none">
            <input type="text" name="subtitle" placeholder="Subtitle" class="px-4 py-2.5 border border-gray-200 rounded-lg text-sm">
            <textarea name="description" placeholder="Description" rows="2" class="px-4 py-2.5 border border-gray-200 rounded-lg text-sm focus:ring-2 focus:ring-primary/20 focus:border-primary outline-none md:col-span-2 lg:col-span-3"></textarea>
            <input type="text" name="btn_text" placeholder="Button Text" class="px-4 py-2.5 border border-gray-200 rounded-lg text-sm">
            <input type="text" name="btn_link" placeholder="Button Link" class="px-4 py-2.5 border border-gray-200 rounded-lg text-sm">
            <input type="number" name="order" placeholder="Order" value="0" class="px-4 py-2.5 border border-gray-200 rounded-lg text-sm">
            <select name="status" class="px-4 py-2.5 border border-gray-200 rounded-lg text-sm">
                <option value="active">Active</option>
                <option value="inactive">Inactive</option>
            </select>
            <input type="file" name="image" accept="image/jpeg,image/png,image/webp" class="px-4 py-2.5 border border-gray-200 rounded-lg text-sm">
            <input type="number" name="duration" placeholder="Duration (seconds)" value="6" min="1" max="3600" class="px-4 py-2.5 border border-gray-200 rounded-lg text-sm">
            <button type="submit" class="px-6 py-2.5 bg-primary text-white font-semibold rounded-lg hover:opacity-90 text-sm md:col-span-2 lg:col-span-3">Add Slider</button>
        </form>
    </div>
</div>

<div class="bg-white rounded-xl shadow-sm">
    <div class="p-6 border-b border-gray-100"><h3 class="font-bold text-heading">Homepage Sliders</h3></div>
    <div class="overflow-x-auto">
        <table class="w-full text-sm">
            <thead><tr class="bg-gray-50 text-heading/60 text-xs uppercase tracking-wider">
                <th class="text-left py-4 px-6 font-semibold">#</th>
                <th class="text-left py-4 px-6 font-semibold">Image</th>
                <th class="text-left py-4 px-6 font-semibold">Title</th>
                <th class="text-left py-4 px-6 font-semibold">Subtitle</th>
                <th class="text-left py-4 px-6 font-semibold">Order</th>
                <th class="text-left py-4 px-6 font-semibold">Duration</th>
                <th class="text-left py-4 px-6 font-semibold">Status</th>
                <th class="text-left py-4 px-6 font-semibold">Action</th>
            </tr></thead>
            <tbody class="divide-y divide-gray-100">
                @forelse($sliders as $i=>$s)
                <tr class="hover:bg-gray-50">
                    <td class="py-4 px-6 text-heading/70">{{ $i+1 }}</td>
                    <td class="py-4 px-6">
                        @if($s->image)
                        <img src="/storage/{{ $s->image }}" alt="{{ $s->title }}" width="64" height="40" loading="lazy" class="w-16 h-10 object-cover rounded">
                        @else
                        <span class="text-heading/40 text-xs">No image</span>
                        @endif
                    </td>
                    <td class="py-4 px-6 font-semibold text-heading">{{ $s->title }}</td>
                    <td class="py-4 px-6 text-heading/70">{{ $s->subtitle ?? '--' }}</td>
                    <td class="py-4 px-6 text-heading/70">{{ $s->order }}</td>
                    <td class="py-4 px-6 text-heading/70">{{ $s->duration ?? 6 }}s</td>
                    <td class="py-4 px-6"><span class="px-3 py-1 rounded-full text-xs font-bold {{ $s->status=='active' ? 'bg-green-100 text-green-700' : 'bg-red-100 text-red-700' }}">{{ ucfirst($s->status) }}</span></td>
                    <td class="py-4 px-6">
                        <div class="flex items-center gap-2">
                            <button x-data @click="$dispatch('open-edit-modal', @js($s))" class="px-3 py-1.5 text-xs font-semibold bg-primary-50 text-primary rounded-lg hover:bg-primary hover:text-white transition-all duration-300">Edit</button>
                            <form method="POST" action="/admin/slider/{{ $s->id }}/delete" x-data @submit.prevent="if(confirm('Delete this slider?')) $el.submit()">
                                @csrf
                                <button type="submit" class="px-3 py-1.5 text-xs font-semibold bg-red-50 text-red-600 rounded-lg hover:bg-red-600 hover:text-white transition-all duration-300">Delete</button>
                            </form>
                        </div>
                    </td>
                </tr>
                @empty
                <tr><td colspan="8" class="py-8 text-center text-heading/50 text-sm">No sliders found.</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>

<div x-data="{ open: false, slider: {} }"
     @open-edit-modal.window="slider = $event.detail; open = true"
     x-show="open"
     x-cloak
     class="fixed inset-0 z-50 flex items-center justify-center bg-black/50"
     @click.self="open = false">
    <div class="bg-white rounded-xl shadow-xl w-full max-w-lg mx-4 max-h-[90vh] overflow-y-auto">
        <div class="p-6 border-b border-gray-100 flex items-center justify-between">
            <h3 class="font-bold text-heading">Edit Slider</h3>
            <button @click="open = false" class="text-heading/40 hover:text-heading"><i class="ri-close-line text-xl"></i></button>
        </div>
        <form method="POST" :action="'/admin/slider/' + slider.id" enctype="multipart/form-data" class="p-6 grid grid-cols-1 gap-4">
            @csrf
            <input type="text" name="title" placeholder="Title" required x-model="slider.title" class="px-4 py-2.5 border border-gray-200 rounded-lg text-sm focus:ring-2 focus:ring-primary/20 focus:border-primary outline-none">
            <input type="text" name="subtitle" placeholder="Subtitle" x-model="slider.subtitle" class="px-4 py-2.5 border border-gray-200 rounded-lg text-sm">
            <textarea name="description" placeholder="Description" rows="2" x-model="slider.description" class="px-4 py-2.5 border border-gray-200 rounded-lg text-sm focus:ring-2 focus:ring-primary/20 focus:border-primary outline-none"></textarea>
            <input type="text" name="btn_text" placeholder="Button Text" x-model="slider.btn_text" class="px-4 py-2.5 border border-gray-200 rounded-lg text-sm">
            <input type="text" name="btn_link" placeholder="Button Link" x-model="slider.btn_link" class="px-4 py-2.5 border border-gray-200 rounded-lg text-sm">
            <input type="number" name="order" placeholder="Order" x-model="slider.order" class="px-4 py-2.5 border border-gray-200 rounded-lg text-sm">
            <select name="status" x-model="slider.status" class="px-4 py-2.5 border border-gray-200 rounded-lg text-sm">
                <option value="active">Active</option>
                <option value="inactive">Inactive</option>
            </select>
            <label class="block text-xs text-heading/60 -mb-3">Duration (seconds)</label>
            <input type="number" name="duration" placeholder="Duration (seconds)" x-model="slider.duration" min="1" max="3600" class="px-4 py-2.5 border border-gray-200 rounded-lg text-sm">
            <div>
                <label class="block text-xs text-heading/60 mb-1">Image (leave empty to keep current)</label>
                <img x-show="slider.image" :src="'/storage/' + slider.image" alt="Current image" class="w-32 h-20 object-cover rounded-lg mb-2 border border-gray-200">
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
