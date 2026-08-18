@extends('layouts.dashboard')
@section('title', 'Class Management')
@section('page-title', 'Classes')
@section('user-name', auth()->user()->name ?? 'Admin')
@section('sidebar')@include('components.admin-sidebar')@stop
@section('content')
@if(session('success'))
<div class="mb-6 px-6 py-4 bg-green-50 border border-green-200 text-green-700 rounded-xl text-sm font-semibold">{{ session('success') }}</div>
@endif
<div class="bg-white rounded-xl shadow-sm mb-6">
    <div class="p-6 border-b border-gray-100"><h3 class="font-bold text-heading">Add New Class</h3></div>
    <div class="p-6">
        <form method="POST" action="/admin/school/classes" class="grid grid-cols-1 md:grid-cols-3 gap-4">
            @csrf
            <input type="text" name="name" placeholder="Class Name" required class="px-4 py-2.5 border border-gray-200 rounded-lg text-sm">
            <input type="text" name="grade" placeholder="Grade (e.g. 5, 6, 7)" required class="px-4 py-2.5 border border-gray-200 rounded-lg text-sm">
            <input type="text" name="section" placeholder="Section (e.g. A, B)" class="px-4 py-2.5 border border-gray-200 rounded-lg text-sm">
            <select name="teacher_id" class="px-4 py-2.5 border border-gray-200 rounded-lg text-sm">
                <option value="">Select Teacher</option>
                @foreach($teachers as $t)
                <option value="{{ $t->id }}">{{ $t->name }}</option>
                @endforeach
            </select>
            <select name="course_id" class="px-4 py-2.5 border border-gray-200 rounded-lg text-sm">
                <option value="">Select Course</option>
                @foreach($courses as $c)
                <option value="{{ $c->id }}">{{ $c->title }}</option>
                @endforeach
            </select>
            <input type="text" name="room" placeholder="Room Number" class="px-4 py-2.5 border border-gray-200 rounded-lg text-sm">
            <input type="number" name="capacity" placeholder="Capacity" value="30" class="px-4 py-2.5 border border-gray-200 rounded-lg text-sm">
            <select name="status" class="px-4 py-2.5 border border-gray-200 rounded-lg text-sm">
                <option value="active">Active</option>
                <option value="inactive">Inactive</option>
            </select>
            <button type="submit" class="px-6 py-2.5 bg-primary text-white font-semibold rounded-lg hover:opacity-90 text-sm">Create Class</button>
        </form>
    </div>
</div>
<div class="bg-white rounded-xl shadow-sm">
    <div class="p-6 border-b border-gray-100"><h3 class="font-bold text-heading">Classes</h3></div>
    <div class="overflow-x-auto">
        <table class="w-full text-sm">
            <thead><tr class="bg-gray-50 text-heading/60 text-xs uppercase tracking-wider">
                <th class="text-left py-4 px-6 font-semibold">#</th>
                <th class="text-left py-4 px-6 font-semibold">Name</th>
                <th class="text-left py-4 px-6 font-semibold">Grade</th>
                <th class="text-left py-4 px-6 font-semibold">Section</th>
                <th class="text-left py-4 px-6 font-semibold">Teacher</th>
                <th class="text-left py-4 px-6 font-semibold">Students</th>
                <th class="text-left py-4 px-6 font-semibold">Status</th>
                <th class="text-left py-4 px-6 font-semibold">Action</th>
            </tr></thead>
            <tbody class="divide-y divide-gray-100">
                @forelse($classes as $i=>$c)
                <tr class="hover:bg-gray-50">
                    <td class="py-4 px-6 text-heading/70">{{ $i+1 }}</td>
                    <td class="py-4 px-6 font-semibold text-heading">{{ $c->name }}</td>
                    <td class="py-4 px-6 text-heading/70">{{ $c->grade }}</td>
                    <td class="py-4 px-6 text-heading/70">{{ $c->section ?? '--' }}</td>
                    <td class="py-4 px-6 text-heading/70">{{ $c->teacher?->name ?? '--' }}</td>
                    <td class="py-4 px-6 text-heading/70">{{ $c->students_count }}</td>
                    <td class="py-4 px-6"><span class="px-3 py-1 rounded-full text-xs font-bold {{ $c->status=='active' ? 'bg-green-100 text-green-700' : 'bg-red-100 text-red-700' }}">{{ ucfirst($c->status) }}</span></td>
                    <td class="py-4 px-6">
                        <div class="flex items-center gap-2">
                            <button x-data @click="$dispatch('open-class-modal', @js($c))" class="px-3 py-1.5 text-xs font-semibold bg-primary-50 text-primary rounded-lg hover:bg-primary hover:text-white transition-all">Edit</button>
                            <form method="POST" action="/admin/school/classes/{{ $c->id }}/delete" x-data @submit.prevent="if(confirm('Delete this class?')) $el.submit()">
                                @csrf
                                <button type="submit" class="px-3 py-1.5 text-xs font-semibold bg-red-50 text-red-600 rounded-lg hover:bg-red-600 hover:text-white transition-all">Delete</button>
                            </form>
                        </div>
                    </td>
                </tr>
                @empty
                <tr><td colspan="8" class="py-8 text-center text-heading/50">No classes found.</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
<div x-data="{ open: false, item: {} }" @open-class-modal.window="item = $event.detail; open = true" x-show="open" x-cloak class="fixed inset-0 z-50 flex items-center justify-center bg-black/50" @click.self="open = false">
    <div class="bg-white rounded-xl shadow-xl w-full max-w-lg mx-4 max-h-[90vh] overflow-y-auto">
        <div class="p-6 border-b border-gray-100 flex items-center justify-between">
            <h3 class="font-bold text-heading">Edit Class</h3>
            <button @click="open = false" class="text-heading/40 hover:text-heading"><i class="ri-close-line text-xl"></i></button>
        </div>
        <form method="POST" :action="'/admin/school/classes/' + item.id" class="p-6 grid grid-cols-1 md:grid-cols-2 gap-4">
            @csrf
            <input type="text" name="name" placeholder="Class Name" required x-model="item.name" class="px-4 py-2.5 border border-gray-200 rounded-lg text-sm">
            <input type="text" name="grade" placeholder="Grade" required x-model="item.grade" class="px-4 py-2.5 border border-gray-200 rounded-lg text-sm">
            <input type="text" name="section" placeholder="Section" x-model="item.section" class="px-4 py-2.5 border border-gray-200 rounded-lg text-sm">
            <select name="teacher_id" x-model="item.teacher_id" class="px-4 py-2.5 border border-gray-200 rounded-lg text-sm">
                <option value="">Select Teacher</option>
                @foreach($teachers as $t) <option value="{{ $t->id }}">{{ $t->name }}</option> @endforeach
            </select>
            <select name="course_id" x-model="item.course_id" class="px-4 py-2.5 border border-gray-200 rounded-lg text-sm">
                <option value="">Select Course</option>
                @foreach($courses as $c) <option value="{{ $c->id }}">{{ $c->title }}</option> @endforeach
            </select>
            <input type="text" name="room" placeholder="Room" x-model="item.room" class="px-4 py-2.5 border border-gray-200 rounded-lg text-sm">
            <input type="number" name="capacity" placeholder="Capacity" x-model="item.capacity" class="px-4 py-2.5 border border-gray-200 rounded-lg text-sm">
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
