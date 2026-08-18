@extends('layouts.dashboard')
@section('title', 'Parent Management')
@section('page-title', 'Parents')
@section('user-name', auth()->user()->name ?? 'Admin')
@section('sidebar')@include('components.admin-sidebar')@stop
@section('content')
@if(session('success'))
<div class="mb-6 px-6 py-4 bg-green-50 border border-green-200 text-green-700 rounded-xl text-sm font-semibold">{{ session('success') }}</div>
@endif
<div class="bg-white rounded-xl shadow-sm mb-6">
    <div class="p-6 border-b border-gray-100"><h3 class="font-bold text-heading">Add Parent</h3></div>
    <div class="p-6">
        <form method="POST" action="/admin/school/parents" class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4">
            @csrf
            <input type="text" name="name" placeholder="Full Name" required class="px-4 py-2.5 border border-gray-200 rounded-lg text-sm">
            <input type="email" name="email" placeholder="Email" required class="px-4 py-2.5 border border-gray-200 rounded-lg text-sm">
            <input type="tel" name="phone" placeholder="Phone" class="px-4 py-2.5 border border-gray-200 rounded-lg text-sm">
            <input type="password" name="password" placeholder="Password" required class="px-4 py-2.5 border border-gray-200 rounded-lg text-sm">
            <div class="md:col-span-2">
                <label class="block text-xs text-heading/60 mb-1">Link Children (students)</label>
                <select name="student_ids[]" multiple class="w-full px-4 py-2.5 border border-gray-200 rounded-lg text-sm" size="3">
                    @foreach($students as $s) <option value="{{ $s->id }}">{{ $s->name }} ({{ $s->email }})</option> @endforeach
                </select>
            </div>
            <button type="submit" class="px-6 py-2.5 bg-primary text-white font-semibold rounded-lg hover:opacity-90 text-sm self-end">Create Parent</button>
        </form>
    </div>
</div>
<div class="bg-white rounded-xl shadow-sm">
    <div class="p-6 border-b border-gray-100"><h3 class="font-bold text-heading">Parents</h3></div>
    <div class="overflow-x-auto">
        <table class="w-full text-sm">
            <thead><tr class="bg-gray-50 text-heading/60 text-xs uppercase tracking-wider">
                <th class="text-left py-4 px-6 font-semibold">#</th><th class="text-left py-4 px-6 font-semibold">Name</th>
                <th class="text-left py-4 px-6 font-semibold">Email</th><th class="text-left py-4 px-6 font-semibold">Phone</th>
                <th class="text-left py-4 px-6 font-semibold">Children</th><th class="text-left py-4 px-6 font-semibold">Action</th>
            </tr></thead>
            <tbody class="divide-y divide-gray-100">
                @forelse($parents as $i=>$p)
                <tr class="hover:bg-gray-50">
                    <td class="py-4 px-6 text-heading/70">{{ $i+1 }}</td>
                    <td class="py-4 px-6 font-semibold text-heading">{{ $p->name }}</td>
                    <td class="py-4 px-6 text-heading/70">{{ $p->email }}</td>
                    <td class="py-4 px-6 text-heading/70">{{ $p->phone ?? '--' }}</td>
                    <td class="py-4 px-6 text-heading/70">{{ $p->children->pluck('name')->implode(', ') ?: '--' }}</td>
                    <td class="py-4 px-6">
                        <form method="POST" action="/admin/school/parents/{{ $p->id }}/delete" x-data @submit.prevent="if(confirm('Delete this parent?')) $el.submit()">
                            @csrf
                            <button type="submit" class="px-3 py-1.5 text-xs font-semibold bg-red-50 text-red-600 rounded-lg hover:bg-red-600 hover:text-white transition-all">Delete</button>
                        </form>
                    </td>
                </tr>
                @empty <tr><td colspan="6" class="py-8 text-center text-heading/50">No parents found.</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
@endsection
