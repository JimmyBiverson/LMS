@extends('layouts.dashboard')
@section('title', 'Testimonials')
@section('page-title', 'Testimonial')
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
        <h3 class="font-bold text-heading">Add New Testimonial</h3>
    </div>
    <div class="p-6">
        <form method="POST" action="/admin/testimonial" enctype="multipart/form-data" class="flex flex-wrap gap-4">
            @csrf
            <input type="text" name="name" placeholder="Client Name" required class="flex-1 min-w-[150px] px-4 py-2.5 border border-gray-200 rounded-lg text-sm focus:ring-2 focus:ring-primary/20 focus:border-primary outline-none">
            <input type="text" name="position" placeholder="Position (e.g. CEO, Company)" class="flex-1 min-w-[150px] px-4 py-2.5 border border-gray-200 rounded-lg text-sm">
            <textarea name="content" placeholder="Testimonial content" required class="flex-[2] px-4 py-2.5 border border-gray-200 rounded-lg text-sm" rows="2"></textarea>
            <select name="rating" class="px-4 py-2.5 border border-gray-200 rounded-lg text-sm">
                @for($r=5;$r>=1;$r--)
                <option value="{{ $r }}">{{ $r }} Star{{ $r>1?'s':'' }}</option>
                @endfor
            </select>
            <select name="status" class="px-4 py-2.5 border border-gray-200 rounded-lg text-sm">
                <option value="active">Active</option>
                <option value="inactive">Inactive</option>
            </select>
            <input type="file" name="avatar" accept="image/jpeg,image/png,image/webp" class="px-4 py-2.5 border border-gray-200 rounded-lg text-sm">
            <button type="submit" class="px-6 py-2.5 bg-primary text-white font-semibold rounded-lg hover:opacity-90 text-sm">Add</button>
        </form>
    </div>
</div>
<div class="bg-white rounded-xl shadow-sm">
    <div class="p-6 border-b border-gray-100"><h3 class="font-bold text-heading">Testimonials</h3></div>
    <div class="overflow-x-auto">
        <table class="w-full text-sm">
            <thead><tr class="bg-gray-50 text-heading/60 text-xs uppercase tracking-wider">
                <th class="text-left py-4 px-6 font-semibold">#</th>
                <th class="text-left py-4 px-6 font-semibold">Name</th>
                <th class="text-left py-4 px-6 font-semibold">Position</th>
                <th class="text-left py-4 px-6 font-semibold">Rating</th>
                <th class="text-left py-4 px-6 font-semibold">Status</th>
                <th class="text-left py-4 px-6 font-semibold">Action</th>
            </tr></thead>
            <tbody class="divide-y divide-gray-100">
                @forelse($testimonials as $i=>$t)
                <tr class="hover:bg-gray-50">
                    <td class="py-4 px-6 text-heading/70">{{ $i+1 }}</td>
                    <td class="py-4 px-6 font-semibold text-heading">{{ $t->name }}</td>
                    <td class="py-4 px-6 text-heading/70">{{ $t->position ?? '--' }}</td>
                    <td class="py-4 px-6"><div class="flex text-amber-400 text-xs">@for($s=0;$s<$t->rating;$s++)<i class="ri-star-fill"></i>@endfor</div></td>
                    <td class="py-4 px-6"><span class="px-3 py-1 rounded-full text-xs font-bold {{ $t->status=='active' ? 'bg-green-100 text-green-700' : 'bg-red-100 text-red-700' }}">{{ ucfirst($t->status) }}</span></td>
                    <td class="py-4 px-6">
                        <div class="flex items-center gap-2">
                            <button x-data @click="$dispatch('open-testimonial-edit-modal', @js($t))" class="text-xs text-primary hover:underline font-semibold">Edit</button>
                            <form method="POST" action="/admin/testimonial/{{ $t->id }}/delete" class="inline" x-data @submit.prevent="if(confirm('Delete this testimonial?')) $el.submit()">
                                @csrf
                                <button type="submit" class="text-xs text-red-500 hover:underline font-semibold">Delete</button>
                            </form>
                        </div>
                    </td>
                </tr>
                @empty
                <tr><td colspan="6" class="py-8 text-center text-heading/50 text-sm">No testimonials found.</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>

{{-- Edit Testimonial Modal --}}
<div x-data="{ open: false, testimonial: {} }"
     @open-testimonial-edit-modal.window="testimonial = $event.detail; open = true"
     x-show="open"
     x-cloak
     class="fixed inset-0 z-50 flex items-center justify-center bg-black/50"
     @click.self="open = false">
    <div class="bg-white rounded-xl shadow-xl w-full max-w-lg mx-4 max-h-[90vh] overflow-y-auto">
        <div class="p-6 border-b border-gray-100 flex items-center justify-between">
            <h3 class="font-bold text-heading">Edit Testimonial</h3>
            <button @click="open = false" class="text-heading/40 hover:text-heading"><i class="ri-close-line text-xl"></i></button>
        </div>
        <form method="POST" :action="'/admin/testimonial/' + testimonial.id" enctype="multipart/form-data" class="p-6 grid grid-cols-1 gap-4">
            @csrf
            <input type="text" name="name" placeholder="Client Name" required x-model="testimonial.name" class="px-4 py-2.5 border border-gray-200 rounded-lg text-sm">
            <input type="text" name="position" placeholder="Position" x-model="testimonial.position" class="px-4 py-2.5 border border-gray-200 rounded-lg text-sm">
            <textarea name="content" placeholder="Testimonial content" required x-model="testimonial.content" rows="2" class="px-4 py-2.5 border border-gray-200 rounded-lg text-sm"></textarea>
            <select name="rating" x-model="testimonial.rating" class="px-4 py-2.5 border border-gray-200 rounded-lg text-sm">
                @for($r=5;$r>=1;$r--)
                <option value="{{ $r }}">{{ $r }} Star{{ $r>1?'s':'' }}</option>
                @endfor
            </select>
            <select name="status" x-model="testimonial.status" class="px-4 py-2.5 border border-gray-200 rounded-lg text-sm">
                <option value="active">Active</option>
                <option value="inactive">Inactive</option>
            </select>
            <div>
                <label class="block text-xs text-heading/60 mb-1">Avatar (leave empty to keep current)</label>
                <input type="file" name="avatar" accept="image/jpeg,image/png,image/webp" class="w-full px-4 py-2.5 border border-gray-200 rounded-lg text-sm">
            </div>
            <div class="flex items-center gap-2 pt-2">
                <button type="submit" class="px-6 py-2.5 bg-primary text-white font-semibold rounded-lg hover:opacity-90 text-sm">Update</button>
                <button type="button" @click="open = false" class="px-6 py-2.5 border border-gray-200 text-heading/70 font-semibold rounded-lg hover:bg-gray-50 text-sm">Cancel</button>
            </div>
        </form>
    </div>
</div>
@endsection
