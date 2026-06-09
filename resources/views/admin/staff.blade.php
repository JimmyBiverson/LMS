@extends('layouts.dashboard')
@section('title', 'Staff Manage')
@section('page-title', 'Staff Manage')
@section('user-name', 'Admin')
@section('sidebar')@include('components.admin-sidebar')@stop
@section('content')

<div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
    <div class="lg:col-span-2">
        <div class="bg-white rounded-xl shadow-sm">
            <div class="p-6 border-b border-gray-100 flex items-center justify-between">
                <h3 class="font-bold text-heading">Staff Members</h3>
            </div>
            <div class="overflow-x-auto">
                <table class="w-full text-sm">
                    <thead><tr class="bg-gray-50 text-heading/60 text-xs uppercase tracking-wider">
                        <th class="text-left py-4 px-6 font-semibold">#</th>
                        <th class="text-left py-4 px-6 font-semibold">Name</th>
                        <th class="text-left py-4 px-6 font-semibold">Email</th>
                        <th class="text-left py-4 px-6 font-semibold">Designation</th>
                        <th class="text-left py-4 px-6 font-semibold">Status</th>
                        <th class="text-left py-4 px-6 font-semibold">Action</th>
                    </tr></thead>
                    <tbody class="divide-y divide-gray-100">
                        @forelse($staffMembers as $i => $s)
                        <tr class="hover:bg-gray-50">
                            <td class="py-4 px-6 text-heading/70">{{ $i + 1 }}</td>
                            <td class="py-4 px-6 font-semibold text-heading">{{ $s->name }}</td>
                            <td class="py-4 px-6 text-heading/70">{{ $s->email }}</td>
                            <td class="py-4 px-6 text-heading/70">{{ $s->designation ?? 'Staff' }}</td>
                            <td class="py-4 px-6">
                                <span class="px-3 py-1 rounded-full text-xs font-bold {{ $s->status === 'active' ? 'bg-green-100 text-green-700' : 'bg-red-100 text-red-700' }}">{{ ucfirst($s->status) }}</span>
                            </td>
                            <td class="py-4 px-6">
                                <div class="flex items-center gap-2">
                                    <button onclick="editStaff({{ $s->id }}, '{{ $s->name }}', '{{ $s->email }}', '{{ $s->designation }}', '{{ $s->status }}', '{{ $s->phone }}')" class="px-3 py-1.5 text-xs font-semibold bg-primary-50 text-primary rounded-lg hover:bg-primary hover:text-white transition-all duration-300">Edit</button>
                                    <form method="POST" action="/admin/staff/{{ $s->id }}/delete" onsubmit="return confirm('Remove this staff member?')">
                                        @csrf
                                        <button type="submit" class="px-3 py-1.5 text-xs font-semibold bg-red-50 text-red-600 rounded-lg hover:bg-red-600 hover:text-white transition-all duration-300">Delete</button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                        @empty
                        <tr><td colspan="6" class="py-12 text-center text-heading/40">No staff members yet.</td></tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <div>
        <div class="bg-white rounded-xl shadow-sm" x-data="{ editMode: false, id: '', name: '', email: '', designation: '', status: 'active', phone: '' }">
            <div class="p-6 border-b border-gray-100">
                <h3 class="font-bold text-heading" x-text="editMode ? 'Edit Staff' : 'Add New Staff'">Add New Staff</h3>
            </div>
            <div class="p-6">
                <form :action="editMode ? '/admin/staff/' + id : '/admin/staff'" method="POST" class="space-y-4">
                    @csrf
                    <template x-if="editMode"><input type="hidden" name="_method" value="POST"></template>
                    <div>
                        <label class="block text-sm font-semibold text-heading mb-1">Name *</label>
                        <input name="name" x-model="name" type="text" class="w-full px-4 py-3 rounded-lg border border-heading/10 text-sm text-heading font-semibold focus:outline-none focus:border-primary" placeholder="Full Name" required>
                    </div>
                    <div>
                        <label class="block text-sm font-semibold text-heading mb-1">Email *</label>
                        <input name="email" x-model="email" type="email" class="w-full px-4 py-3 rounded-lg border border-heading/10 text-sm text-heading font-semibold focus:outline-none focus:border-primary" placeholder="Email" required>
                    </div>
                    <div>
                        <label class="block text-sm font-semibold text-heading mb-1">Password <span x-show="!editMode">*</span></label>
                        <input name="password" type="password" class="w-full px-4 py-3 rounded-lg border border-heading/10 text-sm text-heading font-semibold focus:outline-none focus:border-primary" placeholder="Password" :required="!editMode">
                        <template x-if="editMode"><p class="text-xs text-heading/40 mt-1">Leave blank to keep current password.</p></template>
                    </div>
                    <div x-show="!editMode">
                        <label class="block text-sm font-semibold text-heading mb-1">Confirm Password <span x-show="!editMode">*</span></label>
                        <input name="password_confirmation" type="password" class="w-full px-4 py-3 rounded-lg border border-heading/10 text-sm text-heading font-semibold focus:outline-none focus:border-primary" placeholder="Confirm Password">
                    </div>
                    <div>
                        <label class="block text-sm font-semibold text-heading mb-1">Designation</label>
                        <input name="designation" x-model="designation" type="text" class="w-full px-4 py-3 rounded-lg border border-heading/10 text-sm text-heading font-semibold focus:outline-none focus:border-primary" placeholder="e.g. Manager">
                    </div>
                    <div>
                        <label class="block text-sm font-semibold text-heading mb-1">Phone</label>
                        <input name="phone" x-model="phone" type="text" class="w-full px-4 py-3 rounded-lg border border-heading/10 text-sm text-heading font-semibold focus:outline-none focus:border-primary" placeholder="Phone">
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
                        <button type="submit" class="flex-1 px-6 py-3 bg-primary text-white font-bold rounded-full hover:opacity-90 transition-all duration-300 text-sm" x-text="editMode ? 'Update Staff' : 'Add Staff'">Add Staff</button>
                        <button type="button" x-show="editMode" @click="editMode = false; name = ''; email = ''; designation = ''; status = 'active'; phone = ''; id = ''" class="px-6 py-3 bg-gray-100 text-heading font-bold rounded-full hover:opacity-90 transition-all duration-300 text-sm">Cancel</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script>
function editStaff(id, name, email, designation, status, phone) {
    const form = document.querySelector('[x-data]').__x.$data;
    form.editMode = true;
    form.id = id;
    form.name = name;
    form.email = email;
    form.designation = designation;
    form.status = status;
    form.phone = phone;
    window.scrollTo({ top: 0, behavior: 'smooth' });
}
</script>
@endpush
@endsection
