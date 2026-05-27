@extends('layouts.dashboard')
@section('title', 'Testimonials')
@section('page-title', 'Testimonial')
@section('user-name', 'Admin')
@section('sidebar')@include('components.admin-sidebar')@stop
@section('content')
<div class="bg-white rounded-xl shadow-sm mb-6">
    <div class="p-6 border-b border-gray-100">
        <h3 class="font-bold text-heading">Add New Testimonial</h3>
    </div>
    <div class="p-6">
        <form method="POST" action="/admin/testimonial" class="flex flex-wrap gap-4">
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
                        <form method="POST" action="/admin/testimonial/{{ $t->id }}/delete" class="inline" onsubmit="return confirm('Delete this testimonial?')">
                            @csrf
                            <button type="submit" class="text-xs text-red-500 hover:underline font-semibold">Delete</button>
                        </form>
                    </td>
                </tr>
                @empty
                <tr><td colspan="6" class="py-8 text-center text-heading/50 text-sm">No testimonials found.</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
@endsection
