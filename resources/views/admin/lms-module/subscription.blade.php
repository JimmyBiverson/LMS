@extends('layouts.dashboard')
@section('title', 'Subscription')
@section('page-title', 'Subscription')
@section('user-name', 'Admin')
@section('sidebar')@include('components.admin-sidebar')@stop
@section('content')

<div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
    <div class="lg:col-span-2">
        <div class="bg-white rounded-xl shadow-sm">
            <div class="p-6 border-b border-gray-100"><h3 class="font-bold text-heading">Subscription Plans</h3></div>
            <div class="overflow-x-auto">
                <table class="w-full text-sm">
                    <thead><tr class="bg-gray-50 text-heading/60 text-xs uppercase tracking-wider">
                        <th class="text-left py-4 px-6 font-semibold">#</th>
                        <th class="text-left py-4 px-6 font-semibold">Plan</th>
                        <th class="text-left py-4 px-6 font-semibold">Price</th>
                        <th class="text-left py-4 px-6 font-semibold">Duration</th>
                        <th class="text-left py-4 px-6 font-semibold">Subscribers</th>
                        <th class="text-left py-4 px-6 font-semibold">Status</th>
                        <th class="text-left py-4 px-6 font-semibold">Action</th>
                    </tr></thead>
                    <tbody class="divide-y divide-gray-100">
                        @forelse($plans as $i => $plan)
                        <tr class="hover:bg-gray-50">
                            <td class="py-4 px-6 text-heading/70">{{ $i + 1 }}</td>
                            <td class="py-4 px-6 font-semibold text-heading">{{ $plan->name }}</td>
                            <td class="py-4 px-6 text-heading/70">${{ number_format($plan->price, 2) }}</td>
                            <td class="py-4 px-6 text-heading/70">{{ ucfirst($plan->duration) }}</td>
                            <td class="py-4 px-6 text-heading/70">{{ $plan->user_subscriptions_count }}</td>
                            <td class="py-4 px-6">
                                <span class="px-3 py-1 rounded-full text-xs font-bold {{ $plan->status === 'active' ? 'bg-green-100 text-green-700' : 'bg-red-100 text-red-700' }}">{{ ucfirst($plan->status) }}</span>
                            </td>
                            <td class="py-4 px-6">
                                <div class="flex items-center gap-2">
                                    <button onclick="editPlan({{ $plan->id }}, '{{ $plan->name }}', '{{ $plan->description }}', '{{ $plan->price }}', '{{ $plan->duration }}', '{{ $plan->duration_months }}', '{{ $plan->status }}', {{ json_encode($plan->features) }})" class="px-3 py-1.5 text-xs font-semibold bg-primary-50 text-primary rounded-lg hover:bg-primary hover:text-white transition-all duration-300">Edit</button>
                                    <form method="POST" action="/admin/lms-module/subscription/{{ $plan->id }}/delete" onsubmit="return confirm('Remove this plan?')">
                                        @csrf
                                        <button type="submit" class="px-3 py-1.5 text-xs font-semibold bg-red-50 text-red-600 rounded-lg hover:bg-red-600 hover:text-white transition-all duration-300">Delete</button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                        @empty
                        <tr><td colspan="7" class="py-12 text-center text-heading/40">No subscription plans yet.</td></tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <div>
        <div class="bg-white rounded-xl shadow-sm" x-data="{ editMode: false, id: '', name: '', description: '', price: '', duration: 'monthly', duration_months: 1, features: '', status: 'active' }">
            <div class="p-6 border-b border-gray-100">
                <h3 class="font-bold text-heading" x-text="editMode ? 'Edit Plan' : 'Add Plan'">Add Plan</h3>
            </div>
            <div class="p-6">
                <form :action="editMode ? '/admin/lms-module/subscription/' + id : '/admin/lms-module/subscription'" method="POST" class="space-y-4">
                    @csrf
                    <div>
                        <label class="block text-sm font-semibold text-heading mb-1">Plan Name *</label>
                        <input name="name" x-model="name" type="text" class="w-full px-4 py-3 rounded-lg border border-heading/10 text-sm text-heading font-semibold focus:outline-none focus:border-primary" placeholder="e.g. Basic" required>
                    </div>
                    <div>
                        <label class="block text-sm font-semibold text-heading mb-1">Description</label>
                        <textarea name="description" x-model="description" rows="2" class="w-full px-4 py-3 rounded-lg border border-heading/10 text-sm text-heading font-semibold focus:outline-none focus:border-primary" placeholder="Plan description"></textarea>
                    </div>
                    <div>
                        <label class="block text-sm font-semibold text-heading mb-1">Price ($) *</label>
                        <input name="price" x-model="price" type="number" step="0.01" min="0" class="w-full px-4 py-3 rounded-lg border border-heading/10 text-sm text-heading font-semibold focus:outline-none focus:border-primary" placeholder="9.99" required>
                    </div>
                    <div class="grid grid-cols-2 gap-4">
                        <div>
                            <label class="block text-sm font-semibold text-heading mb-1">Duration *</label>
                            <select name="duration" x-model="duration" class="w-full px-4 py-3 rounded-lg border border-heading/10 text-sm text-heading font-semibold focus:outline-none focus:border-primary">
                                <option value="monthly">Monthly</option>
                                <option value="yearly">Yearly</option>
                            </select>
                        </div>
                        <div>
                            <label class="block text-sm font-semibold text-heading mb-1">Duration (months) *</label>
                            <input name="duration_months" x-model="duration_months" type="number" min="1" class="w-full px-4 py-3 rounded-lg border border-heading/10 text-sm text-heading font-semibold focus:outline-none focus:border-primary" required>
                        </div>
                    </div>
                    <div>
                        <label class="block text-sm font-semibold text-heading mb-1">Features (one per line)</label>
                        <textarea name="features" x-model="features" rows="4" class="w-full px-4 py-3 rounded-lg border border-heading/10 text-sm text-heading font-semibold focus:outline-none focus:border-primary" placeholder="Feature 1&#10;Feature 2&#10;Feature 3"></textarea>
                    </div>
                    <div>
                        <label class="block text-sm font-semibold text-heading mb-1">Status</label>
                        <select name="status" x-model="status" class="w-full px-4 py-3 rounded-lg border border-heading/10 text-sm text-heading font-semibold focus:outline-none focus:border-primary">
                            <option value="active">Active</option>
                            <option value="inactive">Inactive</option>
                        </select>
                    </div>
                    <div class="flex gap-2">
                        <button type="submit" class="flex-1 px-6 py-3 bg-primary text-white font-bold rounded-full hover:opacity-90 transition-all duration-300 text-sm" x-text="editMode ? 'Update Plan' : 'Add Plan'">Add Plan</button>
                        <button type="button" x-show="editMode" @click="editMode = false; name = ''; description = ''; price = ''; duration = 'monthly'; duration_months = 1; features = ''; status = 'active'; id = ''" class="px-6 py-3 bg-gray-100 text-heading font-bold rounded-full hover:opacity-90 transition-all duration-300 text-sm">Cancel</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script>
function editPlan(id, name, description, price, duration, durationMonths, status, features) {
    const form = document.querySelector('[x-data]').__x.$data;
    form.editMode = true;
    form.id = id;
    form.name = name;
    form.description = description || '';
    form.price = price;
    form.duration = duration;
    form.duration_months = durationMonths;
    form.status = status;
    form.features = Array.isArray(features) ? features.join('\n') : (features || '');
    window.scrollTo({ top: 0, behavior: 'smooth' });
}
</script>
@endpush
@endsection
