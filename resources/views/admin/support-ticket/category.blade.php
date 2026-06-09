@extends('layouts.dashboard')
@section('title', 'Support Categories')
@section('page-title', 'Support Category')
@section('user-name', 'Admin')
@section('sidebar')@include('components.admin-sidebar')@stop
@section('content')

<div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
    <div class="lg:col-span-2">
        <div class="bg-white rounded-xl shadow-sm">
            <div class="p-6 border-b border-gray-100"><h3 class="font-bold text-heading">Support Categories</h3></div>
            <div class="overflow-x-auto">
                <table class="w-full text-sm">
                    <thead><tr class="bg-gray-50 text-heading/60 text-xs uppercase tracking-wider">
                        <th class="text-left py-4 px-6 font-semibold">#</th>
                        <th class="text-left py-4 px-6 font-semibold">Name</th>
                        <th class="text-left py-4 px-6 font-semibold">Slug</th>
                        <th class="text-left py-4 px-6 font-semibold">Status</th>
                        <th class="text-left py-4 px-6 font-semibold">Action</th>
                    </tr></thead>
                    <tbody class="divide-y divide-gray-100">
                        @forelse($categories as $i => $c)
                        <tr class="hover:bg-gray-50">
                            <td class="py-4 px-6 text-heading/70">{{ $i + 1 }}</td>
                            <td class="py-4 px-6 font-semibold text-heading">{{ $c->name }}</td>
                            <td class="py-4 px-6 text-heading/70">{{ $c->slug }}</td>
                            <td class="py-4 px-6">
                                <span class="px-3 py-1 rounded-full text-xs font-bold {{ $c->status === 'active' ? 'bg-green-100 text-green-700' : 'bg-red-100 text-red-700' }}">{{ ucfirst($c->status) }}</span>
                            </td>
                            <td class="py-4 px-6">
                                <div class="flex items-center gap-2">
                                    <button onclick="editCategory({{ $c->id }}, '{{ $c->name }}', '{{ $c->status }}')" class="px-3 py-1.5 text-xs font-semibold bg-primary-50 text-primary rounded-lg hover:bg-primary hover:text-white transition-all duration-300">Edit</button>
                                    <form method="POST" action="/admin/support-ticket/category/{{ $c->id }}/delete" onsubmit="return confirm('Delete this category?')">
                                        @csrf
                                        <button type="submit" class="px-3 py-1.5 text-xs font-semibold bg-red-50 text-red-600 rounded-lg hover:bg-red-600 hover:text-white transition-all duration-300">Delete</button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                        @empty
                        <tr><td colspan="5" class="py-12 text-center text-heading/40">No categories yet.</td></tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <div>
        <div class="bg-white rounded-xl shadow-sm" x-data="{ editMode: false, id: '', name: '', status: 'active' }">
            <div class="p-6 border-b border-gray-100">
                <h3 class="font-bold text-heading" x-text="editMode ? 'Edit Category' : 'Add Category'">Add Category</h3>
            </div>
            <div class="p-6">
                <form :action="editMode ? '/admin/support-ticket/category/' + id : '/admin/support-ticket/category'" method="POST" class="space-y-4">
                    @csrf
                    <div>
                        <label class="block text-sm font-semibold text-heading mb-1">Name *</label>
                        <input name="name" x-model="name" type="text" class="w-full px-4 py-3 rounded-lg border border-heading/10 text-sm text-heading font-semibold focus:outline-none focus:border-primary" placeholder="Category name" required>
                    </div>
                    <template x-if="editMode">
                        <div>
                            <label class="block text-sm font-semibold text-heading mb-1">Status</label>
                            <select name="status" x-model="status" class="w-full px-4 py-3 rounded-lg border border-heading/10 text-sm text-heading font-semibold focus:outline-none focus:border-primary">
                                <option value="active">Active</option>
                                <option value="inactive">Inactive</option>
                            </select>
                        </div>
                    </template>
                    <div class="flex gap-2">
                        <button type="submit" class="flex-1 px-6 py-3 bg-primary text-white font-bold rounded-full hover:opacity-90 transition-all duration-300 text-sm" x-text="editMode ? 'Update' : 'Add'">Add</button>
                        <button type="button" x-show="editMode" @click="editMode = false; name = ''; status = 'active'; id = ''" class="px-6 py-3 bg-gray-100 text-heading font-bold rounded-full hover:opacity-90 transition-all duration-300 text-sm">Cancel</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script>
function editCategory(id, name, status) {
    const form = document.querySelector('[x-data]').__x.$data;
    form.editMode = true;
    form.id = id;
    form.name = name;
    form.status = status;
    window.scrollTo({ top: 0, behavior: 'smooth' });
}
</script>
@endpush
@endsection
