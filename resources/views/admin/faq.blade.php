@extends('layouts.dashboard')
@section('title', 'FAQ Manage')
@section('page-title', 'Faq Manage')
@section('user-name', auth()->user()->name ?? 'Admin')
@section('sidebar')@include('components.admin-sidebar')@stop
@section('content')
@if(session('success'))
<div class="mb-6 px-6 py-4 bg-green-50 border border-green-200 text-green-700 rounded-xl text-sm font-semibold flex items-center gap-2">
    <i class="ri-checkbox-circle-fill"></i> {{ session('success') }}
</div>
@endif
@if(session('error'))
<div class="mb-6 px-6 py-4 bg-red-50 border border-red-200 text-red-700 rounded-xl text-sm font-semibold flex items-center gap-2">
    <i class="ri-error-warning-fill"></i> {{ session('error') }}
</div>
@endif
<div class="bg-white rounded-xl shadow-sm mb-6">
    <div class="p-6 border-b border-gray-100">
        <h3 class="font-bold text-heading">Add New FAQ</h3>
    </div>
    <div class="p-6">
        <form method="POST" action="/admin/faq" class="flex flex-wrap gap-4">
            @csrf
            <input type="text" name="question" placeholder="Question" required class="flex-1 min-w-[200px] px-4 py-2.5 border border-gray-200 rounded-lg text-sm focus:ring-2 focus:ring-primary/20 focus:border-primary outline-none">
            <textarea name="answer" placeholder="Answer" required class="flex-[2] px-4 py-2.5 border border-gray-200 rounded-lg text-sm focus:ring-2 focus:ring-primary/20 focus:border-primary outline-none" rows="2"></textarea>
            <input type="number" name="order" placeholder="Order" value="0" min="0" class="px-4 py-2.5 border border-gray-200 rounded-lg text-sm w-24">
            <select name="status" class="px-4 py-2.5 border border-gray-200 rounded-lg text-sm">
                <option value="active">Active</option>
                <option value="inactive">Inactive</option>
            </select>
            <button type="submit" class="px-6 py-2.5 bg-primary text-white font-semibold rounded-lg hover:opacity-90 text-sm">Add</button>
        </form>
    </div>
</div>
<div class="bg-white rounded-xl shadow-sm">
    <div class="p-6 border-b border-gray-100"><h3 class="font-bold text-heading">Frequently Asked Questions</h3></div>
    <div class="overflow-x-auto">
        <table class="w-full text-sm">
            <thead><tr class="bg-gray-50 text-heading/60 text-xs uppercase tracking-wider">
                <th class="text-left py-4 px-6 font-semibold">#</th>
                <th class="text-left py-4 px-6 font-semibold">Question</th>
                <th class="text-left py-4 px-6 font-semibold">Answer</th>
                <th class="text-left py-4 px-6 font-semibold">Order</th>
                <th class="text-left py-4 px-6 font-semibold">Status</th>
                <th class="text-left py-4 px-6 font-semibold">Action</th>
            </tr></thead>
            <tbody class="divide-y divide-gray-100">
                @forelse($faqs as $i=>$f)
                <tr class="hover:bg-gray-50">
                    <td class="py-4 px-6 text-heading/70">{{ $i+1 }}</td>
                    <td class="py-4 px-6 font-semibold text-heading max-w-xs truncate">{{ $f->question }}</td>
                    <td class="py-4 px-6 text-heading/70 max-w-md truncate">{{ $f->answer }}</td>
                    <td class="py-4 px-6 text-heading/70">{{ $f->order }}</td>
                    <td class="py-4 px-6"><span class="px-3 py-1 rounded-full text-xs font-bold {{ $f->status=='active' ? 'bg-green-100 text-green-700' : 'bg-red-100 text-red-700' }}">{{ ucfirst($f->status) }}</span></td>
                    <td class="py-4 px-6">
                        <div class="flex items-center gap-2">
                            <button x-data @click="$dispatch('open-faq-edit-modal', @js($f))" class="text-xs text-primary hover:underline font-semibold">Edit</button>
                            <form method="POST" action="/admin/faq/{{ $f->id }}/delete" class="inline" x-data @submit.prevent="if(confirm('Delete this FAQ?')) $el.submit()">
                                @csrf
                                <button type="submit" class="text-xs text-red-500 hover:underline font-semibold">Delete</button>
                            </form>
                        </div>
                    </td>
                </tr>
                @empty
                <tr><td colspan="6" class="py-8 text-center text-heading/50 text-sm">No FAQs found.</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>

{{-- Edit FAQ Modal --}}
<div x-data="{ open: false, faq: {} }"
     @open-faq-edit-modal.window="faq = $event.detail; open = true"
     x-show="open"
     x-cloak
     class="fixed inset-0 z-50 flex items-center justify-center bg-black/50"
     @click.self="open = false">
    <div class="bg-white rounded-xl shadow-xl w-full max-w-lg mx-4 max-h-[90vh] overflow-y-auto">
        <div class="p-6 border-b border-gray-100 flex items-center justify-between">
            <h3 class="font-bold text-heading">Edit FAQ</h3>
            <button @click="open = false" class="text-heading/40 hover:text-heading"><i class="ri-close-line text-xl"></i></button>
        </div>
        <form method="POST" :action="'/admin/faq/' + faq.id" class="p-6 grid grid-cols-1 gap-4">
            @csrf
            <input type="text" name="question" placeholder="Question" required x-model="faq.question" class="px-4 py-2.5 border border-gray-200 rounded-lg text-sm">
            <textarea name="answer" placeholder="Answer" required x-model="faq.answer" rows="2" class="px-4 py-2.5 border border-gray-200 rounded-lg text-sm"></textarea>
            <input type="number" name="order" placeholder="Order" min="0" x-model="faq.order" class="px-4 py-2.5 border border-gray-200 rounded-lg text-sm">
            <select name="status" x-model="faq.status" class="px-4 py-2.5 border border-gray-200 rounded-lg text-sm">
                <option value="active">Active</option>
                <option value="inactive">Inactive</option>
            </select>
            <div class="flex items-center gap-2 pt-2">
                <button type="submit" class="px-6 py-2.5 bg-primary text-white font-semibold rounded-lg hover:opacity-90 text-sm">Update</button>
                <button type="button" @click="open = false" class="px-6 py-2.5 border border-gray-200 text-heading/70 font-semibold rounded-lg hover:bg-gray-50 text-sm">Cancel</button>
            </div>
        </form>
    </div>
</div>
@endsection
