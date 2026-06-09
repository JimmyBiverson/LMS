@extends('layouts.dashboard')
@section('title', 'Meet Provider')
@section('page-title', 'Meet Provider')
@section('user-name', 'Admin')
@section('sidebar')@include('components.admin-sidebar')@stop
@section('content')

<div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
    <div class="lg:col-span-2">
        <div class="bg-white rounded-xl shadow-sm">
            <div class="p-6 border-b border-gray-100"><h3 class="font-bold text-heading">Meet Providers</h3></div>
            <div class="overflow-x-auto">
                <table class="w-full text-sm">
                    <thead><tr class="bg-gray-50 text-heading/60 text-xs uppercase tracking-wider">
                        <th class="text-left py-4 px-6 font-semibold">#</th>
                        <th class="text-left py-4 px-6 font-semibold">Provider</th>
                        <th class="text-left py-4 px-6 font-semibold">Description</th>
                        <th class="text-left py-4 px-6 font-semibold">Status</th>
                        <th class="text-left py-4 px-6 font-semibold">Action</th>
                    </tr></thead>
                    <tbody class="divide-y divide-gray-100">
                        @forelse($providers as $i => $p)
                        <tr class="hover:bg-gray-50">
                            <td class="py-4 px-6 text-heading/70">{{ $i + 1 }}</td>
                            <td class="py-4 px-6 font-semibold text-heading">{{ $p->name }}</td>
                            <td class="py-4 px-6 text-heading/70 max-w-xs truncate">{{ $p->description ?? '—' }}</td>
                            <td class="py-4 px-6">
                                <span class="px-3 py-1 rounded-full text-xs font-bold {{ $p->status === 'active' ? 'bg-green-100 text-green-700' : 'bg-red-100 text-red-700' }}">{{ ucfirst($p->status) }}</span>
                            </td>
                            <td class="py-4 px-6">
                                <div class="flex items-center gap-2">
                                    <button onclick="editProvider({{ $p->id }}, '{{ $p->name }}', '{{ $p->description }}', '{{ $p->status }}')" class="px-3 py-1.5 text-xs font-semibold bg-primary-50 text-primary rounded-lg hover:bg-primary hover:text-white transition-all duration-300">Edit</button>
                                    <form method="POST" action="/admin/meet-provider/{{ $p->id }}/delete" onsubmit="return confirm('Remove this provider?')">
                                        @csrf
                                        <button type="submit" class="px-3 py-1.5 text-xs font-semibold bg-red-50 text-red-600 rounded-lg hover:bg-red-600 hover:text-white transition-all duration-300">Delete</button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                        @empty
                        <tr><td colspan="5" class="py-12 text-center text-heading/40">No meet providers configured.</td></tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <div>
        <div class="bg-white rounded-xl shadow-sm" x-data="{ editMode: false, id: '', name: '', description: '', status: 'active' }">
            <div class="p-6 border-b border-gray-100">
                <h3 class="font-bold text-heading" x-text="editMode ? 'Edit Provider' : 'Add Provider'">Add Provider</h3>
            </div>
            <div class="p-6">
                <form :action="editMode ? '/admin/meet-provider/' + id : '/admin/meet-provider'" method="POST" class="space-y-4">
                    @csrf
                    <div>
                        <label class="block text-sm font-semibold text-heading mb-1">Provider Name *</label>
                        <input name="name" x-model="name" type="text" class="w-full px-4 py-3 rounded-lg border border-heading/10 text-sm text-heading font-semibold focus:outline-none focus:border-primary" placeholder="e.g. Zoom" required>
                    </div>
                    <div>
                        <label class="block text-sm font-semibold text-heading mb-1">Description</label>
                        <textarea name="description" x-model="description" rows="3" class="w-full px-4 py-3 rounded-lg border border-heading/10 text-sm text-heading font-semibold focus:outline-none focus:border-primary" placeholder="Provider description"></textarea>
                    </div>
                    <div>
                        <label class="block text-sm font-semibold text-heading mb-1">API Key</label>
                        <input name="api_key" type="text" class="w-full px-4 py-3 rounded-lg border border-heading/10 text-sm text-heading font-semibold focus:outline-none focus:border-primary" placeholder="API Key (optional)">
                    </div>
                    <div>
                        <label class="block text-sm font-semibold text-heading mb-1">Status</label>
                        <select name="status" x-model="status" class="w-full px-4 py-3 rounded-lg border border-heading/10 text-sm text-heading font-semibold focus:outline-none focus:border-primary">
                            <option value="active">Active</option>
                            <option value="inactive">Inactive</option>
                        </select>
                    </div>
                    <div class="flex gap-2">
                        <button type="submit" class="flex-1 px-6 py-3 bg-primary text-white font-bold rounded-full hover:opacity-90 transition-all duration-300 text-sm" x-text="editMode ? 'Update Provider' : 'Add Provider'">Add Provider</button>
                        <button type="button" x-show="editMode" @click="editMode = false; name = ''; description = ''; status = 'active'; id = ''" class="px-6 py-3 bg-gray-100 text-heading font-bold rounded-full hover:opacity-90 transition-all duration-300 text-sm">Cancel</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script>
function editProvider(id, name, description, status) {
    const form = document.querySelector('[x-data]').__x.$data;
    form.editMode = true;
    form.id = id;
    form.name = name;
    form.description = description;
    form.status = status;
    window.scrollTo({ top: 0, behavior: 'smooth' });
}
</script>
@endpush
@endsection
