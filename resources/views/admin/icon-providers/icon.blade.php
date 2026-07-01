@extends('layouts.dashboard')
@section('title', 'Icon Providers')
@section('page-title', 'Icon Providers')
@section('user-name', auth()->user()->name ?? 'Admin')
@section('sidebar')@include('components.admin-sidebar')@stop
@section('content')
<div class="bg-white rounded-xl shadow-sm mb-6">
    <div class="p-6 border-b border-gray-100">
        <h3 class="font-bold text-heading">Add New Provider</h3>
    </div>
    <div class="p-6">
        <form method="POST" action="/admin/icon-providers/icon" class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
            @csrf
            <input type="text" name="name" placeholder="Name" required class="px-4 py-2.5 border border-gray-200 rounded-lg text-sm focus:ring-2 focus:ring-primary/20 focus:border-primary outline-none">
            <input type="url" name="url" placeholder="URL" required class="px-4 py-2.5 border border-gray-200 rounded-lg text-sm">
            <select name="status" class="px-4 py-2.5 border border-gray-200 rounded-lg text-sm">
                <option value="active">Active</option>
                <option value="inactive">Inactive</option>
            </select>
            <button type="submit" class="px-6 py-2.5 bg-primary text-white font-semibold rounded-lg hover:opacity-90 text-sm md:col-span-2 lg:col-span-3">Add Provider</button>
        </form>
    </div>
</div>

<div class="bg-white rounded-xl shadow-sm">
    <div class="p-6 border-b border-gray-100"><h3 class="font-bold text-heading">Icon Providers</h3></div>
    <div class="overflow-x-auto">
        <table class="w-full text-sm">
            <thead><tr class="bg-gray-50 text-heading/60 text-xs uppercase tracking-wider">
                <th class="text-left py-4 px-6 font-semibold">#</th>
                <th class="text-left py-4 px-6 font-semibold">Provider</th>
                <th class="text-left py-4 px-6 font-semibold">URL</th>
                <th class="text-left py-4 px-6 font-semibold">Status</th>
                <th class="text-left py-4 px-6 font-semibold">Action</th>
            </tr></thead>
            <tbody class="divide-y divide-gray-100">
                @forelse($providers as $i=>$p)
                <tr class="hover:bg-gray-50">
                    <td class="py-4 px-6 text-heading/70">{{ $i+1 }}</td>
                    <td class="py-4 px-6 font-semibold text-heading">{{ $p->name }}</td>
                    <td class="py-4 px-6 text-heading/70"><a href="{{ $p->url }}" target="_blank" class="text-primary hover:underline">{{ $p->url }}</a></td>
                    <td class="py-4 px-6"><span class="px-3 py-1 rounded-full text-xs font-bold {{ $p->status=='active' ? 'bg-green-100 text-green-700' : 'bg-red-100 text-red-700' }}">{{ ucfirst($p->status) }}</span></td>
                    <td class="py-4 px-6">
                        <div class="flex items-center gap-2">
                            <button x-data @click="$dispatch('open-edit-modal', @js($p))" class="px-3 py-1.5 text-xs font-semibold bg-primary-50 text-primary rounded-lg hover:bg-primary hover:text-white transition-all duration-300">Edit</button>
                            <form method="POST" action="/admin/icon-providers/icon/{{ $p->id }}/delete" x-data @submit.prevent="if(confirm('Delete this provider?')) $el.submit()">
                                @csrf
                                <button type="submit" class="px-3 py-1.5 text-xs font-semibold bg-red-50 text-red-600 rounded-lg hover:bg-red-600 hover:text-white transition-all duration-300">Delete</button>
                            </form>
                        </div>
                    </td>
                </tr>
                @empty
                <tr><td colspan="5" class="py-8 text-center text-heading/50 text-sm">No providers found.</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>

<div x-data="{ open: false, item: {} }"
     @open-edit-modal.window="item = $event.detail; open = true"
     x-show="open"
     x-cloak
     class="fixed inset-0 z-50 flex items-center justify-center bg-black/50"
     @click.self="open = false">
    <div class="bg-white rounded-xl shadow-xl w-full max-w-lg mx-4 max-h-[90vh] overflow-y-auto">
        <div class="p-6 border-b border-gray-100 flex items-center justify-between">
            <h3 class="font-bold text-heading">Edit Provider</h3>
            <button @click="open = false" class="text-heading/40 hover:text-heading"><i class="ri-close-line text-xl"></i></button>
        </div>
        <form method="POST" :action="'/admin/icon-providers/icon/' + item.id + '/update'" class="p-6 grid grid-cols-1 md:grid-cols-2 gap-4">
            @csrf
            <input type="text" name="name" placeholder="Name" required x-model="item.name" class="px-4 py-2.5 border border-gray-200 rounded-lg text-sm focus:ring-2 focus:ring-primary/20 focus:border-primary outline-none">
            <input type="url" name="url" placeholder="URL" required x-model="item.url" class="px-4 py-2.5 border border-gray-200 rounded-lg text-sm">
            <select name="status" x-model="item.status" class="px-4 py-2.5 border border-gray-200 rounded-lg text-sm">
                <option value="active">Active</option>
                <option value="inactive">Inactive</option>
            </select>
            <div class="flex items-center gap-2 pt-2 md:col-span-2">
                <button type="submit" class="px-6 py-2.5 bg-primary text-white font-semibold rounded-lg hover:opacity-90 text-sm">Update</button>
                <button type="button" @click="open = false" class="px-6 py-2.5 border border-gray-200 text-heading/70 font-semibold rounded-lg hover:bg-gray-50 text-sm">Cancel</button>
            </div>
        </form>
    </div>
</div>
@endsection
